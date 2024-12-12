<?php

namespace Domain\Auth\Data;

class LoginData
{
    public function __construct(
        public string $username,
        public string $password,
        public string $intendedRoute,
    )
    {
    }
}