<?php

namespace Domain\Role\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResources extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'displayName' => $this->display_name,
            'guardName' => $this->guard_name,
            'disableAction' => in_array(auth()->user()->email, $this->users()->get()->pluck('email')->toArray(), true),
        ];
    }
}
