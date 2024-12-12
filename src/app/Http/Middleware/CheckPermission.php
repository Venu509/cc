<?php

namespace App\Http\Middleware;

use Closure;
use Support\Helper\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    use Helper;

    public function handle(Request $request, Closure $next): Response
    {
        $currentRouteArray = explode('.', $this->getCurrentRoute()->getName());

//        if ($currentRouteArray[2] === 'settings') {
//            if ($this->checkRouteCanBeAccess(end($currentRouteArray).'-'.$currentRouteArray[1])) {
//                return $this->redirectToDashboard();
//            }
//
//            return $next($request);
//        }

        if ($this->checkRouteCanBeAccess(end($currentRouteArray).'-'.$currentRouteArray[1])) {
            return $this->redirectToDashboard();
        }

        return $next($request);
    }

    protected function redirectToDashboard(): RedirectResponse
    {
        return redirect(route('admin.dashboard'))->withFlash([
            'type' => 'warning',
            'title' => 'Unauthorized Access',
            'message' => __('You don\'t have rights to access this action with :role role!', ['role' => Str::lower(auth()->user()->roles()->first()->display_name)]),
        ]);
    }

    protected function checkRouteCanBeAccess(
        $needle
    ): bool {
        return ! in_array($needle, getUserAssignedUniqueDomains('name')->get('domains'), true);
    }
}
