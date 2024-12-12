<?php

namespace Domain\ProjectName\Requests;

use Domain\Global\Rules\DateRule;
use Domain\Global\Rules\FutureDateOnlyRule;
use Domain\Global\Rules\TextOnly;
use Domain\Global\Traits\Validation;
use Domain\ProjectName\Data\ProjectNameData;
use Illuminate\Foundation\Http\FormRequest;

class ProjectNameRequest extends FormRequest
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
                'max:200',
                ],
        ];
    }

    public function data(): ProjectNameData
    {
        return new ProjectNameData(
            $this->input('id'),
            $this->input('name'),
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
                    'limit' => 200,
                    'message' => 'Name cannot exceed 200 characters'
                ],
            ],
        ];
    }

}
