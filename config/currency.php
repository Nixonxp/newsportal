<?php
return [
    /**
     * supported services - freecurrencyapi,exchangerate
     */
    'default' => env('CURRENCY_SERVICE'),
    'base_currency' => 'RUB',
    'currency_list' => 'RUB,USD,EUR,GBP,BTC,JPY,CNY',
    'crypt_name' => 'BTC',
    'crypt_currency_target' => 'USD',

    'freecurrencyapi' => [
        'api_key' => env('FREECURRENCY_API_KEY')
    ],
    'exchangerate' => [
        'api_key' => env('EXCHANGERATE_API_KEY')
    ]
];
