<?php

namespace Tests\Feature\Services\Currency\DataManagers;

use App\Services\Currency\Clients\FreecurrencyapiClient;
use App\Services\Currency\DataManagers\FreecurrencyapiDataManager;
use App\Services\Currency\Interfaces\CurrencyClientInterface;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use App\Services\Currency\Clients\ExchangerateClient;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Currency\DataManagers\ExchangerateDataManager;

class ExchangerateDataManagerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp():void
    {
        parent::setUp();

        app()->bind(CurrencyClientInterface::class, function () {
            return new ExchangerateClient();
        });

        $this->responseApiJson = file_get_contents(__DIR__ . '/exchangerateServiceResponse.json');

        $this->testClass =  new class extends ExchangerateDataManager {
            public $json;
            protected function getCurrencyFromBase(): array
            {
                return json_decode($this->json, true, 512, JSON_THROW_ON_ERROR);
            }
        };
    }

    public function testCurrencyDtoDataBuilder()
    {
        $this->testClass->json = $this->responseApiJson;
        $data = $this->testClass->getCurrencyDto();

        $arJsonData = json_decode($this->responseApiJson);

        foreach ($data as $DtoItem) {
            $currencyTitle = $DtoItem->title;
            $this->assertEquals($arJsonData->conversion_rates->$currencyTitle, $DtoItem->rate);
            $this->assertEquals(Carbon::createFromTimestamp($arJsonData->time_last_update_unix)->today(), $DtoItem->date);
        }
    }
}
