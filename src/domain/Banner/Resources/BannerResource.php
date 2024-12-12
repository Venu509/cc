<?php

namespace Domain\Banner\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->url,
            'image' => imageCheck('banners/'.$this->image),
            'isActive' => $this->is_active,
            'addedBy' => $this->addedBy->name,
            'lastAccessedBy' => $this->modifiedBy->name,
        ];
    }
}
