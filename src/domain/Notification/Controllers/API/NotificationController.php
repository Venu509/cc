<?php

namespace Domain\Notification\Controllers\API;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Notification\Models\Notification;
use Domain\Notification\Actions\ReadNotificationAction;
use Domain\Notification\Actions\ListNotificationsAction;
use Domain\Notification\Resources\NotificationResources;
use Domain\Notification\Requests\ReadNotificationRequest;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(
        ListNotificationsAction $listNotificationsAction
    ): JsonResponse {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'user' => request()->has('user') ? request()->get('user') : null,
            'userType' => request()->has('userType') ? request()->get('userType') : null,
            'domain' => request()->has('domain') ? request()->get('domain') : null,
            'search' => request()->has('search') ? request()->get('search') : null,
            'isRead' => request()->has('isRead') ? request()->get('isRead') : null,
        ];

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetch notifications',
            'notifications' => $listNotificationsAction->execute(true, params: $params),
        ]);
    }

    public function show(
    ): JsonResponse {

        if (!request()->has('id')) {
            return response()->json([
                'status' => false,
                'message' => 'Please provide the id parameter',
            ]);
        }

        $notification = Notification::where('id', request()->get('id'))->first();

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetch notification',
            'notification' => NotificationResources::make($notification),
        ]);
    }

    public function unseen(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => 'New Notifications',
            'notification' => $this->hasNewNotifications(),
        ]);
    }

    public function read(
        ReadNotificationRequest $readNotificationRequest,
        ReadNotificationAction $readNotificationAction
    ): JsonResponse {
        if($readNotificationRequest->data()->type === 'all'){
            $notifications = Notification::query()
                ->where('user_id', Auth::user()->id)
                ->where('is_read', false)
                ->get();
        }else{
            $notifications = Notification::query()
                ->where('id', $readNotificationRequest->data()->id)
                ->get();
        }

        foreach ($notifications as $notification){
            $readNotificationAction->execute(
                $notification
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully changed notification read state',
        ]);
    }

    protected function hasNewNotifications(): int
    {
        return Notification::query()
            ->where('user_id', auth()->user()->id)
            ->where('is_read', false)
            ->count();
    }
}
