<?php

namespace Domain\Vacancy\Requests;

use Domain\Category\Rules\CategoryExists;
use Domain\Global\Rules\CheckIfAdditionalQuestionsArrayContainAtLeastOneQuestion;
use Domain\Global\Rules\DateRule;
use Domain\Global\Rules\FutureDateOnlyRule;
use Domain\Global\Rules\MultipleDropdownValidate;
use Domain\Global\Rules\TextOnly;
use Domain\Global\Traits\Validation;
use Domain\Vacancy\Data\VacancyData;
use Domain\Vacancy\Rules\VacancyExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VacancyRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->request->get('id');

        return [
            'company' => [
                'required',
                new MultipleDropdownValidate(),
            ],
            'title' => [
                'required',
                'max:100',
            ],
            'yearsOfExperiences' => [
                'required',
            ],
            'salary' => [
                'required',
            ],
            'salaryFrequency' => [
                'required',
                Rule::in(['per-annum', 'per-month'])
            ],
            'parent' => [
                'required',
                'array',
                new MultipleDropdownValidate(existsRule: new CategoryExists()),
            ],
            'child' => [
                'required',
                'array',
                new MultipleDropdownValidate(existsRule: new CategoryExists()),
            ],
            'description' => [
                'required',
            ],
            'qualifications' => [
                'required',
                'array',
            ],
            'benefits' => [
                'required',
                'array',
            ],
            'workModes' => [
                'required',
                'array',
            ],
            'noOfOpenings' => [
                'required',
                'numeric',
            ],
            'locations' => [
                'required',
                'array',
            ],
            'expireDate' => [
                'required',
                new DateRule()
            ],
            'keySkills' => [
                'required',
                'array',
            ],
            'hasAdditionalQuestions' => [
                'required',
                Rule::in(['yes', 'no'])
            ],
            'additionalQuestions' => [
                'required_if:hasAdditionalQuestions,yes',
                new CheckIfAdditionalQuestionsArrayContainAtLeastOneQuestion(),
            ],
            'applicationMethod' => [
                'required',
                Rule::in(['internal', 'external'])
            ],
            'externalLink' => [
                'required_if:applicationMethod,external',
            ],
            'isWalkInInterview' => [
                'required',
                Rule::in(['yes', 'no'])
            ],
            'startDate' => [
                'required_if:isWalkInInterview,yes',
                new FutureDateOnlyRule()
            ],
            'endDate' => [
                'required_if:isWalkInInterview,yes',
                new FutureDateOnlyRule()
            ],
        ];
    }

    public function data(): VacancyData
    {
        return new VacancyData(
            $this->input('id'),
            $this->input('title'),
            $this->input('company')['value'],
            $this->input('salary'),
            $this->input('yearsOfExperiences'),
            $this->input('parent'),
            $this->input('child'),
            $this->input('description'),
            $this->input('qualifications'),
            $this->input('benefits'),
            $this->input('workModes'),
            $this->input('noOfOpenings'),
            $this->input('locations'),
            $this->input('expireDate'),
            $this->input('keySkills'),
            $this->input('hasAdditionalQuestions') === 'yes',
            $this->input('additionalQuestions'),
            $this->input('applicationMethod') === 'internal',
            $this->input('externalLink'),
            $this->input('salaryFrequency'),
            $this->input('isWalkInInterview') === 'yes',
            $this->input('startDate'),
            $this->input('endDate'),
        );
    }

    public function validations(): array
    {
        return [
            'company' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The company field is required'
                ],
            ],
            'title' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The title field is required'
                ],
                [
                    'type' => 'max',
                    'limit' => 100,
                    'message' => 'Title cannot exceed 100 characters'
                ],
            ],
            'salary' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The salary field is required'
                ],
            ],
            'salaryFrequency' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The salary type field is required'
                ],
            ],
            'yearsOfExperiences' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The years of experience field is required'
                ],
            ],
            'parent' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The parent field is required'
                ],
                [
                    'type' => 'array',
                    'limit' => null,
                    'message' => 'The parent field must be an array'
                ],
            ],
            'child' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The child field is required'
                ],
                [
                    'type' => 'array',
                    'limit' => null,
                    'message' => 'The child field must be an array'
                ],
            ],
            'description' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The description field is required'
                ],
            ],
            'qualifications' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The qualifications field is required'
                ],
                [
                    'type' => 'array',
                    'limit' => null,
                    'message' => 'The qualifications field must be an array'
                ],
            ],
            'benefits' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The benefits field is required'
                ],
                [
                    'type' => 'array',
                    'limit' => null,
                    'message' => 'The benefits field must be an array'
                ],
            ],
            'workModes' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The work modes field is required'
                ],
                [
                    'type' => 'array',
                    'limit' => null,
                    'message' => 'The work modes field must be an array'
                ],
            ],
            'noOfOpenings' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The number of openings field is required'
                ],
                [
                    'type' => 'numeric',
                    'limit' => null,
                    'message' => 'The number of openings must be a numeric value'
                ],
            ],
            'locations' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The locations field is required'
                ],
                [
                    'type' => 'array',
                    'limit' => null,
                    'message' => 'The locations field must be an array'
                ],
            ],
            'expireDate' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The last date field is required'
                ],
                [
                    'type' => 'dateRule',
                    'limit' => null,
                    'message' => 'Please enter a valid last date',
                ],
            ],
            'keySkills' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The key skills field is required'
                ],
                [
                    'type' => 'array',
                    'limit' => null,
                    'message' => 'The key skills field must be an array'
                ],
            ],
            'applicationMethod' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The application method field is required'
                ],
            ],
            'externalLink' => [
                [
                    'type' => 'required_if',
                    'limit' => 'applicationMethod,external',
                    'message' => 'The external link field is required when application method is set to external portal'
                ],
            ],
            'isWalkInInterview' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The walk in interview method field is required'
                ],
            ],
            'startDate' => [
                [
                    'type' => 'required_if',
                    'limit' => 'isWalkInInterview,yes',
                    'message' => 'The start date method field is required'
                ],
                [
                    'type' => 'dateRule',
                    'limit' => null,
                    'message' => 'Please enter a valid date'
                ],
                [
                    'type' => 'FutureDateOnlyRule',
                    'limit' => null,
                    'message' => 'The date must be today or a future date within 1 year'
                ],
            ],
            'endDate' => [
                [
                    'type' => 'required_if',
                    'limit' => 'isWalkInInterview,yes',
                    'message' => 'The end date method field is required'
                ],
                [
                    'type' => 'dateRule',
                    'limit' => null,
                    'message' => 'Please enter a valid date'
                ],
                [
                    'type' => 'FutureDateOnlyRule',
                    'limit' => null,
                    'message' => 'The date must be today or a future date within 1 year'
                ],
            ],
        ];
    }
}
