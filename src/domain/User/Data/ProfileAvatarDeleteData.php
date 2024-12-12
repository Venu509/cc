<?php

namespace Domain\User\Data;

class ProfileAvatarDeleteData
{
    public function __construct(
        public string $role,
        public string $user,
    ) {
    }
}
