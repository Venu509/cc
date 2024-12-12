<?php

namespace Domain\Auth\Data;

class ForgotPasswordData
{
    public function __construct(
        public string $username,
    ) {
    }
}
