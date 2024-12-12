<?php

namespace Domain\Notification\Requests;

use Domain\Global\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Domain\Notification\Data\ReadNotificationData;
use Illuminate\Validation\Rule;

class ReadNotificationRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required_if:type,single',
            'type' => [
                'required',
                Rule::in(['single', 'all']),
            ]
        ];
    }

    public function data(): ReadNotificationData
    {
        return new ReadNotificationData(
            $this->input('id'),
            $this->input('type'),
        );
    }
}
