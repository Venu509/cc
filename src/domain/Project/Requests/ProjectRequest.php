<?php

namespace Domain\Project\Requests;

use Domain\Global\Rules\DateRule;
use Domain\Global\Rules\FutureDateOnlyRule;
use Domain\Global\Rules\MultipleDropdownValidate;
use Domain\Global\Traits\Validation;
use Domain\Project\Data\ProjectData;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    use Validation;
    protected bool $checkDuplicate = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->request->get('id');
        $this->request->set('checkDuplicate', true);

        return [
            'name' => [
                'required',
                'array',
                new MultipleDropdownValidate(),

            ],
            'type' => [
                'required',
                Rule::in(['mini', 'academic'])
            ],
            'branch' => [
                'required',
                'array',
                new MultipleDropdownValidate(),
            ],
            'semester' => [
                'required',
            ],
            'description' => [
                'max:300'
            ],
            'startDate' => [
                'required',
                'before_or_equal:endDate',
                new DateRule(),
                new FutureDateOnlyRule()
            ],
            'endDate' => [
                'required',
                'after_or_equal:date',
                new DateRule(),
                new FutureDateOnlyRule(),
                function ($attribute, $value, $fail) {
                    $exists = DB::table('projects')
                        ->where('added_by', Auth::user()->id)
                        ->where('name', $this->name['value'])
                        ->where('type', $this->type)
                        ->where('branch_id', $this->branch['value'])
                        ->where('semester', $this->semester)
                        ->where('description', $this->description)
                        ->where('date', $this->startDate)
                        ->where('end_date', $this->endDate)
                        ->when($this->id, function (Builder $builder){
                            return $builder->whereNot('id', $this->id);
                        })
                        ->exists();

                    if ($exists) {
                        $fail('the data is already exsist.');
                    }
                },
            ],
        ];
    }

    public function data(): ProjectData
    {
        return new ProjectData(
            $this->input('id'),
            $this->input('name'),
            $this->input('type'),
            $this->input('branch'),
            $this->input('semester'),
            $this->input('description'),
            $this->input('startDate'),
            $this->input('endDate'),
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
                ]
            ],
            'type' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The type field is required'
                ],
            ],
            'branch' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The branch field is required'
                ],
            ],
            'semester' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The semester field is required'
                ],
            ],
            'description' => [
                [
                    'type' => 'max',
                    'limit' => 300,
                    'message' => 'The description field cannot exceed 300 characters'
                ],
            ],
            'startDate' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The date field is required'
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
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The date field is required'
                ],
                [
                    'type' => 'dateRule',
                    'limit' => null,
                    'message' => 'Please enter a valid date'
                ],
                [
                    'type' => 'FutureDateOnlyRule',
                    'limit' => null,
                    'message' => 'The end date must be today or a future date within 1 year'
                ],
            ],
        ];
    }

}
