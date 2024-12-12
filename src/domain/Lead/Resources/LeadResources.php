<?php

namespace Domain\Lead\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;
use Support\Helper\Helper;

class LeadResources extends JsonResource
{
    use Helper;

    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'type' => [
                'label' => ucfirst($this->type),
                'value' => $this->type,
                'color' => $this->color($this->type),
            ],
            'status' => $this->status,
            'description' => $this->description,
            'isActive' => $this->is_active,
            'user' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ] : [],
        ];

        $currentRouteName = Route::currentRouteName();

        if ($currentRouteName === 'admin.leads.index') {
            $data['addedBy'] = $this->addedBy->name;
            $data['lastAccessedBy'] = $this->modifiedBy->name;
        }

        return $data;
    }
}