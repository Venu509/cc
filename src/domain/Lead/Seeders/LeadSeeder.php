<?php

namespace Domain\Lead\Seeders;

use Domain\Lead\Models\Lead;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        Lead::factory(10)->create();
    }
}
