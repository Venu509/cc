<?php

namespace Database\Seeders;

use App\Models\User;
use Domain\Candidate\Models\SkillUser;
use Domain\Skill\Models\Skill;
use Domain\User\Models\UserDetails;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = collect(roleAbilities());

        foreach ($roles as $roleSlug => $roleConfig) {
            $data = [
                'role' => $roleSlug,
                'email' => 'careerconnect@' . $roleSlug . '.com',
                'name' => ucfirst($roleSlug),
            ];
            $user = $this->createNewUser($data);

            $abilities = [];
            foreach ($roleConfig['domains'] as $domain => $permissions) {
                if (is_array($permissions)) {
                    foreach ($permissions as $permission) {
                        $abilities[] = $permission . '-' . $domain;
                    }
                }
            }

            $user->givePermissionTo($abilities);
        }

        $externalUsers = [
            [
                'role' => 'marketing',
                'phone' => '9110580709',
                'email' => 'baluv989@gmail.com',
                'name' => 'Valasamgari Balaswami',
            ],
            [
                'role' => 'marketing',
                'phone' => '8328459942',
                'email' => 'muraliatme542@gmail.com',
                'name' => 'Murali',
            ],
        ];

        collect($externalUsers)->each(function ($externalUser) {
            $user = $this->createNewUser($externalUser);

            $user->syncRoles($externalUser['role']);

            $permissions = Role::query()->where('name', $externalUser['role'])->with('permissions')->get()->pluck('permissions')->flatten()->pluck('name')->toArray();

            $user->syncPermissions($permissions);
        });

        if(app()->environment('local')) {
            $this->extraCandidatesAndCompanies();
        }

        $this->rusiruCandidate();
    }

    protected function createNewUser(array $data): User
    {
        $user = User::factory()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'is_active' => true,
            'is_profile_completed' => true,
            'code' => 1010,
        ]);

        $user->assignRole($data['role']);

        UserDetails::factory()->create([
            'user_id' => $user->id,
            'full_name' => $user->name,
            'company_name' => $user->name,
            'company_mobile_number' => $user->phone ?? 'N/A', // Ensure phone is not null
            'company_email' => $user->email,
            'contact_person' => $user->name,
            'contact_person_email' => $user->email,
            'contact_person_phone' => $user->phone ?? 'N/A', // Ensure phone is not null
        ]);

        return $user;
    }

    /**
     * @return void
     */
    protected function rusiruCandidate(): void
    {
        $rusiru = [
            'role' => 'candidate',
            'phone' => '0786794814',
            'email' => 'rusiruofficial@gmail.com',
            'name' => 'Rusiru Kulathunga',
        ];

        $rusiruCandidate = $this->createNewUser($rusiru);

        $rusiruCandidate->syncRoles($rusiru['role']);

        $permissions = Role::query()->where('name', $rusiru['role'])->with('permissions')->get()->pluck('permissions')->flatten()->pluck('name')->toArray();

        $rusiruCandidate->syncPermissions($permissions);

        $skillsToCheck = ['Laravel', 'PHP', 'Node Js', 'GitHub', 'CI/CD', 'Team', 'Vue Js', 'Java Script'];

        Skill::query()
            ->where(function ($query) use ($skillsToCheck) {
                foreach ($skillsToCheck as $title) {
                    $query->orWhere('title', 'LIKE', "%{$title}%");
                }
            })
            ->get()
            ->each(function ($skill) use ($rusiruCandidate) {
                $skillUser = new SkillUser();

                $skillUser->forceFill([
                    'skill_id' => $skill->id,
                    'user_id' => $rusiruCandidate->id,
                ]);

                $skillUser->save();
                $skillUser->refresh();
            });
    }

    /**
     * @return void
     */
    protected function extraCandidatesAndCompanies(): void
    {
        $companies = [
            [
                'role' => 'company',
                'phone' => '13472145678',
                'email' => 'john.doe123@gmail.com',
                'name' => 'John Doe',
            ],
            [
                'role' => 'company',
                'phone' => '15123987654',
                'email' => 'jane.smith456@gmail.com',
                'name' => 'Jane Smith',
            ],
            [
                'role' => 'company',
                'phone' => '16274890123',
                'email' => 'alex.brown789@gmail.com',
                'name' => 'Alex Brown',
            ],
            [
                'role' => 'company',
                'phone' => '19873654678',
                'email' => 'chris.jones321@gmail.com',
                'name' => 'Chris Jones',
            ],
            [
                'role' => 'company',
                'phone' => '17123986456',
                'email' => 'patricia.wilson654@gmail.com',
                'name' => 'Patricia Wilson',
            ],
            [
                'role' => 'company',
                'phone' => '18372912345',
                'email' => 'michael.taylor987@gmail.com',
                'name' => 'Michael Taylor',
            ],
            [
                'role' => 'company',
                'phone' => '12123456789',
                'email' => 'emily.miller432@gmail.com',
                'name' => 'Emily Miller',
            ],
            [
                'role' => 'company',
                'phone' => '14785678901',
                'email' => 'sarah.davis098@gmail.com',
                'name' => 'Sarah Davis',
            ],
            [
                'role' => 'company',
                'phone' => '16785901234',
                'email' => 'daniel.martin654@gmail.com',
                'name' => 'Daniel Martin',
            ],
            [
                'role' => 'company',
                'phone' => '19012345678',
                'email' => 'linda.lee123@gmail.com',
                'name' => 'Linda Lee',
            ],
        ];

        collect($companies)->each(function ($company) {
            $user = $this->createNewUser($company);

            $user->syncRoles($company['role']);

            $permissions = Role::query()->where('name', $company['role'])->with('permissions')->get()->pluck('permissions')->flatten()->pluck('name')->toArray();

            $user->syncPermissions($permissions);
        });

        $candidates = [
            [
                'role' => 'candidate',
                'phone' => '15551234567',
                'email' => 'david.johnson123@gmail.com',
                'name' => 'David Johnson',
            ],
            [
                'role' => 'candidate',
                'phone' => '13142357689',
                'email' => 'lisa.anderson456@gmail.com',
                'name' => 'Lisa Anderson',
            ],
            [
                'role' => 'candidate',
                'phone' => '16789234567',
                'email' => 'james.moore789@gmail.com',
                'name' => 'James Moore',
            ],
            [
                'role' => 'candidate',
                'phone' => '14876543210',
                'email' => 'amanda.jackson654@gmail.com',
                'name' => 'Amanda Jackson',
            ],
            [
                'role' => 'candidate',
                'phone' => '17785673456',
                'email' => 'robert.thomas098@gmail.com',
                'name' => 'Robert Thomas',
            ],
            [
                'role' => 'candidate',
                'phone' => '12142350987',
                'email' => 'maria.white123@gmail.com',
                'name' => 'Maria White',
            ],
            [
                'role' => 'candidate',
                'phone' => '18239874560',
                'email' => 'kevin.harris456@gmail.com',
                'name' => 'Kevin Harris',
            ],
            [
                'role' => 'candidate',
                'phone' => '19345678901',
                'email' => 'elizabeth.young321@gmail.com',
                'name' => 'Elizabeth Young',
            ],
            [
                'role' => 'candidate',
                'phone' => '12019876543',
                'email' => 'thomas.king654@gmail.com',
                'name' => 'Thomas King',
            ],
            [
                'role' => 'candidate',
                'phone' => '15478901234',
                'email' => 'nancy.green123@gmail.com',
                'name' => 'Nancy Green',
            ]
        ];

        collect($candidates)->each(function ($candidate) {
            $user = $this->createNewUser($candidate);

            $user->syncRoles($candidate['role']);

            $permissions = Role::query()->where('name', $candidate['role'])->with('permissions')->get()->pluck('permissions')->flatten()->pluck('name')->toArray();

            $user->syncPermissions($permissions);

            Skill::query()
                ->inRandomOrder()
                ->take(5)
                ->get()
                ->each(function ($skill) use ($user) {

                    $skillUser = new SkillUser();

                    $skillUser->forceFill([
                        'skill_id' => $skill->id,
                        'user_id' => $user->id,
                    ]);

                    $skillUser->save();
                    $skillUser->refresh();
                });
        });
    }
}
