<?php

namespace Domain\Lead\Requests;

use Domain\Global\Rules\MultipleDropdownValidate;
use Domain\Global\Traits\Validation;
use Domain\Lead\Data\LeadData;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LeadRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
//            'role' => [
//                'required',
//                Rule::in([
//                    'marketing',
//                ])
//            ],
            'title' => [
                'required',
                'max:80',
                function ($attribute, $value, $fail) {
                    $exists = DB::table('leads')
                        ->where('added_by', Auth::user()->id)
                        ->where('status', $this->status)
                        ->where('title', $this->title)
                        ->when($this->id, function (Builder $builder){
                            return $builder->whereNot('id', $this->id);
                        })
                        ->exists();

                    if ($exists) {
                        $fail('the data is already exsist.');
                    }
                },
            ],
            'status' => [
                'max:200',
            ],
            'type' => [
                'required',
                'array',
                new MultipleDropdownValidate(),
            ],
        ];
    }

    public function data(): LeadData
    {
        return new LeadData(
            $this->input('id'),
            $this->input('title'),
            $this->input('type')['value'],
            $this->input('status'),
            $this->input('description'),
        );
    }

    public function validations(): array
    {
        return [
            'title' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The title field is required'
                ],
                [
                    'type' => 'string',
                    'limit' => null,
                    'message' => 'Title must be a string'
                ],
                [
                    'type' => 'max',
                    'limit' => 80,
                    'message' => 'Title cannot exceed 80 characters'
                ],
                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The title may only contain letters and can not use special characters or numbers.'
                ],
            ],
            'type' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The type field is required'
                ],
            ],
            'status' => [
                [
                    'type' => 'string',
                    'limit' => null,
                    'message' => 'Status must be a string'
                ],
                [
                    'type' => 'max',
                    'limit' => 200,
                    'message' => 'Status cannot exceed 200 characters'
                ],
            ],
            'description' => [
                [
                    'type' => 'string',
                    'limit' => null,
                    'message' => 'Description must be a string'
                ],
                [
                    'type' => 'max',
                    'limit' => 200,
                    'message' => 'Description cannot exceed 200 characters'
                ],
            ],
        ];
    }
}
