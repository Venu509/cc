<?php

namespace Domain\API\Authentication\Data;

class ChangePasswordData
{
    public function __construct(
        public string $user,
        public ?string $oldPassword,
        public string $password,
    ) {
    }
}
