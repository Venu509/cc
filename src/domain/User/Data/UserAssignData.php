<?php

namespace Domain\User\Data;

class UserAssignData
{
    public function __construct(
        public string $id,
        public string $role,
        public ?array $region,
        public ?array $area,
        public ?array $city,
        public ?array $topLevelUser,
    ) {
    }
}
