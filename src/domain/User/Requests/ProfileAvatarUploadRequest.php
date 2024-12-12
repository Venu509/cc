<?php

namespace Domain\User\Requests;

use Domain\Global\Traits\Validation;
use Domain\User\Data\ProfileAvatarUploadData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileAvatarUploadRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role' => [
                'required',
                Rule::in(['dealer', 'driver', 'customer'])
            ],
            'user' => [
                'required',
            ],
            'image' => [
                'required',
                'mimes:jpeg,png,jpg,gif',
                'max:2048',
            ],
        ];
    }

    public function data(): ProfileAvatarUploadData
    {
        return new ProfileAvatarUploadData(
            $this->input('role'),
            $this->input('user'),
            $this->file('image'),
        );
    }
}
