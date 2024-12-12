<?php

namespace Domain\Auth\Data;

class ResetPasswordData
{
    public function __construct(
        public string $currentPassword,
        public string $password,
    )
    {
    }
}