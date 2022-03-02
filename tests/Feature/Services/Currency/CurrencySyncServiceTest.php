<?php

namespace Tests\Feature\Services\Currency;

use App\Dto\Currency\CurrencyDto;
use App\Models\Currency;
use App\Services\Currency\CurrencySyncService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use ReflectionClass;
use Tests\TestCase;

class CurrencySyncServiceTest extends TestCase
{
    use DatabaseTransactions;

    private Collection $dtoCollection;
    private CurrencySyncService $serviceObject;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setDBLastActualCurrencyData();
        $this->dtoCollection = $this->jsonToCollectionDto();
        $this->serviceObject = new CurrencySyncService($this->dtoCollection);
    }

    public function testTrendDifferenceValue()
    {
        $expectResult = 2.65094;
        $lastActualRecordYesterday = Currency::where(
            'date',
            '=', \Illuminate\Support\Carbon::now()->yesterday()->toDateString()
            )
            ->where('title', 'USD')
            ->first();

        $item = $this->dtoCollection->where('code', 'USD')->first();

        $result = $this->callProtectedMethod(
            $this->serviceObject,
            'getTrendDifferenceValue',
            [
                $lastActualRecordYesterday,
                $item
            ]);

        $this->assertEquals($expectResult, $result);
    }

    public function dataTrendArray()
    {
        return [
            [
                [
                    'title' => 'USD',
                    'trend' => Currency::UP, //usd
                    'trend_diff' => 2.65094,
                ]
            ],
            [
                [
                    'title' => 'JPY',
                    'trend' => Currency::DOWN, //JPY
                    'trend_diff' => 0.07001,
                ]
            ],
            [
                [
                    'title' => 'CNY',
                    'trend' => Currency::EQ, //CNY
                    'trend_diff' => 0,
                ]
            ],
            [
                [
                    'title' => 'RUB',
                    'trend' => Currency::EQ, //RUB
                    'trend_diff' => 0,
                ]
            ],
            [
                [
                    'title' => 'BTC',
                    'trend' => Currency::EQ, //BTC
                    'trend_diff' => 0,
                ]
            ],
            [
                [
                    'title' => 'GBP',
                    'trend' => Currency::EQ, //GBP
                    'trend_diff' => 0,
                ]
            ],
            [
                [
                    'title' => 'EUR',
                    'trend' => Currency::EQ, //EUR
                    'trend_diff' => 0,
                ]
            ]
        ];
    }

    /**
     * @dataProvider dataTrendArray
     * @param $dtoCollection
     * @param $expectResults
     * @return void
     */
    public function testCheckTrendCurrencies($expectResults)
    {
        $resultItem = $this->callProtectedMethod(
            $this->serviceObject,
            'getTrendCurrency',
            [
                $this->dtoCollection->where('title', $expectResults['title'])->first()
            ]);

        $this->assertEquals($resultItem->trend, $expectResults['trend']);
        $this->assertEquals($resultItem->trend_diff, $expectResults['trend_diff']);
    }

    public function testCheckActualRecordFalse()
    {
        $usdDto = $this->dtoCollection->where('title', 'USD')->first();

        $resultItem = $this->callProtectedMethod(
            $this->serviceObject,
            'isIssetActualRecord',
            [
                $usdDto
            ]);

        $this->assertFalse($resultItem);
    }

    public function testCheckActualRecordTrue()
    {
        $usdDto = $this->dtoCollection->where('title', 'USD')->first();

        $this->serviceObject->sync();

        $resultCheck = $this->callProtectedMethod(
            $this->serviceObject,
            'isIssetActualRecord',
            [
                $usdDto
            ]);

        $this->assertFalse($resultCheck);
    }

    public function testGetActualRecord()
    {
        $usdDto = $this->dtoCollection->where('title', 'USD')->first();

        $this->serviceObject->sync();

        $newIteration = new CurrencySyncService($this->dtoCollection);
        $resultRecord = $this->callProtectedMethod(
            $newIteration,
            'getActualRecord',
            [
                $usdDto
            ]);

        $this->assertEquals(
            [
                $usdDto->title,
                $usdDto->date->toDateString()
            ],
            [
                $resultRecord->title,
                $resultRecord->date
            ]
        );
    }

    protected function jsonToCollectionDto()
    {
        $dtoJson = file_get_contents(__DIR__ . '/dto.json');
        $array = json_decode($dtoJson, true);

        return collect(array_map(static function($value) {
            $currDTO = new CurrencyDto();
            $currDTO->title = $value['title'];
            $currDTO->code = $value['code'];
            $currDTO->rate = (float)$value['rate'];
            $currDTO->base_currency = $value['base_currency'];
            $currDTO->crypt = $value['crypt'];
            $currDTO->date = Carbon::now()->today();

            return $currDTO;
        },$array));
    }

    /**
     * Last day DB
     * USD is lower 0.013464 -> 0.013000
     * JPY is higher 1.559 -> 1.750
     *
     * @return \Illuminate\Support\Collection
     */
    private function setDBLastActualCurrencyData(): void
    {
        $dtoJson = file_get_contents(__DIR__ . '/lastdaydb.json');
        $array = json_decode($dtoJson, true);

        array_map(function($value) {
            $record = Currency::updateOrCreate([
                'title' => $value['title'],
                'code' => $value['code'],
                'date' => Carbon::now()->yesterday()
            ], [
                'rate' => (float)$value['rate'],
                'base_currency' => $value['base_currency'],
                'crypt' => $value['crypt']
            ]);

            return true;
        },$array);
    }
}
