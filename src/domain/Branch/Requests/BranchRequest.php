<?php

namespace Domain\Branch\Requests;

use Domain\Branch\Data\BranchData;
use Domain\Global\Rules\TextOnly;
use Domain\Global\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:40',
                new TextOnly(),
                Rule::unique('branches')->where(function ($query) {
                    return $query->where('added_by', auth()->id())->whereNull('deleted_at');
                })->ignore($id),
            ],
            'description' => [
                'required',
                'string',
                'max:120',
            ],
        ];
    }

    public function data(): BranchData
    {
        return new BranchData(
            $this->input('id'),
            $this->input('name'),
            $this->input('description'),
        );
    }

    public function validations(): array
    {
        return [
            'name' => [
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
                    'limit' => 40,
                    'message' => 'Name cannot exceed 40 characters'
                ],
                [
                    'type' => 'textOnly',
                    'limit' => null,
                    'message' => 'The name may only contain letters and can not use special characters or numbers.'
                ],
            ],
            'description' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The description field is required'
                ],
                [
                    'type' => 'string',
                    'limit' => null,
                    'message' => 'Description must be a string'
                ],
                [
                    'type' => 'max',
                    'limit' => 120,
                    'message' => 'Description cannot exceed 120 characters'
                ],
            ],
        ];
    }
}
