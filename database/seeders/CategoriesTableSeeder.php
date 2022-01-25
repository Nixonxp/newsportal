<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'name' => 'Политика',
                'slug' => 'politics',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Экономика',
                'slug' => 'economy',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Общество',
                'slug' => 'society',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Наука',
                'slug' => 'science',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Культура',
                'slug' => 'culture',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Спорт',
                'slug' => 'sport',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Остальное',
                'slug' => 'other',
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}
