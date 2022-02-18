<?php

namespace App\Services\Currency;

use App\Exceptions\ServiceException;
use App\Jobs\ProccessSendAdminEmail;
use App\Services\Currency\Interfaces\CurrencyDataManagerInterface;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncManager
{
    /**
     * @throws Exception
     */
    public function handle($baseCurrency = null, $force = false, bool $showResult = false): bool
    {
        try {
            $currencyDataCollection = $this->getCurrencyDtoCollection($baseCurrency);
            $result = (new CurrencySyncService($currencyDataCollection))->sync($force);
            $this->resetCache();
            $this->toLog($result, $showResult);
        } catch (ServiceException $exception) {
            echo $exception . PHP_EOL;
        } catch (Throwable $exception) {
            Log::channel('database')->critical($exception);
            ProccessSendAdminEmail::dispatch((object)[
                'message' => __('admin.service_currency_error')
            ])->delay(5);
            echo 'Error from sync service, please contact with administrator' . PHP_EOL;
        }

        return true;
    }

    /**
     * @return void
     */
    private function resetCache(): void
    {
        Cache::tags('currency_cache_info')->flush();
    }

    /**
     * @param $baseCurrency
     * @return Collection
     * @throws Throwable
     */
    protected function getCurrencyDtoCollection($baseCurrency): Collection
    {
        $managerClass = $this->getDataManagerFromConfig();
        return (new $managerClass)->getCurrencyDto($baseCurrency);
    }

    /**
     * @return CurrencyDataManagerInterface
     * @throws Throwable
     */
    protected function getDataManagerFromConfig(): CurrencyDataManagerInterface
    {
        $dataManagerName = config('currency.default') . 'DataManager';
        $dataManageClass = __NAMESPACE__ . '\\DataManagers\\' . ucwords($dataManagerName);

        throw_if(!class_exists($dataManageClass), Exception::class,
            "Service manager [{$dataManageClass}] not found");

        return new $dataManageClass;
    }

    /**
     * @param array $result
     * @param bool $showResult
     * @return void
     */
    protected function toLog(array $result, bool $showResult): void
    {
        $message = !empty($result)
            ? 'Currency updated successful, result: ' . implode_r_key(', ', $result)
            : 'Currency update process is successful, data is not need updated';

        if ($showResult) {
            echo $message . PHP_EOL;
        }

        Log::channel('database')->info($message);
    }
}
