<?php

namespace Domain\Workshop\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkshopResources extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => [
                'label' => $this->workshopName->name,
                'value' => $this->name
            ],
            'description' => $this->description,
            'branch' => [
                'id' => $this->branch_id,
                'name' => $this->branch->name
            ],
            'semester' => (int)$this->semester,
            'date' => $this->date,
            'endDate' => $this->end_date,
        ];
    }
}
