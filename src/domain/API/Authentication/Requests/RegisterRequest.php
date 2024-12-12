<?php

namespace Domain\API\Authentication\Requests;

use Domain\API\Authentication\Data\RegisterData;
use Domain\API\Authentication\Rules\UsernameValidation;
use Domain\Global\Rules\AcceptTextAndNumbersRule;
use Domain\Global\Rules\DateRule;
use Domain\Global\Rules\EmailValidationRule;
use Domain\Global\Rules\LeastHaveOneLetterRule;
use Domain\Global\Rules\MobileNumberRule;
use Domain\Global\Rules\MultipleDropdownValidate;
use Domain\Global\Rules\PastDateOnlyRule;
use Domain\Global\Rules\TextOnly;
use Domain\Global\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->request->get('userId');

        $id = isset($id) ? (int)$id : null;

        $rules = [
            'selectedRole' => [
                'required',
                Rule::in([
                    'government',
                    'institution',
                    'candidate',
                    'company',
                ])
            ],
            'email' => [
                'required',
                'email',
                new EmailValidationRule(),
                new UsernameValidation(id: $id,  column: 'email'),
            ],
            'phone' => [
                'required',
                new MobileNumberRule(),
                new UsernameValidation(id: $id,  column: 'phone'),
            ],
            'password' => [
                'required',
                'confirmed',
                app()->isLocal() ? null : Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'via' => [
                'required',
                Rule::in(['email', 'phone'])
            ],
            'username' => [
                'required',
                new UsernameValidation($this->input('via'), $id, 'username'),
            ],
        ];

        if ($this->input('selectedRole') === 'government' || $this->input('selectedRole') === 'institution') {
            $rules = array_merge($rules, [
                'companyName' => [
                    'required',
                    'max:100',
                ],
                'contactPerson' => [
                    'required',
                    'max:60',
                    new TextOnly()
                ],
                'contactPersonEmail' => [
                    'required',
                    'email',
                    new EmailValidationRule(),
                ],
                'contactPersonPhone' => [
                    'required',
                    new MobileNumberRule(),
                ],
                'contactPersonAddress' => [
                    'required',
                    'max:200',
                ],
                'dateOfRegistration' => [
                    'required',
                    new DateRule()
                ],
                'address' => [
                    'required',
                    'max:200',
                ],
                'yearsOfExistence' => [
                    'required'
                ],
                'registrationDoc' => [
                    'required',
                    'file',
                    'mimes:pdf,docx,doc',
                    'max:5120'
                ],
            ]);
        } elseif ($this->input('selectedRole') === 'candidate') {
            $rules = array_merge($rules, [
                'fullName' => [
                    'required',
                    'max:100',
                    new TextOnly()
                ],
                'gender' => [
                    'required',
                    Rule::in(['male', 'female', 'other'])
                ],
                'dob' => [
                    'required',
                    new DateRule(),
                    new PastDateOnlyRule(),
                ],
                'maritalStatus' => [
                    'required',
                    Rule::in(['single', 'married', 'divorced', 'widowed'])
                ],
                'alternativeNumber' => [
                    'nullable',
                    'string'
                ],
                'preferredJobRole' => [
                    'required',
                    'string',
                    'max:40',
                    new TextOnly()
                ],
                'preferredJobLocation' => [
                    'required',
                    'array',
                ],
                'keySkills' => [
                    'required',
                    'array',
                ],
                'noticePeriod' => [
                    'required',
                    'array',
                    new MultipleDropdownValidate(['immediate', '15-days', '30-days', '60-days', '90-days', 'more']),
                ],
                'expectedSalary' => [
                    'required',
                    'string',
                    'max:10'
                ],
                'street' => [
                    'required',
                    'string',
                    'max:20'
                ],
                'city' => [
                    'required',
                    'string',
                    'max:20'
                ],
                'state' => [
                    'required',
                    'string',
                    'max:20'
                ],
                'postalCode' => [
                    'required',
                    'string',
                    'max:6'
                ],
                'country' => [
                    'required',
                    'array',
                    new MultipleDropdownValidate(),
                ],
                'avatar' => [
                    'required',
                    'file',
                    'mimes:pdf,jpeg,png,jpg,gif',
                    'max:5120'
                ],
                'resume' => [
                    'required',
                    'file',
                    'mimes:pdf,doc,docx',
                    'max:5120'
                ],
            ]);
        } elseif ($this->input('selectedRole') === 'company') {
            $rules = array_merge($rules, [
                'companyName' => [
                    'required',
                    'max:100',
                ],
                'contactPerson' => [
                    'required',
                    'max:60'
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
                'registrationDoc' => [
                    'required',
                    'file',
                    'mimes:pdf,docx,doc',
                    'max:5120'
                ],
            ]);
        }

        return $rules;
    }

    public function data(): RegisterData
    {
        return new RegisterData(
            $this->input('userId'),
            $this->input('selectedRole'),
            $this->input('via'),
            $this->input('phone'),
            $this->input('email'),
            $this->input('name'),
            $this->input('companyName'),
            $this->input('contactPerson'),
            $this->input('contactPersonEmail'),
            $this->input('contactPersonPhone'),
            $this->input('contactPersonAddress'),
            $this->input('dateOfRegistration'),
            $this->input('username'),
            $this->input('websiteUrl'),
            $this->input('address'),
            $this->input('password'),
            $this->input('yearsOfExistence'),
            $this->input('gst'),
            $this->file('avatar'),
            $this->file('registrationDoc'),

            $this->input('fullName'),
            $this->input('alternativeNumber'),
            $this->input('preferredJobRole'),
            $this->input('preferredJobLocation'),
            $this->input('keySkills'),
            $this->input('noticePeriod'),
            $this->input('expectedSalary'),
            $this->input('street'),
            $this->input('city'),
            $this->input('state'),
            $this->input('postalCode'),
            $this->input('country'),
            $this->input('firstName'),
            $this->input('lastName'),
            $this->input('studentID'),
            $this->input('gender') ??  'male',
            $this->input('dob'),
            $this->input('panNumber'),
            $this->input('aadharNumber'),
            $this->input('qualification'),
            $this->input('maritalStatus') ?? 'single',
            $this->input('experience'),
            $this->input('skillSet'),
            $this->file('resume'),
        );
    }

    public function validations(): array
    {
        return [
            'code' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The code field is required'
                ],
            ],
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
            'password_confirmation' => [
                [
                    'type' => 'confirmed',
                    'limit' => null,
                    'message' => 'The password confirmation does not match'
                ],
            ],
            'password' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The password field is required'
                ],
                [
                    'type' => 'min',
                    'limit' => 8,
                    'message' => 'The password must be at least 8 characters long'
                ],
                [
                    'type' => 'letters',
                    'limit' => null,
                    'message' => 'The password must contain at least one letter'
                ],
                [
                    'type' => 'numbers',
                    'limit' => null,
                    'message' => 'The password must contain at least one number'
                ],
                [
                    'type' => 'symbols',
                    'limit' => null,
                    'message' => 'The password must contain at least one symbol'
                ],
                [
                    'type' => 'uncompromised',
                    'limit' => null,
                    'message' => 'The password has appeared in a data breach and should not be used'
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
                    'limit' => 100,
                    'message' => 'The full name field must not be greater than 100 characters.'
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
                [
                    'type' => 'emailValidationRule',
                    'limit' => null,
                    'message' => 'The email must be a valid email address.'
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
            'street' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The street field is required'
                ],

                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The street may only contain letters and can not use special characters or numbers.'
                ],

                [
                    'type' => 'max',
                    'limit' => 20,
                    'message' => 'The street field must not be greater than 20 characters.'
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
                    'limit' => 20,
                    'message' => 'The city field must not be greater than 20 characters.'
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
                    'limit' => 20,
                    'message' => 'The state field must not be greater than 20 characters.'
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
                    'message' => 'The postal code field is required'
                ],

                [
                    'type' => 'numeric',
                    'limit' => null,
                    'message' => 'The postal code may only contain letters and can not use special characters or numbers.'
                ],

                [
                    'type' => 'max',
                    'limit' => 6,
                    'message' => 'The postal code cannot exceed 6 characters.'
                ],
            ],
            'phone' => [
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
                    'type' => 'max',
                    'limit' => 100,
                    'message' => 'The company name  cannot exceed 100 characters'
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
                    'limit' => 50,
                    'message' => 'The current job title cannot exceed 50 characters'
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
                    'limit' => 50,
                    'message' => 'The current company cannot exceed 50 characters'
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
                [
                    'type' => 'min',
                    'limit' => 1900,
                    'message' => 'The year of graduation must be at least 1900.'
                ],
                [
                    'type' => 'max',
                    'limit' => 2099,
                    'message' => 'The year of graduation cannot be greater than 2099.'
                ],
            ],
            'contactPerson' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The contact person field is required'
                ],
                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The contact person may only contain letters and can not use special characters or numbers.'
                ],
                [
                    'type' => 'max',
                    'limit' => 60,
                    'message' => 'The contact person name field must not be greater than 60 characters.'
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
                [
                    'type' => 'emailValidationRule',
                    'limit' => null,
                    'message' => 'The contact person email must be a valid email address.'
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
                    'message' => 'The contact person address cannot be greater than 200.'
                ],
            ],
            'qualification' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The qualification field is required'
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
            'resume' => [
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
            ],
            'knownLanguages' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The known languages field is required'
                ],
                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The known languages may only contain letters and can not use special characters or numbers.'
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
            ],
            'additionalQualification' => [
                [
                    'type' => 'required_if',
                    'limit' => 'hasAdditionalQualification,yes',
                    'message' => 'The additional qualification field is required when additional qualification is set to yes'
                ],
            ],
            'certifications' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The certifications field is required'
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
                    'limit' => 40,
                    'message' => 'The preferred job title cannot be greater than 40.'
                ],
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
            'carerObjective' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The carer objective status field is required'
                ],
                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The carer objective  may only contain letters and can not use special characters or numbers.'
                ]
            ],
            'dateOfRegistration' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The date of registration field is required'
                ],
                [
                    'type' => 'DateRule',
                    'limit' => null,
                    'message' => 'Please enter a valid date'
                ]
            ],
            'awardsAndRecognitions' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The awards and recognitions status field is required'
                ],
            ],
        ];
    }
}
