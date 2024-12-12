<?php

namespace Domain\Job\Data;

class AssignAppliedStatusToJobData
{
    public function __construct(
        public string $id,
        public int $user,
        public string $role,
        public bool $isApplied,
    ) {
    }
}