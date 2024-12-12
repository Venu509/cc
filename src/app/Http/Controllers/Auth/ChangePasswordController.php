<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Domain\Auth\Requests\ChangePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ChangePasswordController extends Controller
{


    public function rules():JsonResponse
    {
        return response()->json([
            'status' => true,
            'rules' => (new ChangePasswordRequest())->validations()
        ]);
    }


    public function showChangePasswordForm(): InertiaResponse
    {
        return Inertia::render('Auth/ResetPassword');
    }

    public function changePassword(
        ChangePasswordRequest $changePasswordRequest
    )
    {
        $auth = Auth::user();

        if (!Hash::check($changePasswordRequest->data()->currentPassword, $auth->password)) {
            return redirect()->back()->withFlash([
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Current Password is Invalid',
            ]);
        }

        if (strcmp($changePasswordRequest->data()->currentPassword, $changePasswordRequest->data()->password) === 0) {
            return redirect()->back()->withFlash([
                'type' => 'error',
                'title' => 'Error',
                'message' => 'New Password cannot be the same as your current password',
            ]);
        }

        $user = User::find($auth->id);
        $user->password = Hash::make($changePasswordRequest->data()->password);
        $user->save();

        $role = auth()->user()->roles()->first()->name;

        Auth::guard('web')->logout();


        if(in_array($role, ['government', 'institution', 'candidate', 'company'], true)) {
            return redirect()->route('login');
        }

        if($role === 'marketing') {
            return redirect()->route('marketing.login');
        }

        return redirect()->route('admin.login');

    }
}
