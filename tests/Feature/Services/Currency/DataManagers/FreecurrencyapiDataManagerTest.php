<?php

namespace Tests\Feature\Services\Currency\DataManagers;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use App\Services\Currency\DataManagers\FreecurrencyapiDataManager;

class FreecurrencyapiDataManagerTest extends TestCase
{

    use DatabaseTransactions;

    protected function setUp():void
    {
        parent::setUp();

        $this->responseApiJson = file_get_contents(__DIR__ . '/serviceResponse.json');

        $this->testClass =  new class extends FreecurrencyapiDataManager {
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
            $this->assertEquals($arJsonData->data->$currencyTitle, $DtoItem->rate);
            $this->assertEquals(Carbon::createFromTimestamp($arJsonData->query->timestamp)->today(), $DtoItem->date);
            $this->assertEquals($DtoItem->crypt, (config('currency.crypt_name') === $DtoItem->title));
            $this->assertEquals($DtoItem->base_currency, (config('currency.base_currency') === $DtoItem->title));
        }
    }
}
