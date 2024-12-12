<?php

namespace Domain\API\Authentication\Actions;

use App\Mail\UserProfile;
use App\Models\User;
use Domain\API\Authentication\Data\RegisterData;
use Domain\API\Authentication\Mail\UserRegister;
use Domain\Notification\Actions\StoreNotificationAction;
use Domain\Notification\Data\NotificationData;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use JsonException;
use Spatie\Permission\Models\Role;
use Support\Helper\Helper;

class StoreUserAction
{
    use Helper;

    /**
     * @throws JsonException
     */
    public function execute(
        RegisterData $registerData,
        User         $user = new User(),
        ?string      $method = 'store',
        ?string      $userAgent = 'api',
        ?bool        $viaMobile = false
    ): User
    {
        $password = Str::password(8);

        $user->name = $registerData->name;
        $user->email = $registerData->email;
        $user->login_via = $registerData->via;
        $user->phone = $registerData->phone;
        $user->avatar = $this->saveFile(
            $user,
            $registerData->avatar,
            'avatar',
            'user-details/',
        );
        if ($method === 'store') {
            $user->password = Hash::make($password);
            $user->modified_by = User::first()->id;
            $user->added_by = User::first()->id;
        }

        if ($method === 'update') {
            $user->modified_by = auth()->user()->id;
        }

        $user->save();

        $user->refresh();

        $user->syncRoles($registerData->role);

        $user->syncPermissions(Role::query()->where('name', $registerData->role)->first()->permissions);

        if ($method === 'store') {
            if ($registerData->via === 'email') {
                Mail::to($registerData->email)->send(new UserRegister($registerData, $password));
            } else {
//                Mail::to($registerData->email)->send(new UserRegister($registerData));
            }

            (new StoreNotificationAction())->execute(
                new NotificationData(
                    $user->id,
                    $registerData->role,
                    domainStates('register'),
                    'Welcome to Dream Career',
                    'You\'ve successfully registered to Dream Career'
                )
            );
        } else {
            (new StoreNotificationAction())->execute(
                new NotificationData(
                    $user->id,
                    $registerData->role,
                    domainStates('updated'),
                    'Profile Updated',
                    'You\'ve successfully Updated your profile'
                )
            );
        }

        return $user;
    }
}
