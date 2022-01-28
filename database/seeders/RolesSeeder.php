<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Admin'],
            ['name' => 'Chief-editor'],
            ['name' => 'Editor'],
            ['name' => 'User'],
        ];

        foreach ($data as $dataItem) {
            $dataItem['created_at'] = Carbon::now();
            $dataItem['updated_at'] = Carbon::now();

            \DB::table('roles')->insert($dataItem);
        }
    }
}
