<?php

namespace Domain\Resume\ViewModels;

use App\Models\User;
use Domain\User\Resources\UserProfileResources;
use Domain\Vacancy\Models\Vacancy;
use Domain\Vacancy\Resources\VacancyResources;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use JsonException;
use Spatie\ViewModels\ViewModel;

class ResumeShowViewModel extends ViewModel
{
    public function __construct(
        public ?User $user = null,
        public ?Vacancy $vacancy = null,
    )
    {
    }

    public function resume(): array
    {
        return [
            'id' => $this->user->id,
            'role' => $this->user->roles[0]->display_name,
            'roleSlug' => $this->user->roles[0]->name,
            'username' => $this->user->username,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'alternativeNumber' => $this->user->alternative_number,
            'phone' => $this->user->phone,
            'via' => $this->user->login_via,
            'address' => $this->user->address,
            'avatar' => $this->avatar($this->user),
            'isActive' => $this->user->is_active,
            'userDetail' => $this->userDetail($this->user),
            'workExperiences' => $this->workExperiences($this->user)->get('workExperiences'),
            'appliedJobs' => $this->appliedJobs($this->user),
            'totalWorkExperiences' => $this->workExperiences($this->user)->get('totalExperience'),

            'keySkills' => $this->user->skills ? collect($this->user->skills)->map(function ($item, $index) {
                return [
                    'index' => $index,
                    'title' => $item->title,
                    'slug' => $item->slug,
                ];
            })->sortBy(function ($item) {
                return $item['title'];
            })->values()->toArray() : [],
        ];
    }

    public function vacancy(): array
    {
        return [
            'id' => $this->vacancy?->id,
            'username' => $this->vacancy?->title,
        ];
    }

    private function userDetail($user): array
    {
        return [
            'id' => $user->userDetail->id,
            'fullName' => $user->userDetail->full_name ?? $user->userDetail->full_name,
            'gender' => $user->userDetail->gender,
            'address' => $user->userDetail->address,
            'street' => $user->userDetail->street,
            'city' => $user->userDetail->city,
            'state' => $user->userDetail->state,
            'postalCode' => $user->userDetail->postal_code,
            'country' => $user->userDetail->country,
            'age' => $user->userDetail->age,
            'dob' => $user->userDetail->dob,
            'noOfExperiences' => $user->userDetail->no_of_experiences,
            'qualification' => getStringFromSlug($user->userDetail->qualification),
            'maritalStatus' => $user->userDetail->marital_status,
            'resume' => $user->userDetail->resume ? imageCheck('resumes/thumbnail/' . $user->userDetail->resume) : null,
            'experience' => $user->userDetail->experience,

            'currentJobTitle' => $user->userDetail->current_job_title,
            'currentCompany' => $user->userDetail->current_company,
            'currentSalary' => $user->userDetail->current_salary,
            'expectedSalary' => $user->userDetail->expected_salary,
            'noticePeriod' => getStringFromSlug($user->userDetail->notice_period),
            'canRelocated' => $user->userDetail->can_relocated,

            'preferredJobType' => $this->jobPreferences($user)['Type'] ?? null,
            'preferredJobIndustry' => $this->jobPreferences($user)['Industry'] ?? null,
            'preferredJobRole' => $this->jobPreferences($user)['Role'] ?? null,
            'preferredJobLocation' => $this->jobPreferences($user)['Location'] ?? null,
            'preferredJobEmploymentStatus' => $this->jobPreferences($user)['EmploymentStatus'] ?? null,

            'specializedIn' => $user->userDetail->specialized_in,
            'university' => $user->userDetail->university,
            'yearOfGraduation' => $user->userDetail->year_of_graduation,
            'additionalQualification' => $user->userDetail->additional_qualification,

            'user_id' => $user->userDetail->user_id,
        ];
    }

    private function appliedJobs($user): array|Collection
    {
        return $user->appliedJobs()
            ->when(auth()->user()->hasRole('company'), function ($builder) {
                return $builder->whereHas('vacancy', function($query) {
                    $query->where('company_id', auth()->user()->id);
                });
            })
            ->get()
            ->map(function ($job) {
                return VacancyResources::make($job->vacancy);
            });
    }

    private function avatar($user): ?string
    {
        if($user->avatar) {
            return imageCheck('user-details/avatars/thumbnail/' . $user->avatar);
        }

        return $user->profile_photo_path ? imageCheck($user->profile_photo_path) : "https://ui-avatars.com/api/?name=". $user->name . "&color=7F9CF5&background=87e8ff";
    }

    private function workExperiences($user): Collection
    {
        $totalMonths = 0;

        $workExperiences = $user->workExperiences ? collect($user->workExperiences)->map(function ($workExperience) use (&$totalMonths) {
            $startDate = Carbon::parse($workExperience->start_date);
            $endDate = $workExperience->is_still_working ? now() : Carbon::parse($workExperience->end_date);

            $monthsDiff = $startDate->diffInMonths($endDate);

            $totalMonths += $monthsDiff;

            return [
                'company' => $workExperience->company,
                'jobTitle' => $workExperience->job_title,
                'startDate' => $workExperience->start_date,
                'endDate' => $workExperience->is_still_working ? null : $workExperience->end_date,
                'isStillWorking' => $workExperience->is_still_working,
                'responsibilities' => $workExperience->responsibilities,
                'achievements' => $workExperience->achievements,
                'otherExperiences' => $workExperience->other_experiences,
                'candidate_id' => $workExperience->candidate_id,
                'experienceInMonths' => $monthsDiff,
            ];
        }) : collect([]);

        $totalYears = floor($totalMonths / 12);
        $remainingMonths = $totalMonths % 12;

        return collect([
            'workExperiences' => $workExperiences,
            'totalExperience' => [
                'years' => $totalYears,
                'months' => $remainingMonths,
            ],
        ]);
    }

    private function getUserDetails($user)
    {
        return $user ? $user->userDetail : null;
    }

    /**
     * @throws JsonException
     */
    protected function jobPreferences($user): ?array
    {
        $jobPreferences = $this->getUserDetails($user)?->job_preferences;

        if($jobPreferences) {
            return json_decode($jobPreferences, true, 512, JSON_THROW_ON_ERROR);
        }

        return null;
    }
}
