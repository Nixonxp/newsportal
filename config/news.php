<?php
return [
    /**
     * supported services - newsapi
     */
    'default' => env('NEWS_SERVICE'),
    'news_lang' => 'ru',
    'max_sync_count' => 50,
    'category_list' => ['politics', 'economy', 'society', 'science', 'culture', 'sport'],

    'newsapi' => [
        'api_key' => env('NEWSAPI_API_KEY')
    ],
];
