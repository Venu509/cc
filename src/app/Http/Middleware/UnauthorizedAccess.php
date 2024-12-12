<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class UnauthorizedAccess
{
    public function handle(Request $request, Closure $next)
    {
        $token = PersonalAccessToken::findToken(request()->bearerToken());

        if (! request()->bearerToken()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized Access',
            ], 401);
        }

        if (! $token) {
            return response()->json([
                'status' => false,
                'message' => 'Session not valid',
            ], 403);
        }

        if (now()->greaterThan($token->expires_at)) {
            PersonalAccessToken::findToken(request()->bearerToken())->delete();

            return response()->json([
                'status' => false,
                'message' => 'Token Expired',
            ], 403);
        }

        return $next($request);
    }
}
