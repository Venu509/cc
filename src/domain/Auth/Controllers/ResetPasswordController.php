<?php

namespace Domain\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Domain\Auth\Requests\ResetPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ResetPasswordController extends Controller
{
    public function show(): InertiaResponse
    {
        return Inertia::render('Auth/ResetPassword');
    }

    public function update(
        ResetPasswordRequest $resetPasswordRequest
    ): RedirectResponse
    {
        if(Auth::check()) {
            if (!Hash::check($resetPasswordRequest->data()->currentPassword, auth()->user()->password)) {
                return redirect(route('admin.login'))->withFlash([
                    'status' => false,
                    'type' => 'error',
                    'message' => 'Current Password is Invalid',
                ]);
            }

            if (strcmp($resetPasswordRequest->data()->currentPassword, $resetPasswordRequest->data()->password) == 0) {
                return redirect(route('admin.login'))->withFlash([
                    'status' => false,
                    'type' => 'error',
                    'message' => 'New Password cannot be the same as your current password',
                ]);
            }

            $admin = User::find(auth()->user()->id);
            $admin->password = Hash::make($resetPasswordRequest->data()->password);
            $admin->save();

            return redirect(route('admin.login'))->withFlash([
                'status' => true,
                'type' => 'info',
                'title' => 'Password Updated',
                'message' => 'Your password has been successfully updated',
            ]);
        }

        return redirect(route('admin.login'))->withFlash([
            'type' => 'error',
            'title' => 'Unauthorized access',
            'message' => __('Unauthorized access'),
        ]);
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}