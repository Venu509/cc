<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateApiKey
{
    public function handle(Request $request, Closure $next)
    {
        if (! array_key_exists('apikey', $request->header())) {
            return response()->json([
                'message' => 'API Key not defined',
            ], 301);
        }

        if ($request->header()['apikey'][0] !== env('API_KEY')) {
            return response()->json([
                'message' => 'Unauthorized Access',
            ], 403);
        }

        return $next($request);
    }
}
