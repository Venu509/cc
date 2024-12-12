<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveAPIUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()->is_active) {
            PersonalAccessToken::findToken(request()->bearerToken())->delete();

            return response()->json([
                'status' => false,
                'title' => 'Unauthorized Access',
                'message' => __("You don't have the right access to access this portal"),
            ], 401);
        }

        return $next($request);
    }
}
