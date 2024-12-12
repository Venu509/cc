<?php

namespace Domain\Country\Seeders;

use Domain\Country\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use JsonException;

class CountrySeeder extends Seeder
{
    /**
     * @throws JsonException
     */
    public function run(): void
    {
        $json = File::get(public_path('countries.json'));

        $countries = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        foreach ($countries as $code => $country) {
            Country::factory()->create([
                'code' => $code,
                'name' => $country['name'],
                'region' => $country['region'],
                'timezones' => json_encode($country['timezones'], JSON_THROW_ON_ERROR),
                'iso_alpha_2' => $country['iso']['alpha-2'],
                'iso_alpha_3' => $country['iso']['alpha-3'],
                'iso_numeric' => $country['iso']['numeric'],
                'phone' => implode(',', $country['phone']),
                'emoji' => $country['emoji'],
                'image' => $country['image'],
            ]);
        }
    }
}
