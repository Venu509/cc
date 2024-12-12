<?php

namespace Domain\Auth\Guards;

use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class AdminGuard extends SessionGuard
{
    public function __construct(UserProvider $provider, Request $request = null)
    {
        parent::__construct('admin', $provider, $request);
    }

    protected function hasValidCredentials($user, $credentials): bool
    {
        return $user !== null && $this->provider->validateCredentials($user, $credentials);
    }
}