<?php

namespace Domain\User\Data;

class ProfileDeleteData
{
    public function __construct(
        public string $role,
        public string $user,
        public string $currentPassword,
    ) {
    }
}
