<?php

namespace Domain\Skill\Data;

class SkillData
{
    public function __construct(
        public ?string $id,
        public string $title,
    ) {
    }
}
