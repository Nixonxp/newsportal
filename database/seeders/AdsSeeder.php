<?php

namespace Database\Seeders;

use Faker\Generator as Faker;
use File;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdsSeeder extends Seeder
{
    use WithFaker;

    protected $storage = 'app/public/images';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $faker->addProvider(new \Smknstd\FakerPicsumImages\FakerPicsumImagesProvider($faker));
        $storagePath = storage_path($this->storage);

        if(!File::exists($storagePath)){
            File::makeDirectory($storagePath, null, true);
        }

        DB::table('ads')->insert([
            [
                'name' => 'Header',
                'link' => '/',
                'type' => 'header',
                'image' => 'images/' . $faker->image(storage_path($this->storage),728,90, false, null, true),
                'showdate_start' => Carbon::now(),
                'showdate_end' => $faker->dateTimeBetween('now', '+2 months'),
            ],
            [
                'name' => 'side',
                'link' => '/',
                'type' => 'side',
                'image' => 'images/' . $faker->image(storage_path($this->storage),255,293,false, null, true),
                'showdate_start' => Carbon::now(),
                'showdate_end' => $faker->dateTimeBetween('now', '+2 months'),
            ],
        ]);
    }
}
