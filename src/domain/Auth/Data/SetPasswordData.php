<?php

namespace Domain\Auth\Data;

class SetPasswordData
{
    public function __construct(
        public string $email,
        public string $password,
        public string $token,
    )
    {
    }
}