<?php

namespace Domain\Attendance\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonException;

class AttendanceResources extends JsonResource
{
    /**
     * @throws JsonException
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'clockInAt' => $this->clock_in_at,
            'clockOutAt' => $this->clock_out_at,
            'clockInIp' => $this->clock_in_ip,
            'clockOutIp' => $this->clock_out_ip,
            'clockInCoordinates' => $this->clock_in_coordinates ? json_decode($this->clock_in_coordinates, true, 512, JSON_THROW_ON_ERROR) : null,
            'clockOutCoordinates' => $this->clock_out_coordinates ? json_decode($this->clock_out_coordinates, true, 512, JSON_THROW_ON_ERROR) : null,
            'user' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ] : [],
            'lastAccessedBy' => $this->modifiedBy->name,
        ];
    }
}
