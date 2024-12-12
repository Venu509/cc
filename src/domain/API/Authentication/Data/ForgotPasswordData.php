<?php

namespace Domain\API\Authentication\Data;

class ForgotPasswordData
{
    public function __construct(
        public string $via,
        public ?string $email,
        public ?string $phone,
    ) {
    }
}
