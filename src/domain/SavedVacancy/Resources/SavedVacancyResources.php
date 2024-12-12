<?php

namespace Domain\SavedVacancy\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SavedVacancyResources extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'studentsCount' => $this->students->count(),
            'description' => $this->description,
            'slug' => $this->slug,
        ];
    }
}
