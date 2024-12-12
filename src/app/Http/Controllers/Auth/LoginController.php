<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    public function showLoginForm(): InertiaResponse|RedirectResponse
    {
        $role = manageSession();

        if(request()->has('job-id')) {
            $jobId = request()->get('job-id');

            if(!findVacancyById($jobId)) {
                return redirect(route('login'))->withFlash([
                    'type' => 'warning',
                    'title' => 'Job Not Found',
                    'message' => __('Requested Job Not Found'),
                ]);
            }

            session(['applyingJobId' => $jobId]);
        }

        return Inertia::render('Auth/Login', [
            'selectedRole' => $role,
        ]);
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
