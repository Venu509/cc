<?php

namespace Domain\Workshop\Requests;

use Domain\Global\Rules\DateRule;
use Domain\Global\Rules\FutureDateOnlyRule;
use Domain\Global\Rules\MultipleDropdownValidate;
use Domain\Global\Traits\Validation;
use Domain\Workshop\Data\WorkshopData;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkshopRequest extends FormRequest
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
                'array',
                new MultipleDropdownValidate(),
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
                'max:300',
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
                    $exists = DB::table('workshops')
                        ->where('added_by', Auth::user()->id)
                        ->where('name', $this->name['value'])
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

    public function data(): WorkshopData
    {
        return new WorkshopData(
            $this->input('id'),
            $this->input('name'),
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
                    'message' => 'The date must be today or a future date within 1 year'
                ],
            ],
            'description' => [
                [
                    'type' => 'max',
                    'limit' => 300,
                    'message' => 'The description field cannot exceed 300 characters'
                ],
            ],
        ];
    }

}
