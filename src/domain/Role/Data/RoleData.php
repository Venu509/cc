<?php

namespace Domain\Role\Data;

class RoleData
{
    public function __construct(
        public string $name,
        public string $displayName,
        public array $permissions,
    ) {
    }
}
