<?php

namespace App\Http\Middleware;

use Closure;
use Support\Helper\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CheckAPIPermission
{
    use Helper;

    public function handle(Request $request, Closure $next): Response
    {
        $currentRouteArray = explode('.', $this->getCurrentRoute()->getName());

        if ($this->checkRouteCanBeAccess(end($currentRouteArray).'-'.$currentRouteArray[1], $request->user())) {
            return $this->responseBack();
        }

        return $next($request);
    }

    protected function responseBack(): JsonResponse
    {
        return response()->json([
            'status' => false,
            'title' => 'Unauthorized Access',
            'message' => __('You don\'t have rights to access this end point with :role role!', ['role' => Str::lower(auth()->user()->roles()->first()->name)]),
        ], 401);
    }

    protected function checkRouteCanBeAccess(
        $needle,
        $user = null
    ): bool {
        return ! in_array($needle, getUserAssignedUniqueDomains('name', $user), true);
    }
}
