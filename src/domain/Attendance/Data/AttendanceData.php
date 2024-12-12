<?php

namespace Domain\Attendance\Data;

class AttendanceData
{
    public function __construct(
        public mixed $id,
        public string $role,
        public bool $isClockIn,
        public array $coordinates,
    ) {
    }
}
