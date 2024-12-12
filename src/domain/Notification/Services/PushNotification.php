<?php

namespace Domain\Notification\Services;

use Illuminate\Support\Facades\Http;
use Domain\DeviceToken\Models\DeviceToken;
use Domain\Notification\Models\Notification;
use JsonException;

class PushNotification
{
    /**
     * @throws JsonException
     */
    public function execute(
        Notification $notification,
        ?bool $passExtraData = false
    ): void {
        $deviceTokens = DeviceToken::query()->where('user_id', $notification->user_id)->get();

        foreach ($deviceTokens as $token) {
            $deviceToken = $token->device_token;

            $fields = [
                'to' => $deviceToken,
                'notification' => [
                    'body' => $notification->message,
                    'title' => $notification->title,
                    'icon' => asset('images/logo-default.png'),
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ],
                'data' => [
                    'type' => $notification->type,
                    'id' => $notification->id,
                    'data' => $passExtraData ? json_decode($notification->data, true, 512, JSON_THROW_ON_ERROR) : [],
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ],
            ];

            $headers = [
                'Authorization' => 'key='.env('FIREBASE_SERVER_KEY'),
                'Content-Type' => 'application/json',
            ];

            $response = Http::withHeaders($headers)->post(env('FIREBASE_COULD_MESSAGE_ENDPOINT'), $fields);
            $response->body();
        }
    }
}
