<?php
return [
    'admin.dashboard' => [
        [
            'link' => null,
            'text' => 'Рабочий стол',
            'text_lang' => 'admin.dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'angle_left' => true,
            'active' => 'admin.dashboard*',
            'child' => [
                [
                    'link' => 'admin.dashboard',
                    'text' => 'Рабочий стол #1',
                    'active' => 'admin.dashboard',
                    'icon' => 'far fa-circle',
                ]
            ]
        ]
    ],
    'admin.content' => [
        [
            'link' => 'admin.categories.index',
            'text' => 'Категории',
            'text_lang' => 'admin.categories',
            'icon' => 'fas fa-list',
            'angle_left' => false,
            'active' => 'admin.categories.*',
            'viewPolicy' => ['viewAny', App\Models\Category::class],
        ],
        [
            'link' => 'admin.posts.index',
            'text' => 'Новости',
            'text_lang' => 'admin.news',
            'icon' => 'fas fa-newspaper',
            'angle_left' => false,
            'active' => 'admin.posts.*',
            'viewPolicy' => ['viewAny', App\Models\Post::class],
        ],
    ]
];
