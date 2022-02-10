<?php

namespace App\Services\Currency;

use App\Dto\Currency\CurrencyDto;
use App\Exceptions\ServiceException;
use App\Models\Currency as Model;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;

class CurrencySyncService
{
    private Collection $lastCurrencies;
    private Collection $newDtoCurrencyData;
    private CurrencyDto $newBaseCurrency;

    /**
     * @throws Exception
     */
    public function __construct(Collection $dtoCollection)
    {
        $this->lastCurrencies = $this->getLastCurrencies();
        $this->newDtoCurrencyData = $dtoCollection;
        $this->checkSetNewBaseCurrency();
    }

    /**
     * Return currency records last 2 days,
     * or yesterday only
     *
     * @return Collection
     */
    public function getLastCurrencies(): Collection
    {
        return Model::select('id', 'title', 'date', 'rate')->where('date', '>=', Carbon::now()->yesterday())->get();
    }

    /**
     * @throws ServiceException
     */
    protected function checkSetNewBaseCurrency(): void
    {
        $this->newBaseCurrency = $this->newDtoCurrencyData->where('title', config('currency.base_currency'))->first();

        if (empty($this->newBaseCurrency)) {
            throw new ServiceException('Not found base currency');
        }
    }

    /**
     * @param bool $force
     * @return array
     * @throws ServiceException
     */
    public function sync(bool $force = false): array
    {
        if ($this->newDtoCurrencyData->isEmpty()) {
            throw new ServiceException('No data for sync');
        }

        $recordResult = $this->newDtoCurrencyData->map(function ($item) use ($force) {
            $result['action'] = null;
            $data = [
                'title' => $item->title,
                'code' => $item->code,
                'rate' => $item->rate,
                'base_currency' => $item->base_currency,
                'crypt' => $item->crypt,
                'date' => $item->date,
                'trend' => $this->getTrendCurrency($item)->trend,
                'trend_diff' => $this->getTrendCurrency($item)->trend_diff,
            ];

            $isActualRecordIsset = $this->isIssetActualRecord($item);

            if (($force && $isActualRecordIsset)) {
                $actualRecord = $this->getActualRecord($item);
                $updateAction = $this->update($actualRecord, $data);
                $result['action']['updated'] = $actualRecord->id;
            } elseif (!$isActualRecordIsset) {
                $result['action']['created'] = $this->create($data)->id;
            }

            return $result;
        })->reject(function ($value) {
            return $value['action'] === null;
        });

        return $recordResult->isEmpty()
            ? []
            : $recordResult->mapToGroups(function ($item, $key) {
                $item['action'] = $item['action'] ?? [];
                return $item['action'];
            })->toArray();
    }

    /**
     * Get trend data
     *
     * @param CurrencyDto $item
     * @return object
     */
    protected function getTrendCurrency(CurrencyDto $item): object
    {
        $result = (object)[
            'trend' => Model::EQ,
            'trend_diff' => null
        ];

        if ($item->base_currency) {
            return $result;
        }

        $lastActualRecordYesterday = $this->lastCurrencies
            ->where('date', '=', Carbon::now()->yesterday()->toDateString())
            ->where('title', $item->title)
            ->first();

        if (!empty($lastActualRecordYesterday)) {

            if ($lastActualRecordYesterday->rate < $item->rate) {
                $result->trend = Model::UP;
            }

            if ($lastActualRecordYesterday->rate > $item->rate) {
                $result->trend = Model::DOWN;
            }

            $result->trend_diff = $this->getTrendDifferenceValue($lastActualRecordYesterday, $item);

        }

        return $result;
    }

    /**
     * Get difference for new record currency
     * from old last day actual record
     *
     * @param Model $lastActualRecordYesterday
     * @param CurrencyDto $item
     * @return float
     */
    #[Pure]
    private function getTrendDifferenceValue(Model $lastActualRecordYesterday, CurrencyDto $item): float
    {
        $firstRateCurrency = CurrencyService::convertFromTargetRateToOrigin($lastActualRecordYesterday->rate);
        $secondRateCurrency = CurrencyService::convertFromTargetRateToOrigin($item->rate);
        return round(abs($firstRateCurrency - $secondRateCurrency), 2);
    }

    /**
     * Check actual record in base
     *
     * @param CurrencyDto $item
     * @return bool
     */
    protected function isIssetActualRecord(CurrencyDto $item): bool
    {
        return $this->lastCurrencies
            ->where('title', $item->title)
            ->where('date', $item->date->toDateString())
            ->isNotEmpty();
    }

    /**
     * @param CurrencyDto $item
     * @return Model
     */
    protected function getActualRecord(CurrencyDto $item): Model
    {
        return $this->lastCurrencies
            ->where('title', $item->title)
            ->where('date', $item->date->toDateString())
            ->first();
    }

    /**
     * @param Model $record
     * @param array $data
     * @return bool
     */
    protected function update(Model $record, array $data): bool
    {
        return $record->update($data);
    }

    /**
     * @param $data
     * @return Model
     */
    protected function create($data): Model
    {
        return Model::create($data);
    }
}
