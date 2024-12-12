<?php

namespace Domain\Student\Requests;

use Domain\Global\Rules\AcceptTextAndNumbersRule;
use Domain\Global\Rules\DateRule;
use Domain\Global\Rules\EmailValidationRule;
use Domain\Global\Rules\MobileNumberRule;
use Domain\Global\Rules\PanCardRule;
use Domain\Global\Rules\PastDateOnlyRule;
use Domain\Global\Rules\TextOnly;
use Domain\Global\Traits\Validation;
use Domain\Student\Data\StudentData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
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
            'firstName' => [
                'required',
                'string',
                'max:70',
                new TextOnly(),
            ],
            'lastName' => [
                'required',
                'string',
                'max:70',
                new TextOnly(),
            ],
            'dateOfBirth' => [
                'required',
                new DateRule(),
                new PastDateOnlyRule()
            ],
            'studentId' => [
                'required',
                'max:12',
                Rule::unique('students', 'student_id')->ignore($id),
                new AcceptTextAndNumbersRule()
            ],
            'mobileNumber' => [
                'required',
                'numeric',
                'digits_between:10,12',
                new MobileNumberRule()
            ],
            'email' => [
                'required',
                'email',
                new EmailValidationRule()
            ],
            'address' => [
                'required',
                'max:200'
            ],
            'resume' => 'nullable',
            'panNumber' => [
                'nullable',
                'max:10',
                new PanCardRule(),
            ],
            'aadhaarNumber' => [
                'required',
                'regex:/^\d{12}$/',
                'numeric',
            ],
            'qualification' => 'required',
            'gender' => [
                'required',
                Rule::in(['male', 'female', 'other'])
            ],
            'maritalStatus' => [
                'required',
                Rule::in(['single', 'married'])
            ],
            'profilePicture' => [
                isset($id) ? 'sometimes' : 'required',
                isset($id) ? 'sometimes' : 'mimes:jpeg,png',
                'max:2048',
            ],
            'branch' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    if (!isset($value['value'])) {
                        $fail('The ' . $attribute . '.value field is required.');
                    }
                },
            ],
        ];
    }

    public function data(): StudentData
    {
        return new StudentData(
            $this->input('id'),
            $this->input('firstName'),
            $this->input('lastName'),
            $this->input('dateOfBirth'),
            $this->input('mobileNumber'),
            $this->input('email'),
            $this->input('address'),
            $this->file('resume'),
            $this->input('panNumber'),
            $this->input('aadhaarNumber'),
            $this->input('qualification'),
            $this->input('gender'),
            $this->input('maritalStatus'),
            $this->file('profilePicture'),
            $this->input('branch'),
            $this->input('studentId'),
        );
    }

    public function validations(): array
    {
        return [
            'firstName' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The name field is required'
                ],
                [
                    'type' => 'string',
                    'limit' => null,
                    'message' => 'Name must be a string'
                ],
                [
                    'type' => 'max',
                    'limit' => 70,
                    'message' => 'Name cannot exceed 70 characters'
                ],
                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The name may only contain letters and can not use special characters or numbers.'
                ],
            ],
            'lastName' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The name field is required'
                ],
                [
                    'type' => 'string',
                    'limit' => null,
                    'message' => 'Name must be a string'
                ],
                [
                    'type' => 'max',
                    'limit' => 70,
                    'message' => 'Name cannot exceed 70 characters'
                ],
                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The name may only contain letters and can not use special characters or numbers.'
                ],
            ],
            'dateOfBirth' => [
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
                    'message' => 'The must be a valid past date.'
                ],
            ],
            'mobileNumber' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The mobile number field is required'
                ],
                [
                    'type' => 'mobile_number',
                    'limit' => null,
                    'message' => 'Please enter a valid number'
                ],
                [
                    'type' => 'max',
                    'limit' => 12,
                    'message' => 'The mobile number can have a maximum of 12 numbers.'
                ],
                [
                    'type' => 'min',
                    'limit' => 10,
                    'message' => 'The mobile number need to be minimum 10 numbers'
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
                    'message' => 'Please enter a valid email'
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
                    'message' => 'address cannot exceed 200 characters'
                ],
            ],
            'branch' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The branch number field is required'
                ],
            ],
            'aadhaarNumber' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The aadhaar number field is required'
                ],
                [
                    'type' => 'max',
                    'limit' => 12,
                    'message' => 'The aadhaar number can have a maximum of 12 characters.'
                ],
                [
                    'type' => 'aadhaarNumber',
                    'limit' => null,
                    'message' => 'The aadhaar number must be 12 characters.'
                ],
            ],
            'panNumber' => [
                [
                    'type' => 'panCardRule',
                    'limit' => null,
                    'message' => 'Please enter valid PAN card number'
                ],
            ],
            'qualification' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The qualification field is required'
                ],
            ],
            'gender' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The gender field is required'
                ],
            ],
            'maritalStatus' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The marital status field is required'
                ],
            ],
            'profilePicture' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The profile picture field is required'
                ],
            ],
            'studentId' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The student id field is required'
                ],
                [
                    'type' => 'max',
                    'limit' => 12,
                    'message' => 'The student ID can have a maximum of 12 characters.'
                ],
                [
                    'type' => 'AcceptTextAndNumbersRule',
                    'limit' => null,
                    'message' => 'The student ID can have only numbers and letters.'
                ],
            ],
        ];
    }
}
