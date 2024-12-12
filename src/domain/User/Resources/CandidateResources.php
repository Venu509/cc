<?php

namespace Domain\User\Resources;

use Domain\Global\Helpers\EmployeeHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Helper\Helper;

class CandidateResources extends JsonResource
{
    use Helper;

    public ?array $requiredSkills = [];
    public ?array $userSkills = [];
    public ?string $vacancyId = null;
    public ?float $matchedPercentage = 0;

    public function __construct(
        $resource,
        ?array $requiredSkills = [],
        ?array $userSkills = [],
        ?string $vacancyId = null,
        ?float $matchedPercentage = 0
    )
    {
        $this->requiredSkills = $requiredSkills;
        $this->userSkills = $userSkills;
        $this->vacancyId = $vacancyId;
        $this->matchedPercentage = $matchedPercentage;

        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        $employeeHelper = new EmployeeHelper();

        $userVacancy = $this->appliedJobs()
            ->where('vacancy_id', $this->vacancyId)
            ->first();

        $vacancyQuestions = $this->vacancyId ? findVacancyById($this->vacancyId)->questions : [];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'alternativeNumber' => $this->alternative_number,
            'phone' => $this->phone,
            'username' => $this->username,
            'loginVia' => $this->login_via,
            'address' => $this->address,
            'avatar' => $employeeHelper->avatar($this),
            'userDetail' => $this->userDetail($this),
            'workExperiences' => $employeeHelper->workExperiences($this)->get('workExperiences'),
            'totalWorkExperiences' => $employeeHelper->workExperiences($this)->get('totalExperience'),

            'keySkills' => $this->skills ? collect($this->skills)->map(function ($item, $index) {
                return [
                    'index' => $index,
                    'title' => $item->title,
                    'slug' => $item->slug,
                ];
            })->sortBy(function ($item) {
                return $item['title'];
            })->values()->toArray() : [],

            'appliedJobs' => $employeeHelper->appliedVacancies($this),
            'matchedPercentage' => $this->matchedPercentage,
            'vacancyStatus' => $userVacancy ? $userVacancy->status : 'applied',
            'applicantTracking' => $userVacancy ? collect($userVacancy->history)->map(function ($history) {
                $accessedBy = findUserById($history['accessedByID']);
                return [
                    'status' => $history['status'],
                    'remarks' => $history['remarks'],
                    'color' => $this->applicantStatusColor($history['status']),
                    'date' => formattedDateTime($history['timestamp'], 'Y-m-d'),
                    'time' => formattedDateTime($history['timestamp'], 'H:i A'),
                    'accessedBy' => [
                        'id' => $accessedBy->id,
                        'name' => $accessedBy->name,
                        'avatar' => (new EmployeeHelper())->avatar($accessedBy),
                    ],
                ];
            })->sortBy(function ($item) {
                return $item['time'];
            })->groupBy(function ($item) {
                return $item['date'];
            }) : [],

            'questions' => $vacancyQuestions ? collect($vacancyQuestions)->map(function ($question, $index) {
                $expectedAnswers = json_decode($question->answers, true, 512, JSON_THROW_ON_ERROR);

                $candidateId = $this->id;
                $givenAnswer = $question->answer($candidateId)->first() ? $question->answer($candidateId)->first()->answer : null;

                $matchingState = 'low';
                $color = 'text-red-500';
                $matchingStateLabel = 'Not Matched';

                if ($givenAnswer) {
                    if (in_array($givenAnswer, $expectedAnswers, true)) {
                        $matchingState = 'high';
                        $color = 'text-green-500';
                        $matchingStateLabel = 'Matched';
                    } elseif (collect($expectedAnswers)->contains(function ($expected) use ($givenAnswer) {
                        return str_contains(strtolower($givenAnswer), strtolower($expected)) || str_contains(strtolower($expected), strtolower($givenAnswer));
                    })) {
                        $matchingState = 'medium';
                        $color = 'text-yellow-500';
                        $matchingStateLabel = 'Average';
                    }
                }

                return [
                    'index' => $index,
                    'question' => $question->question,
                    'expectedAnswers' => $expectedAnswers,
                    'givenAnswer' => $givenAnswer,
                    'matchingState' => $matchingState,
                    'matchingStateLabel' => $matchingStateLabel,
                    'color' => $color,
                ];
            })->sortBy(function ($item) {
                return [
                    'high' => 1,
                    'medium' => 2,
                    'low' => 3
                ][$item['matchingState']];
            })->values()->toArray() : [],

            'vacancyStatusColor' => $userVacancy ? $this->applicantStatusColor($userVacancy->status) : 'green',
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
            'qualification' => getStringFromSlug($user->userDetail->qualification),
            'resume' => $user->userDetail->resume ? imageCheck('resumes/thumbnail/' . $user->userDetail->resume) : null,
            'experience' => $user->userDetail->experience,
            'noOfExperiences' => addHyphenBetweenNumbers(getStringFromSlug($user->userDetail->no_of_experiences)),

            'currentJobTitle' => $user->userDetail->current_job_title,
            'currentCompany' => $user->userDetail->current_company,
            'currentSalary' => $user->userDetail->current_salary,
            'expectedSalary' => $user->userDetail->expected_salary,
            'noticePeriod' => $user->userDetail->notice_period === 'more' ? '90+ days' : getStringFromSlug($user->userDetail->notice_period),
            'canRelocated' => $user->userDetail->can_relocated,

            'specializedIn' => $user->userDetail->specialized_in,
            'university' => $user->userDetail->university,
            'yearOfGraduation' => $user->userDetail->year_of_graduation,
            'additionalQualification' => $user->userDetail->additional_qualification,
        ];
    }
}
