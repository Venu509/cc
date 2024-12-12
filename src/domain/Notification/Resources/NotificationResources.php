<?php

namespace Domain\Notification\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use JsonException;

class NotificationResources extends JsonResource
{
    /**
     * @throws JsonException
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->id === auth()->user()->id ? "You've" : $this->user->name,
                'avatar' => $this->avatar($this->user),
            ] : [],
            'userType' => $this->user_type,
            'domain' => $this->domain,
            'title' => $this->title,
            'type' => $this->type,
            'message' => $this->message,
            'isRead' => $this->is_read,
            'additionalInfo' => json_decode($this->data, true, 512, JSON_THROW_ON_ERROR),
            'icon' => $this->icon($this->domain),
            'readAt' => $this->read_at ? $this->read_at->diffForHumans() : null,
            'addedAt' => Carbon::parse($this->created_at)->diffForHumans(),
            'createdAt' => $this->formatAddedAt($this->created_at),
            'isActive' => $this->is_active,
        ];
    }

    public function icon(string $status): string
    {
        $fileName = in_array($status, ['accepted', 'canceled', 'completed', 'pending'], true) ? $status : 'other';

        return asset('images/notifications/icons/' . $fileName . '.png');
    }

    private function avatar($user): ?string
    {
        if($user->avatar) {
            return imageCheck('user-details/avatars/thumbnail/' . $user->avatar);
        }

        return $user->profile_photo_path ? imageCheck($user->profile_photo_path) : "https://ui-avatars.com/api/?name=". $user->name . "&color=7F9CF5&background=87e8ff";
    }

    protected function formatAddedAt(Carbon $createdAt): string
    {
        $now = Carbon::now();
        $diffInMinutes = $now->diffInMinutes($createdAt);
        $diffInHours = $now->diffInHours($createdAt);

        if ($diffInHours < 1) {
            return $diffInMinutes . ' m';
        }

        return $diffInHours . ' h';
    }
}
