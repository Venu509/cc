<?php

namespace Domain\Banner\Requests;

use Illuminate\Validation\Rule;
use Domain\Banner\Data\BannerData;
use Domain\Global\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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
                'string',
                'max:190',
                isset($id) ? Rule::unique('banners')->ignore($id) : 'unique:banners,title',
            ],
            'image' => [
                isset($id) ? 'sometimes' : 'required',
                isset($id) ? null : 'mimes:jpeg,png,jpg,gif',
                isset($id) ? null : 'max:2048',
            ],
        ];
    }

    public function data(): BannerData
    {
        return new BannerData(
            $this->input('title'),
            $this->file('image'),
            $this->input('url'),
        );
    }
}
