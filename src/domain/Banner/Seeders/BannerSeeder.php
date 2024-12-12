<?php

namespace Domain\Banner\Seeders;

use Illuminate\Database\Seeder;
use Domain\Banner\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'image' => 'b1.png',
            ],
            [
                'image' => 'b2.png',
            ],
            [
                'image' => 'b3.png',
            ],
        ];

        collect($banners)->each(function ($banner) {
            Banner::factory()->create($banner);
        });
    }
}
