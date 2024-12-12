<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Domain\Auth\Requests\SetPasswordRequest;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function showResetForm(): InertiaResponse
    {
        $token = request()->route()->parameter('token');

        return Inertia::render('Auth/SetPassword', [
            'token' => $token,
            'email' => request()->email
        ]);
    }

    public function setPassword(
        SetPasswordRequest $setPasswordRequest
    ): RedirectResponse
    {
        $user = User::where('username', $setPasswordRequest->data()->email)->first();

        $user->password = Hash::make($setPasswordRequest->data()->password);
        $user->save();

        $role = $user->roles()->first()->name;

        if(in_array($role, ['government', 'institution', 'candidate', 'company'], true)) {
            return redirect()->route('login')->withFlash([
                'status' => true,
                'type' => 'info',
                'title' => 'Password Updated',
                'message' => 'Your password has been successfully updated',
            ]);
        }

        if($role === 'marketing') {
            return redirect()->route('marketing.login')->withFlash([
                'status' => true,
                'type' => 'info',
                'title' => 'Password Updated',
                'message' => 'Your password has been successfully updated',
            ]);
        }

        return redirect()->route('admin.login')->withFlash([
            'status' => true,
            'type' => 'info',
            'title' => 'Password Updated',
            'message' => 'Your password has been successfully updated',
        ]);

    }
}
