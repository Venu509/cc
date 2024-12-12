<?php

namespace Domain\Global\Requests;

use Domain\API\Authentication\Rules\UsernameValidation;
use Domain\Global\Data\EmployeeData;
use Domain\Global\Rules\CheckIfExperiencesArrayContainAtLeastOneExperience;
use Domain\Global\Rules\DateRule;
use Domain\Global\Rules\EmailValidationRule;
use Domain\Global\Rules\MobileNumberRule;
use Domain\Global\Rules\MultipleDropdownValidate;
use Domain\Global\Rules\PastDateOnlyRule;
use Domain\Global\Rules\TextOnly;
use Domain\Global\Traits\Validation;
use Domain\User\Rules\UserExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'type' => [
                'required',
                'array',
                new MultipleDropdownValidate(['government', 'institution', 'marketing', 'company', 'candidate']),
            ],
        ];

        $id = $this->input('id');
        $selectedRole = $this->input('type')['value'];

        if ($id) {
            $rules['id'] = [
                'required',
                new UserExists()
            ];
        }

        if (
            $selectedRole === 'government' || $selectedRole === 'institution' || $selectedRole === 'company' ||
            ($selectedRole === 'candidate' && $this->input('tab') === 'personal-details')
        ) {
            $rules['username'] = [
                'required',
                new UsernameValidation($this->input('via'), $id),
            ];
        }

        if ($selectedRole === 'government' || $selectedRole === 'institution') {
            return $this->collegeRules($id, $rules);
        }

        if ($selectedRole === 'candidate') {
            $common = [
                'tab' => [
                    'required',
                    Rule::in([
                        'personal-details',
                        'professional-information',
                        'educational-background',
                        'work-experiences',
                        'skill-and-certificates',
                        'resume-and-portfolio',
                        'job-preferences',
                        'additional-information',
                        'avatar',
                    ])
                ]
            ];

            return $this->candidateRules($id, array_merge($rules, $common));
        }

        if ($selectedRole === 'company') {
            return $this->companyRules($id, array_merge($rules), $selectedRole);
        }

        return $rules;
    }

    private function collegeRules($id, $rules): array
    {
        if (!$id) {
            $rules['registrationDoc'] = [
                'required',
                'file',
                'mimes:pdf,docx,doc',
                'max:5120',
            ];
        }

        return array_merge($rules, [
            'name' => [
                'required',
                'max:100'
            ],
            'address' => [
                'required',
                'max:200'
            ],
            'mobileNumber' => [
                'required',
                new MobileNumberRule(),
            ],
            'email' => [
                'required',
                new EmailValidationRule(),
            ],
            'yearsOfExistence' => [
                'required',
                'numeric'
            ],
            'dateOfRegister' => [
                'required',
                new DateRule(),
                new PastDateOnlyRule(),
            ],
            'contactPerson' => [
                'required',
                'max:50',
                new TextOnly()
            ],
            'contactPersonEmail' => [
                'required',
                new EmailValidationRule()
            ],
            'contactPersonPhone' => [
                'required',
                new MobileNumberRule()
            ],
            'contactPersonAddress' => 'required',
        ]);
    }

    private function companyRules($id, $rules, $selectedRole): array
    {
        if ($this->input('tab') === 'personal-details') {
            $newRules = [
                'companyName' => [
                    'required',
                    'max:20',
                ],
                'mobileNumber' => [
                    'required',
                    new MobileNumberRule(),
                ],
                'email' => [
                    'required',
                    'email',
                    new EmailValidationRule(),
                ],
                'contactPerson' => [
                    'required',
                    'max:50',
                    new TextOnly()
                ],
                'contactPersonEmail' => [
                    'required',
                    'email',
                    new EmailValidationRule()
                ],
                'contactPersonPhone' => [
                    'required',
                    new MobileNumberRule()
                ],
                'contactPersonAddress' => [
                    'required',
                    'max:200'
                ],
                'address' => [
                    'required',
                    'max:200'
                ],
                'gst' => [
                    'nullable',
                    'max:15'
                ],
            ];

            if (($selectedRole === 'company') && !$id) {
                $newRules['registrationDoc'] = [
                    'required',
                    'file',
                    'mimes:pdf,docx,doc',
                    'max:5120'
                ];
            }
        }

        if ($this->input('tab') === 'avatar') {
            $newRules['avatar'] = [
                'required',
                'file',
                'mimes:png,jpg,jpeg',
                'max:2048',
            ];
        }

        return array_merge($rules, $newRules);
    }

    private function candidateRules($id, $rules): array
    {
        $newRules = [];

        if ($this->input('tab') === 'personal-details') {
            $newRules = [
                'fullName' => [
                    'required',
                    new TextOnly(),
                    'max:40'
                ],
                'dob' => [
                    'required',
                    new DateRule(),
                    new PastDateOnlyRule(),
                ],
                'gender' => [
                    'required',
                    Rule::in(['male', 'female', 'other'])
                ],
                'maritalStatus' => [
                    'required',
                    Rule::in(['single', 'married', 'divorced', 'widowed'])
                ],
                'mobileNumber' => [
                    'required',
                    new MobileNumberRule(),
                ],
                'email' => [
                    'required',
                    'email',
                    new EmailValidationRule(),
                ],
                'alternativeNumber' => [
                    'nullable',
                    new MobileNumberRule()
                ],
                'street' => [
                    'required',
                    'max:40',
                ],
                'city' => [
                    'required',
                    'max:40',
                    new TextOnly()
                ],
                'state' => [
                    'required',
                    'max:40',
                    new TextOnly()
                ],
                'postalCode' => 'required',
                'country' => [
                    'required',
                    'array',
                    new MultipleDropdownValidate(),
                ],
            ];

            if ((auth()->user()->roles()->first()->name === 'candidate') && !$id) {
                $newRules['avatar'] = [
                    'required',
                    'file',
                    'mimes:png,jpg,jpeg',
                    'max:2048',
                ];
                $newRules['resume'] = [
                    'required',
                    'file',
                    'mimes:pdf,doc,docx',
                    'max:5120'
                ];
            }
        }

        if ($this->input('tab') === 'avatar') {
            $newRules['avatar'] = [
                'required',
                'file',
                'mimes:png,jpg,jpeg',
                'max:2048',
            ];
        }

        if ($this->input('tab') === 'professional-information') {
            $newRules = [
                'currentJobTitle' => [
                    'required',
                    'max:40',
                    new TextOnly()
                ],
                'currentCompany' => [
                    'required',
                    'max:20',
                    new TextOnly()
                ],
                'noOfExperience' => [
                    'required',
                    'array',
                    new MultipleDropdownValidate(['less-than-1-year', '1-3-years', '3-5-years', '5-years', '10-years', '10+-years']),
                ],
                'currentSalary' => [
                    'required',
                    'string',
                    'max:10',
                ],
                'expectedSalary' => [
                    'required',
                    'string',
                    'max:10',
                ],
                'noticePeriod' => [
                    'required',
                    'array',
                    new MultipleDropdownValidate(['immediate', '15-days', '30-days', '60-days', '90-days', 'more']),
                ],
                'canRelocated' => [
                    'required',
                    Rule::in('yes', 'no')
                ],
            ];
        }

        if ($this->input('tab') === 'educational-background') {
            $newRules = [
                'specializedIn' => [
                    'required',
                    'max:20'
                ],
                'qualification' => [
                    'required',
                    'array',
                    new MultipleDropdownValidate(['high-school', 'diploma', 'bachelors-degree', 'masters-degree']),
                ],
                'university' => [
                    'required',
                    'max:20'
                ],
                'yearOfGraduation' => [
                    'required',
                    'digits:4',
                    'integer',
                ],
                'additionalQualification' => [
                    'required_if:hasAdditionalQualification,yes',
                    'max:100'
                ],
            ];
        }

        if ($this->input('tab') === 'work-experiences') {
            $newRules = [
                'candidateExperiences' => [
                    'required',
                    'array',
                    new CheckIfExperiencesArrayContainAtLeastOneExperience(),
                ],
                'candidateExperiences.*.companyName' => [
                    'required',
                    'string',
                    'max:20'
                ],
                'candidateExperiences.*.jobTitle' => [
                    'required',
                    'string',
                    'max:60'
                ],
                'candidateExperiences.*.startDate' => [
                    'required',
                    'date',
                    'before_or_equal:candidateExperiences.*.endDate'
                ],
                'candidateExperiences.*.endDate' => [
                    'nullable',
                    'date',
                    'after_or_equal:candidateExperiences.*.startDate'
                ],
                'candidateExperiences.*.responsibilities' => [
                    'required',
                    'string',
                    'max:200',
                ],
                'candidateExperiences.*.achievements' => [
                    'nullable',
                    'string',
                    'max:200',
                ],
            ];
        }

        if ($this->input('tab') === 'skill-and-certificates') {
            $newRules = [
                'keySkills' => [
                    'required',
                    'array',
                ],
                'certifications' => [
                    'nullable',
                    'max:200',
                ],
                'knownLanguages' => [
                    'nullable',
                    'max:200',
                ],
            ];
        }

        if ($this->input('tab') === 'resume-and-portfolio') {
            $newRules = [
                'coverLetter' => [
                    'nullable',
                    'string',
                    'max:200',
                ],
                'coverLetterType' => [
                    'required',
                    Rule::in(['file', 'text'])
                ],
                'coverLetterFile' => [
                    'nullable',
                ],
            ];

//            if ($this->requestTypeCheck()) {
//                $newRules['resume'] = [
//                    'required',
//                    'file',
//                    'mimes:pdf,doc,docx',
//                    'max:5120'
//                ];
//            }
        }

        if ($this->input('tab') === 'job-preferences') {
            $newRules = [
                'preferredJobType' => [
                    'required',
                    'array',
                ],
                'preferredJobIndustry' => [
                    'required',
                    'array',
                ],
                'preferredJobEmploymentStatus' => [
                    'required',
                    'array',
                ],
                'preferredJobRole' => [
                    'required',
                    'string',
                    'max:20'
                ],
                'preferredJobLocation' => [
                    'required',
                    'array',
                ],
            ];
        }

        if ($this->input('tab') === 'additional-information') {
            $newRules = [
                'careerObjective' => [
                    'nullable',
                    'string',
                    'max:200'
                ],
                'awardsAndRecognitions' => [
                    'nullable',
                    'string',
                    'max:200'
                ],
            ];
        }

        return array_merge($rules, $newRules);
    }

    public function data(): EmployeeData
    {
        return new EmployeeData(
            $this->input('id'),
            $this->input('name') ?? $this->input('username'),
            $this->input('username'),
            $this->input('address'),
            $this->input('mobileNumber'),
            $this->input('email'),
            $this->input('yearsOfExistence'),
            $this->input('dateOfRegister'),
            $this->input('contactPerson'),
            $this->input('contactPersonEmail'),
            $this->input('contactPersonPhone'),
            $this->input('contactPersonAddress'),
            $this->input('gst'),
            $this->input('type'),
            $this->file('registrationDoc'),
            $this->file('avatar'),

            $this->input('fullName'),
            $this->input('dob'),
            $this->input('gender'),
            $this->input('maritalStatus'),
            $this->input('alternativeNumber'),
            $this->input('street'),
            $this->input('city'),
            $this->input('state'),
            $this->input('postalCode'),
            $this->input('country'),

            $this->input('currentJobTitle'),
            $this->input('currentCompany'),
            $this->input('noOfExperience'),
            $this->input('currentSalary'),
            $this->input('expectedSalary'),
            $this->input('noticePeriod'),
            $this->input('canRelocated') === 'yes',

            $this->input('qualification'),
            $this->input('specializedIn'),
            $this->input('university'),
            $this->input('yearOfGraduation'),
            $this->input('additionalQualification'),

            $this->input('candidateExperiences'),

            $this->input('keySkills'),
            $this->input('certifications'),
            $this->input('knownLanguages'),

            $this->file('resume'),
            $this->file('portfolio'),
            $this->input('coverLetter'),
            $this->input('coverLetterType'),
            $this->file('coverLetterFile'),

            $this->input('preferredJobType'),
            $this->input('preferredJobIndustry'),
            $this->input('preferredJobEmploymentStatus'),
            $this->input('preferredJobRole'),
            $this->input('preferredJobLocation'),

            $this->input('careerObjective'),
            $this->input('awardsAndRecognitions'),

            $this->input('companyName'),

            $this->input('tab'),
            $this->input('via'),
        );
    }

    public function validations(): array
    {
        return [
            'username' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The username name field is required'
                ],
                [
                    'type' => 'UsernameValidation',
                    'limit' => null,
                    'message' => 'Please enter a valid user name'
                ],
            ],
            'avatar' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The avatar field is required'
                ],
                [
                    'type' => 'file',
                    'limit' => null,
                    'message' => 'The avatar must be a valid file'
                ],
                [
                    'type' => 'mimes',
                    'limit' => 'png,jpg,jpeg',
                    'message' => 'The avatar must be a file of type: png, jpg, jpeg'
                ],
                [
                    'type' => 'maxFileSize',
                    'limit' => 2048,
                    'message' => 'The avatar may not be greater than 2048 kilobytes'
                ],
            ],
            'fullName' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The full name field is required'
                ],
                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The name may only contain letters and can not use special characters or numbers.'
                ],
                [
                    'type' => 'max',
                    'limit' => 40,
                    'message' => 'The full name  cannot exceed 40 characters'
                ],
            ],
            'name' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The name field is required'
                ],
                [
                    'type' => 'max',
                    'limit' => 100,
                    'message' => 'The name  cannot exceed 100 characters'
                ],
            ],
            'email' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The email field is required'
                ],
                [
                    'type' => 'email',
                    'limit' => null,
                    'message' => 'The email field is must be valid type of email'
                ],
            ],
            'dateOfRegister' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The date of register field is required'
                ],
                [
                    'type' => 'dateRule',
                    'limit' => null,
                    'message' => 'Please enter a valid date of register'
                ],
                [
                    'type' => 'PastDateOnlyRule',
                    'limit' => null,
                    'message' => 'Please enter a valid date of register'
                ],
            ],
            'dob' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The date of birth field is required'
                ],
                [
                    'type' => 'dateRule',
                    'limit' => null,
                    'message' => 'Please enter a valid date'
                ],
                [
                    'type' => 'PastDateOnlyRule',
                    'limit' => null,
                    'message' => 'Please enter a valid date'
                ],
            ],
            'gender' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The date of birth field is required'
                ],
            ],
            'street' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The street field is required'
                ],
                [
                    'type' => 'max',
                    'limit' => 100,
                    'message' => 'The street cannot exceed 100 characters.'
                ],
                [
                    'type' => 'max',
                    'limit' => 40,
                    'message' => 'The street cannot exceed 40 characters.'
                ],
            ],
            'city' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The city field is required'
                ],

                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The city may only contain letters and can not use special characters or numbers.'
                ],
                [
                    'type' => 'max',
                    'limit' => 40,
                    'message' => 'The city cannot exceed 40 characters.'
                ],

            ],
            'state' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The state field is required'
                ],
                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The state may only contain letters and can not use special characters or numbers.'
                ],
                [
                    'type' => 'max',
                    'limit' => 40,
                    'message' => 'The state cannot exceed 40 characters.'
                ],
            ],
            'country' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The country field is required'
                ],
            ],
            'postalCode' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The state field is required'
                ],

                [
                    'type' => 'numeric',
                    'limit' => null,
                    'message' => 'The postal code need to be a numbers.'
                ],
            ],
            'mobilePhone' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The phone field is required'
                ],
                [
                    'type' => 'mobile_number',
                    'limit' => null,
                    'message' => 'The phone must be a valid phone number with 10 to 12 digits.'
                ]
            ],
            'alternativeNumber' => [
                [
                    'type' => 'mobile_number',
                    'limit' => null,
                    'message' => 'The alternative number must be a valid phone number with 10 to 12 digits.'
                ]
            ],
            'mobileNumber' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The phone field is required'
                ],
                [
                    'type' => 'mobile_number',
                    'limit' => null,
                    'message' => 'The phone must be a valid phone number with 10 to 12 digits.'
                ]
            ],
            'companyName' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The company name field is required'
                ],
                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The company name may only contain letters and can not use special characters or numbers.'
                ],
                [
                    'type' => 'max',
                    'limit' => 20,
                    'message' => 'The company name  cannot exceed 20 characters'
                ],
            ],
            'currentJobTitle' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The current job title field is required'
                ],
                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The current job title may only contain letters and can not use special characters or numbers.'
                ],
                [
                    'type' => 'max',
                    'limit' => 40,
                    'message' => 'The current job title cannot exceed 40 characters'
                ],
            ],
            'yearsOfExistence' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The years of existence field is required'
                ],
                [
                    'type' => 'max',
                    'limit' => 4,
                    'message' => 'The years of existence cannot exceed 4 digits'
                ],
                [
                    'type' => 'numeric',
                    'limit' => null,
                    'message' => 'The years of existence need to be a numbers.'
                ],
            ],
            'currentCompany' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The current company field is required'
                ],
                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The current company may only contain letters and can not use special characters or numbers.'
                ],
                [
                    'type' => 'max',
                    'limit' => 20,
                    'message' => 'The current company cannot exceed 20 characters'
                ],
            ],
            'currentSalary' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The current salary field is required'
                ],
                [
                    'type' => 'numeric',
                    'limit' => null,
                    'message' => 'The current salary need to be a numbers.'
                ],
                [
                    'type' => 'max',
                    'limit' => 10,
                    'message' => 'The current salary cannot exceed 10 characters'
                ],
            ],
            'expectedSalary' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The expected salary field is required'
                ],
                [
                    'type' => 'numeric',
                    'limit' => null,
                    'message' => 'The expected salary need to be a numbers.'
                ],
                [
                    'type' => 'max',
                    'limit' => 10,
                    'message' => 'The expected salary cannot exceed 10 characters'
                ],
            ],
            'noOfExperience' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The no of experience field is required'
                ],
            ],
            'yearOfGraduation' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The year of graduation is required.'
                ],
                [
                    'type' => 'digits',
                    'limit' => 4,
                    'message' => 'The year of graduation must be a 4-digit number.'
                ],
                [
                    'type' => 'integer',
                    'limit' => null,
                    'message' => 'The year of graduation must be an integer.'
                ],
            ],
            'contactPerson' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The contact person field is required'
                ],
                [
                    'type' => 'max',
                    'limit' => 50,
                    'message' => 'The contact person name field must not be greater than 50 characters.'
                ],
                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The contact person may only contain letters and can not use special characters or numbers.'
                ],
            ],
            'noticePeriod' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The notice period field is required'
                ],
            ],
            'canRelocated' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The can relocated field is required'
                ],
            ],
            'contactPersonEmail' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The contact person email field is required'
                ],
                [
                    'type' => 'email',
                    'limit' => null,
                    'message' => 'The contact person email field is must be valid type of email'
                ],
            ],
            'contactPersonPhone' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The contact person phone field is required'
                ],
                [
                    'type' => 'mobile_number',
                    'limit' => null,
                    'message' => 'The contact person phone must be a valid phone number with 10 to 12 digits.'
                ],
            ],
            'contactPersonAddress' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The contact person address field is required'
                ],
                [
                    'type' => 'max',
                    'limit' => 200,
                    'message' => 'The contact person address field must not be greater than 200 characters.'
                ],
            ],
            'qualification' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The qualification field is required'
                ],
            ],
            'resume' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The resume is required.'
                ],
                [
                    'type' => 'file',
                    'limit' => null,
                    'message' => 'The resume must be a file.'
                ],
                [
                    'type' => 'mimes',
                    'limit' => 'pdf,docx,doc',
                    'message' => 'The resume must be a file of type: pdf, docx, doc.'
                ],
                [
                    'type' => 'maxFileSize',
                    'limit' => 5120,
                    'message' => 'The resume may not be greater than 5120 kilobytes.'
                ],
            ],
            'registrationDoc' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The registration document is required.'
                ],
                [
                    'type' => 'file',
                    'limit' => null,
                    'message' => 'The registration document must be a file.'
                ],
                [
                    'type' => 'mimes',
                    'limit' => 'pdf,docx,doc',
                    'message' => 'The registration document must be a file of type: pdf, docx, doc.'
                ],
                [
                    'type' => 'maxFileSize',
                    'limit' => 5120,
                    'message' => 'The registration document may not be greater than 5120 kilobytes.'
                ],
            ],
            'specializedIn' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The specializedIn field is required'
                ],
                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The specializedIn may only contain letters and can not use special characters or numbers.'
                ],
                [
                    'type' => 'max',
                    'limit' => 20,
                    'message' => 'The specialized in field must not be greater than 20 characters.'
                ],
            ],
            'address' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The address field is required'
                ],
                [
                    'type' => 'max',
                    'limit' => 200,
                    'message' => 'The address field must not be greater than 200 characters.'
                ],
            ],
            'certifications' => [
                [
                    'type' => 'max',
                    'limit' => 200,
                    'message' => 'The certifications cannot exceed 200 characters.'
                ],
            ],
            'knownLanguages' => [
                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The known languages may only contain letters and can not use special characters or numbers.'
                ],
                [
                    'type' => 'max',
                    'limit' => 200,
                    'message' => 'The knownLanguages cannot exceed 200 characters.'
                ],
            ],
            'university' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The university field is required'
                ],
                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The university may only contain letters and can not use special characters or numbers.'
                ],
                [
                    'type' => 'max',
                    'limit' => 20,
                    'message' => 'The university field must not be greater than 20 characters.'
                ],
            ],
            'additionalQualification' => [
                [
                    'type' => 'required_if',
                    'limit' => 'hasAdditionalQualification,yes',
                    'message' => 'The additional qualification field is required when additional qualification is set to yes'
                ],
                [
                    'type' => 'max',
                    'limit' => 100,
                    'message' => 'The additional qualification cannot exceed 100 characters'
                ],
            ],
            'keySkills' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The keySkills field is required'
                ],
            ],
            'coverLetter' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The cover letter field is required'
                ],
                [
                    'type' => 'max',
                    'limit' => 200,
                    'message' => 'The cover letter field must not be greater than 200 characters.'
                ]
            ],
            'jobTypes' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The job types field is required'
                ],
            ],
            'preferredJobIndustry' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The preferred job industry field is required'
                ],
            ],
            'preferredJobRole' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The job role field is required'
                ],
                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The job role  may only contain letters and can not use special characters or numbers.'
                ],
                [
                    'type' => 'max',
                    'limit' => 20,
                    'message' => 'The preferred job role field must not be greater than 20 characters.'
                ]
            ],
            'preferredJobLocation' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The preferred job location field is required'
                ],
            ],
            'preferredJobEmploymentStatus' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The employment status field is required'
                ],
            ],
            'careerObjective' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The career objective status field is required'
                ],
                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The career objective  may only contain letters and can not use special characters or numbers.'
                ],
                [
                    'type' => 'max',
                    'limit' => 200,
                    'message' => 'The career objective cannot exceed 200 characters'
                ],
            ],
            'awardsAndRecognitions' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The awards and recognitions status field is required'
                ],
                [
                    'type' => 'max',
                    'limit' => 200,
                    'message' => 'The career objective cannot exceed 200 characters'
                ],
            ],
            'gst' => [
                [
                    'type' => 'max',
                    'limit' => 15,
                    'message' => 'The gst cannot exceed 15 characters'
                ],
            ],
            'candidateExperiences' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'At least one candidate experience entry is required.'
                ],
                [
                    'type' => 'array',
                    'limit' => null,
                    'message' => 'Candidate experiences must be in an array format.'
                ],
                [
                    'type' => 'custom',
                    'limit' => null,
                    'message' => 'Each experience entry must contain valid data.',
                    'rule' => new CheckIfExperiencesArrayContainAtLeastOneExperience(),
                ],
            ],
            'candidateExperiences.0.companyName' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The company name field is required for each experience.'
                ],
                [
                    'type' => 'string',
                    'limit' => null,
                    'message' => 'The company name must be a string.'
                ],
                [
                    'type' => 'max',
                    'limit' => 40,
                    'message' => 'The company name cannot exceed 40 characters.'
                ],
            ],
            'candidateExperiences.0.jobTitle' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The job title field is required for each experience.'
                ],
                [
                    'type' => 'string',
                    'limit' => null,
                    'message' => 'The job title must be a string.'
                ],
                [
                    'type' => 'max',
                    'limit' => 60,
                    'message' => 'The job title cannot exceed 60 characters.'
                ],
            ],
            'candidateExperiences.0.startDate' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The start date is required for each experience.'
                ],
                [
                    'type' => 'date',
                    'limit' => null,
                    'message' => 'The start date must be a valid date.'
                ],
                [
                    'type' => 'before_or_equal',
                    'limit' => 'candidateExperiences.*.endDate',
                    'message' => 'The start date must be before or equal to the end date.'
                ],
            ],
            'candidateExperiences.0.endDate' => [
                [
                    'type' => 'nullable',
                    'limit' => null,
                    'message' => 'The end date can be left empty if applicable.'
                ],
                [
                    'type' => 'date',
                    'limit' => null,
                    'message' => 'The end date must be a valid date if provided.'
                ],
                [
                    'type' => 'after_or_equal',
                    'limit' => 'candidateExperiences.*.startDate',
                    'message' => 'The end date must be after or equal to the start date.'
                ],
            ],
            'candidateExperiences.0.responsibilities' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The responsibilities is required for each experience.'
                ],
                [
                    'type' => 'string',
                    'limit' => null,
                    'message' => 'Responsibilities must be a valid string if provided.'
                ],
                [
                    'type' => 'max',
                    'limit' => 200,
                    'message' => 'The responsibilities cannot exceed 200 characters.'
                ],
            ],
            'candidateExperiences.0.achievements' => [
                [
                    'type' => 'max',
                    'limit' => 200,
                    'message' => 'The achievements cannot exceed 200 characters.'
                ],
            ],
        ];
    }
}
