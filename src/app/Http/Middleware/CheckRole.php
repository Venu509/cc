<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $acceptedRoles = ['candidate', 'government', 'company', 'institution'];

        if (!$request->has('role') || !in_array($request->query('role'), $acceptedRoles, true)) {
            throw new NotFoundHttpException();
        }

        return $next($request);
    }
}
