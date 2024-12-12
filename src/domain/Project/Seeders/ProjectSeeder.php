<?php

namespace Domain\Project\Seeders;

use Domain\Project\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Project no 1',
                'semester' => 'This is semester no 1',
            ],
        ];

        collect($projects)->each(function ($project) {
            Project::factory()->create([
                'name' => $project['name'],
                'semester' => $project['semester'],
            ]);
        });
    }
}
