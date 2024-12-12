<?php

namespace Domain\DeviceToken\Requests;

use Domain\Global\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Domain\DeviceToken\Data\DeviceTokenData;
use Illuminate\Validation\Rule;

class DeviceTokenRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'userId' => 'required|numeric',
            'deviceToken' => [
                'required',
                'string',
            ],
            'deviceType' => [
                'required',
                Rule::in(['android', 'ios', 'web'])
            ],
        ];
    }

    public function data(): DeviceTokenData
    {
        return new DeviceTokenData(
            (int) $this->input('userId'),
            $this->input('deviceToken'),
            $this->input('deviceType'),
        );
    }
}
