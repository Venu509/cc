<?php

namespace Domain\Branch\Seeders;

use App\Models\User;
use Domain\Branch\Models\Branch;
use Domain\User\Models\UserDetails;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
//        $branches = [
//            [
//                'name' => 'Branch no 1',
//                'description' => 'This is branch no 1',
//            ],
//        ];
//
//        collect($branches)->each(function ($branch) {
//            Branch::factory()->create([
//                'name' => $branch['name'],
//                'description' => slugGenerator($branch['name']),
//            ]);
//        });

        $marketingUser = User::query()->whereHas('roles', function (Builder $builder) {
            $builder->where('name', 'marketing');
        })->first()->id;

        Branch::factory(10)->create([
            'added_by' => $marketingUser
        ]);

        $users = User::query()->whereHas('roles', function (Builder $builder) {
            $builder->whereIn('name', ['government', 'institution']);
        })->get();

        $users->each(function ($user) use ($marketingUser) {
            $userDetail = UserDetails::where('user_id', $user->id)->first();

            $user->added_by = $marketingUser;
            $user->save();

            if ($userDetail) {
                $branchId = Branch::query()
                    ->where('added_by', $marketingUser)
                    ->inRandomOrder()
                    ->value('id');

                if ($branchId) {
                    $userDetail->branch_id = $branchId;
                    $userDetail->save();
                }
            }
        });
    }
}
