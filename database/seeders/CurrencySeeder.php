<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $data = [
             "USD" => [
                 "title" => "USD",
                 "code" => "USD",
                 "rate" => 0.013281,
                 "base_currency" => false,
                 "crypt" => false,
                 "date" => "2022-02-07T21:00:00.000000Z"
             ],
             "JPY" => [
                 "title" => "JPY",
                 "code" => "JPY",
                 "rate" => 1.5302,
                 "base_currency" => false,
                 "crypt" => false,
                 "date" => "2022-02-07T21:00:00.000000Z"
             ],
             "CNY" => [
                 "title" => "CNY",
                 "code" => "CNY",
                 "rate" => 0.0821,
                 "base_currency" => false,
                 "crypt" => false,
                 "date" => "2022-02-07T21:00:00.000000Z"
             ],
             "RUB" => [
                 "title" => "RUB",
                 "code" => "RUB",
                 "rate" => 1,
                 "base_currency" => true,
                 "crypt" => false,
                 "date" => "2022-02-07T21:00:00.000000Z"
             ],
             "BTC" => [
                 "title" => "BTC",
                 "code" => "BTC",
                 "rate" => 0.000312,
                 "base_currency" => false,
                 "crypt" => true,
                 "date" => "2022-02-07T21:00:00.000000Z"
             ],
             "GBP" => [
                 "title" => "GBP",
                 "code" => "GBP",
                 "rate" => 0.009821,
                 "base_currency" => false,
                 "crypt" => false,
                 "date" => "2022-02-07T21:00:00.000000Z"
             ],
             "EUR" => [
                 "title" => "EUR",
                 "code" => "EUR",
                 "rate" => 0.011612,
                 "base_currency" => false,
                 "crypt" => false,
                 "date" => "2022-02-07T21:00:00.000000Z"
             ]
         ];

        foreach ($data as $dataItem) {
            \DB::table('currencies')->insert($dataItem);
        }
    }
}
