<?php

namespace Domain\Lead\Data;

class LeadData
{
    public function __construct(
        public mixed $id,
        public string $title,
        public string $type,
        public ?string $status,
        public ?string $description,
    )
    {
    }
}