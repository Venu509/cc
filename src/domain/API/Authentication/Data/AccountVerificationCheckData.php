<?php

namespace Domain\API\Authentication\Data;

class AccountVerificationCheckData
{
    public function __construct(
        public string $userId,
    ) {
    }
}
