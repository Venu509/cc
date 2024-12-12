<?php

namespace Domain\Auth\Data;

class ChangePasswordData
{
    public function __construct(
        public string $email,
        public string $currentPassword,
        public string $password,
    )
    {
    }
}