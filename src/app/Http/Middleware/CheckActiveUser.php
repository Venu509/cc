<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (! auth()->user()->is_active) {
            Auth::logout();

            return redirect(route('login'))->withFlash([
                'type' => 'error',
                'title' => 'Unauthorized Access',
                'message' => __("You don't have the right access to access this portal"),
            ]);
        }

        return $next($request);
    }
}
