<?php

namespace Domain\Country\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResources extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'region' => $this->region,
            'timezones' => $this->timezones,
            'isoAlpha2' => $this->iso_alpha_2,
            'isoAlpha3' => $this->iso_alpha_3,
            'isoNumeric' => $this->iso_numeric,
            'phone' => $this->phone,
            'emoji' => $this->emoji,
            'image' => $this->image,
        ];
    }
}
