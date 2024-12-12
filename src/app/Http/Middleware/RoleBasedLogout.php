<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleBasedLogout
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && session()->has('session_expired')) {
            $role = Auth::guard('web')->user()->roles()->first()->name;
            Auth::guard('web')->logout();

            return match ($role) {
                'admin' => redirect()->route('admin.login')->withFlash([
                    'type' => 'warning',
                    'title' => 'Session expired',
                    'message' => 'Session expired. Please log in again.',
                ]),
                'marketing' => redirect()->route('marketing.login')->withFlash([
                    'type' => 'warning',
                    'title' => 'Session expired',
                    'message' => 'Session expired. Please log in again.',
                ]),
                default => redirect()->route('login')->withFlash([
                    'type' => 'warning',
                    'title' => 'Session expired',
                    'message' => 'Session expired. Please log in again.',
                ]),
            };
        }

        return $next($request);
    }
}
