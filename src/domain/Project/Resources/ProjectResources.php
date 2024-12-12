<?php

namespace Domain\Project\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResources extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' =>[
                'label' => $this->projectName->name,
                'value' => $this->name
            ],
            'type' => $this->type,
            'date' => $this->date,
            'endDate' => $this->end_date,
            'description' => $this->description,
            'branch' => [
                'id' => $this->branch_id,
                'name' => $this->branch->name
            ],
            'semester' => $this->semester,
        ];
    }
}
