<?php

namespace Domain\Branch\Data;

class BranchDeleteData
{
    public function __construct(
        public ?string $id,
        public int $user,
    ) {
    }
}
