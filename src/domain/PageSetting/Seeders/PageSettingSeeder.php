<?php

namespace Domain\PageSetting\Seeders;

use Domain\PageSetting\Models\PageSetting;
use Illuminate\Database\Seeder;

class PageSettingSeeder extends Seeder
{
    public function run(): void
    {
        $pageSettings = [
            [
                'type' => 'about',
                'title' => 'About Us',
                'description' => 'We are pleased to introduce ourselves as Spiderfocus, a professional placement services organization. We are a prominent Recruitment Firm offering out of the box Campus recruitment solutions to Institutes and colleges. With a vision to explore and harness the talents of young leaders, we have come up with a concept of Campus recruitment and promotion of institutes and colleges looking to place their fresh candidates.<div><br></div>',
                'email' => null,
                'mobile' => null,
            ],
            [
                'type' => 'contact',
                'title' => 'Contact Us',
                'description' => 'H-126, By-Pass Road<div>New Delhi India</div>',
                'email' => 'info@gmail.com',
                'mobile' => 8988858695,
            ]
        ];

        collect($pageSettings)->each(function ($pageSetting) {
            PageSetting::factory()->create($pageSetting);
        });
    }
}
