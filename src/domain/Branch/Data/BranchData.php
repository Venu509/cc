<?php

namespace Domain\Branch\Data;

class BranchData
{
    public function __construct(
        public ?string $id,
        public string $name,
        public string $description,
    ) {
    }
}
