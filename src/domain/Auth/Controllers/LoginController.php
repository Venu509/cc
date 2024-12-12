<?php

namespace Domain\Auth\Controllers;

use App\Http\Controllers\Controller;
use Domain\Auth\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class LoginController extends Controller
{
    public function showLoginForm(): InertiaResponse
    {
        return Inertia::render('Auth/Login');
    }

    public function rules(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'rules' => (new LoginRequest())->validations()
        ]);
    }

    public function login(
        LoginRequest $loginRequest
    ): RedirectResponse
    {
        $credentials = $loginRequest->only('username', 'password');
        $intendedRoute = $loginRequest->data()->intendedRoute;

        if (Auth::guard('web')->attempt($credentials)) {

            $user = auth()->user();
            $role = $user->roles()->first() ? $user->roles()->first()->name : null;

            $unauthorizedRoles = [
                'login' => ['government', 'institution', 'candidate', 'company'],
                'marketing.login' => ['marketing'],
                'admin.login' => ['master', 'admin', 'super-admin']
            ];

            if (isset($unauthorizedRoles[$intendedRoute]) && !in_array($role, $unauthorizedRoles[$intendedRoute], true)) {
                return $this->unauthorized($intendedRoute);
            }

            $applyingJobId = session()->has('applyingJobId') ? session('applyingJobId') : null;

            if($applyingJobId) {
                session()->forget('applyingJobId');
                return redirect()->route('admin.jobs.show', $applyingJobId);
            }

            return redirect()->route('admin.dashboard');
        }

        return redirect(route($intendedRoute))->withFlash([
            'type' => 'error',
            'title' => 'Invalid credentials',
            'message' => __('Invalid credentials'),
        ]);
    }

    public function logout(): RedirectResponse
    {
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

    private function unauthorized($route)
    {
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect(route($route))->withFlash([
            'type' => 'warning',
            'title' => 'Unauthorized access',
            'message' => __('Please use correct portal for login'),
        ]);
    }
}
