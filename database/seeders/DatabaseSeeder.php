<?php

namespace Database\Seeders;

use Domain\Banner\Seeders\BannerSeeder;
use Domain\Category\Seeders\CategorySeeder;
use Domain\Country\Seeders\CountrySeeder;
use Domain\Lead\Seeders\LeadSeeder;
use Domain\Branch\Seeders\BranchSeeder;
use Domain\PageSetting\Seeders\PageSettingSeeder;
use Domain\ProjectName\Seeders\ProjectNameSeeder;
use Domain\Role\Seeders\PermissionSeeder;
use Domain\Role\Seeders\RoleSeeder;
use Domain\Skill\Seeders\SkillSeeder;
use Domain\Vacancy\Seeders\VacancySeeder;
use Domain\WorkshopName\Seeders\WorkshopNameSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(SkillSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(BannerSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(WorkshopNameSeeder::class);
        $this->call(ProjectNameSeeder::class);

        if(app()->environment('local')) {
            $this->call(BranchSeeder::class);
            $this->call(VacancySeeder::class);
            $this->call(PageSettingSeeder::class);
            $this->call(LeadSeeder::class);
        }
    }
}
