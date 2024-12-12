<?php

namespace Domain\API\Authentication\Controllers;

use Exception;
use Throwable;
use App\Models\User;
use Support\Helper\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Builder;
use Domain\Notification\Data\NotificationData;
use Domain\User\Resources\UserProfileResources;
use Domain\API\Authentication\Actions\VerifyOTPAction;
use Domain\API\Authentication\Mail\ForgotPasswordMail;
use Domain\API\Authentication\Requests\VerifyOTPRequest;
use Domain\Notification\Actions\StoreNotificationAction;
use Domain\API\Authentication\Actions\ChangePasswordAction;
use Domain\API\Authentication\Requests\ChangePasswordRequest;
use Domain\API\Authentication\Requests\ForgotPasswordRequest;

class PasswordResetController extends Controller
{
    use Helper;

    /**
     * @throws Exception
     */
    public function forgotPassword(
        ForgotPasswordRequest $forgotPasswordRequest,
    ): JsonResponse {
        $user = User::query()
            ->when($forgotPasswordRequest->via === 'email', function (Builder $builder) use ($forgotPasswordRequest) {
                return $builder->where('email', $forgotPasswordRequest->data()->email);
            })->when($forgotPasswordRequest->via === 'phone', function (Builder $builder) use ($forgotPasswordRequest) {
                return $builder->where('phone', $forgotPasswordRequest->data()->phone);
            })->first();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'Requested record not found',
            ], 422);
        }

        $password = Str::password(8);

        $user->password = Hash::make($password);

        $user->save();

        $user->refresh();

        if ($forgotPasswordRequest->data()->via === 'email') {
            Mail::to($forgotPasswordRequest->data()->email)->send(new ForgotPasswordMail($password));
        } else {
            $credentials = ['phone'];
        }

        return response()->json([
            'status' => true,
            'message' => 'Reset password link sent on your '.$forgotPasswordRequest->data()->via.'.',
            'user' => UserProfileResources::make($user),
        ]);
    }

    public function changePassword(
        ChangePasswordRequest $changePasswordRequest,
        ChangePasswordAction $changePasswordAction
    ): JsonResponse {
        $user = User::find(Auth::user()->id);

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'Requested record not found',
            ], 422);
        }

        try {
            $changePasswordAction->execute(
                $changePasswordRequest->data(),
                $user
            );

            (new StoreNotificationAction())->execute(
                new NotificationData(
                    $user->id,
                    $user->roles()->first()->name,
                    domainStates('updated'),
                    __('Password Changed'),
                    __('You\'re Password has been successfully changed')
                )
            );

            return response()->json([
                'status' => true,
                'message' => 'Password has been successfully changed',
            ]);
        } catch (Throwable $e) {
            return $this->throwable($e);
        }
    }

    public function resetPassword(
        ChangePasswordRequest $changePasswordRequest,
        ChangePasswordAction $changePasswordAction
    ): JsonResponse {
        $user = findUserById($changePasswordRequest->data()->user);

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 422);
        }

        try {
            $changePasswordAction->execute($changePasswordRequest->data(), $user);

            return response()->json([
                'status' => true,
                'message' => 'Password has been successfully reset',
            ]);
        } catch (Throwable $e) {
            return $this->throwable($e);
        }
    }
}
