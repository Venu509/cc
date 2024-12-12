<?php

namespace Domain\API\Authentication\Data;

class SendOTPData
{
    public function __construct(
        public ?string $type,
        public string $via,
        public ?string $email,
        public ?string $phone,
    ) {
    }
}
