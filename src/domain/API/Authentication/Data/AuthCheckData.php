<?php

namespace Domain\API\Authentication\Data;

class AuthCheckData
{
    public function __construct(
        public ?string $role,
        public ?string $type,
        public string $via,
        public ?string $email,
        public ?string $phone,
    ) {
    }
}
