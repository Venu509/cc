<?php

namespace Domain\Skill\Requests;

use Domain\Global\Traits\Validation;
use Domain\Skill\Data\SkillData;
use Illuminate\Foundation\Http\FormRequest;

class SkillRequest extends FormRequest
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
            'title' => [
                'required',
                'max:60',
            ],
        ];
    }

    public function data(): SkillData
    {
        return new SkillData(
            $this->input('id'),
            $this->input('title'),
        );
    }
}
