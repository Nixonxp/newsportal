<?php

namespace Tests\Feature\Services\Currency;

use App\Dto\Currency\CurrencyDto;
use App\Jobs\CurrencySync;
use App\Models\Currency;
use App\Services\Currency\CurrencyService;
use App\Services\Currency\CurrencySyncService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Queue;
use Tests\TestCase;

class CurrencyServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function testStartQueueSync()
    {
        Queue::fake();
        (new CurrencyService)->getActualInfo();

        Queue::assertPushed(CurrencySync::class);
    }

    public function testCurrencyService()
    {
        $resultExpectJson = file_get_contents(__DIR__ . '/currencyserviceresult.json');
        $this->setDBLastActualCurrencyData();
        $this->addActualDataDB();

        $result = (new CurrencyService)->getActualInfo();

       $this->assertEquals($resultExpectJson, $result->toJson() . "\n");
    }

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

    private function addActualDataDB()
    {
        $dtoJson = file_get_contents(__DIR__ . '/dto.json');
        $array = json_decode($dtoJson, true);

        $dtoCollection = collect(array_map(static function($value) {
            $currDTO = new CurrencyDto();
            $currDTO->title = $value['title'];
            $currDTO->code = $value['code'];
            $currDTO->rate = (float)$value['rate'];
            $currDTO->base_currency = $value['base_currency'];
            $currDTO->crypt = $value['crypt'];
            $currDTO->date = Carbon::now()->today();

            return $currDTO;
        },$array));

        $syncAction = (new CurrencySyncService($dtoCollection))->sync(true);
    }
}
