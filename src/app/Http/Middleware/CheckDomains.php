<?php

namespace App\Http\Middleware;

use Closure;
use Support\Helper\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDomains
{
    use Helper;

    public function handle(Request $request, Closure $next): Response
    {
        $currentRouteName = $this->getCurrentRoute()->getName();

        $domains = [];
        foreach (getUserAssignedUniqueDomains()->get('domains') as $domain) {
            $domains[] = $domain;
        }

//        if ((explode('.', $currentRouteName)[1] === 'settings')) {
//            if (! $this->checkRouteCanBeAccess(1, $currentRouteName, $domains)) {
//                return $this->redirectToDashboard();
//            }
//
//            return $next($request);
//        }

        if (! $this->checkRouteCanBeAccess(1, $currentRouteName, $domains)) {
            return $this->redirectToDashboard();
        }

        return $next($request);
    }

    protected function redirectToDashboard()
    {
        return redirect(route('admin.dashboard'))->withFlash([
            'type' => 'warning',
            'title' => 'Unauthorized Access',
            'message' => __('You don\'t have rights to access this page with :role role!', ['role' => Str::lower(auth()->user()->roles()->first()->display_name)]),
        ]);
    }

    protected function checkRouteCanBeAccess(
        $index,
        $currentRouteName,
        $domains
    ): bool {
        return in_array(explode('.', $currentRouteName)[$index], array_unique($domains), true);
    }
}
