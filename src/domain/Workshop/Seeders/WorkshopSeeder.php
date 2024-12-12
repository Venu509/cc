<?php

namespace Domain\Workshop\Seeders;

use Domain\Workshop\Models\Workshop;
use Illuminate\Database\Seeder;

class WorkshopSeeder extends Seeder
{
    public function run(): void
    {
        $workshops = [
            [
                'title' => 'Workshop no 1',
                'semester' => 'This is semester no 1',
            ],
        ];

        collect($workshops)->each(function ($workshop) {
            Workshop::factory()->create([
                'name' => $workshop['name'],
                'semester' => $workshop['semester'],
            ]);
        });
    }
}
