<?php

namespace App\Http\Middleware;

use Domain\Attendance\Models\Attendance;
use Domain\Attendance\Resources\AttendanceResources;
use Domain\Notification\Actions\ListNotificationsAction;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Middleware;
use Support\Helper\Helper;

class HandleInertiaRequests extends Middleware
{
    use Helper;
    protected $rootView = 'app';


    public function render($page): \Inertia\Response
    {
        return Inertia::render($page);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'logo' => $this->logo(),
            'logoWhite' => $this->logo('white'),
            'governmentLogo' => asset('images/government.png'),
            'logoIcon' => asset('images/logo-default.png'),
            'selectedRole' => $this->selectedRole(),
            'menus' => menus(),
            'authUser' => auth()->check() ? $this->user() : null,
            'role' => auth()->check() ? auth()->user()->roles[0] : null,
            'attendance' => $this->attendance(),
            'notifications' => $this->notifications(),
            'intendedRoute' => request()->route()->getName(),
        ]);
    }

    private function user(): array
    {
        $user = auth()->user();

        return [
            'id' => $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'avatar' => $this->avatar($user),
        ];
    }

    private function avatar($user): ?string
    {
        if($user->avatar) {
            return imageCheck('user-details/avatars/thumbnail/' . $user->avatar);
        }

        return $user->profile_photo_path ? imageCheck($user->profile_photo_path) : "https://ui-avatars.com/api/?name=". $user->name . "&color=7F9CF5&background=87e8ff";
    }

    private function logo(?string $type = 'default'): string
    {
        return asset('images/logo-'. $type .'.png');
    }

    private function selectedRole(): string
    {
        if(Auth::check()) {
            return auth()->user()->roles()->first()->name;
        }

        return session()->has('selectedRole') ? session('selectedRole', 'government') : 'government';
    }

    private function notifications(): LengthAwarePaginator|Collection
    {
        if(!Auth::check()) {
            return collect([]);
        }

        $params = [
            'user' => auth()->user()->id,
            'limit' => 8,
        ];

        return (new ListNotificationsAction())->execute(params: $params);
    }
}
