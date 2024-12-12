<?php

namespace Domain\Candidate\Helpers;

use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CandidateHelper
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws JsonException
     */
    public function data($candidate): array
    {
        $userDetails = $this->getUserDetails($candidate);

        return [
            'id' => $candidate?->id,
            'tab' => request()->has('tab') ? request()->get('tab') : 'personal-details',
            'email' => $candidate?->email,
            'via' => $candidate->login_via ?? 'email',
            'mobileNumber' => $candidate?->phone,
            'username' => $candidate?->username,
            'alternativeNumber' => $candidate?->alternative_number,
            'fullName' => $userDetails?->full_name,
            'dob' => $userDetails?->dob,
            'gender' => $userDetails ? $userDetails->gender : 'male',
            'maritalStatus' => $userDetails ? $userDetails->marital_status : 'single',
            'street' => $userDetails?->street,
            'city' => $userDetails?->city,
            'state' => $userDetails?->state,
            'postalCode' => $userDetails?->postal_code,
            'country' => [
                'value' => $userDetails?->country,
                'label' => getStringFromSlug($userDetails?->country),
            ],
            'type' => [
                'value' => 'candidate',
                'label' => 'Candidate',
            ],
            'getCompletionStatus' => $candidate?->getCompletionStatus(),

            'avatar' => $candidate?->avatar ? imageCheck('user-details/avatars/thumbnail/' . $candidate?->avatar) : null,
            'profilePreview' => $candidate?->avatar ? imageCheck('user-details/avatars/thumbnail/' . $candidate?->avatar) : null,

            'currentJobTitle' => $userDetails?->current_job_title,
            'currentCompany' => $userDetails?->current_company,
            'currentSalary' => $userDetails?->current_salary,
            'expectedSalary' => $userDetails?->expected_salary,
            'canRelocated' => $userDetails?->can_relocated ? 'yes' : 'no',
            'noOfExperience' => [
                'value' => $userDetails?->no_of_experiences,
                'label' => getStringFromSlug($userDetails?->no_of_experiences),
            ],
            'noticePeriod' => [
                'value' => $userDetails?->notice_period,
                'label' => getStringFromSlug($userDetails?->notice_period),
            ],

            'qualification' => [
                'value' => $userDetails?->qualification,
                'label' => getStringFromSlug($userDetails?->qualification),
            ],
            'specializedIn' => $userDetails?->specialized_in,
            'university' => $userDetails?->university,
            'yearOfGraduation' => $userDetails?->year_of_graduation,
            'hasAdditionalQualification' => $userDetails?->additional_qualification ? 'yes' : 'no',
            'additionalQualification' => $userDetails?->additional_qualification,

            'candidateExperiences' => $candidate?->workExperiences && collect($candidate?->workExperiences)->isNotEmpty() ? collect($candidate?->workExperiences)->map(function ($item, $index) {
                return [
                    'index' => $index,
                    'companyName' => $item->company,
                    'jobTitle' => $item->job_title,
                    'startDate' => $item->start_date,
                    'endDate' => $item->end_date,
                    'isCurrentEmployer' => $item->is_still_working ? 'yes' : 'no',
                    'responsibilities' => $item->responsibilities,
                    'achievements' => $item->achievements,
                    'otherExperiences' => $item->other_experiences,
                ];
            })->sortByDesc(function ($item) {
                $endDate = $item['endDate'] ?: '9999-12-31';
                return [$item['startDate'], $endDate];
            })->values()->toArray() : [[
                'index' => 0,
                'companyName' => null,
                'jobTitle' => null,
                'startDate' => null,
                'endDate' => null,
                'isCurrentEmployer' => 'no',
                'responsibilities' => null,
                'achievements' => null,
                'otherExperiences' => null,
            ]],

            'keySkills' => $candidate ? collect($candidate->skills)->map(function ($item, $index) {
                return [
                    'index' => $index,
                    'title' => $item->title,
                    'slug' => $item->slug,

                    'label' => $item->title,
                    'value' => $item->slug,
                ];
            })->sortBy(function ($item) {
                return $item['title'];
            })->values()->toArray() : [],
            'certifications' => $userDetails?->certifications,
            'knownLanguages' => $userDetails?->known_languages,

            'resume' => imageCheck('resumes/thumbnail/' . $userDetails?->resume),
            'portfolio' => imageCheck('user-details/portfolios/thumbnail/' . $userDetails?->portfolio),
            'portfolioLink' => imageCheck('user-details/portfolios/thumbnail/' . $userDetails?->portfolio),
            'coverLetter' => $userDetails?->cover_letter,
            'coverLetterType' => $userDetails?->cover_letter_type ?? 'text',
            'coverLetterFile' => imageCheck('user-details/cover-letters/thumbnail/' . $userDetails?->cover_letter_file),

            'preferredJobType' => $this->jobPreferences($candidate)['Type'] ?? null,
            'preferredJobIndustry' => $this->jobPreferences($candidate)['Industry'] ?? null,
            'preferredJobRole' => $this->jobPreferences($candidate)['Role'] ?? null,
            'preferredJobLocation' => $this->jobPreferences($candidate)['Location'] ?? null,
            'preferredJobEmploymentStatus' => $this->jobPreferences($candidate)['EmploymentStatus'] ?? null,

            'careerObjective' => $userDetails?->career_objective,
            'awardsAndRecognitions' => $userDetails?->awards_and_recognitions,
        ];
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