<?php

namespace Domain\Attendance\Actions;

use Domain\Attendance\Data\AttendanceData;
use Domain\Attendance\Models\Attendance;
use JsonException;

class StoreAttendanceAction
{
    /**
     * @throws JsonException
     */
    public function execute(
        AttendanceData $attendanceData,
        Attendance $attendance
    ): Attendance
    {
        if($attendanceData->isClockIn) {
            $attendance->forceFill([
                'clock_out_at' => now(),
                'clock_out_ip' => request()->ip(),
                'clock_out_coordinates' => json_encode($attendanceData->coordinates, JSON_THROW_ON_ERROR | true),
            ]);
        } else {
            $attendance->forceFill([
                'clock_in_at' => now(),
                'clock_in_ip' => request()->ip(),
                'clock_in_coordinates' => json_encode($attendanceData->coordinates, JSON_THROW_ON_ERROR | true),
            ]);
        }
        $attendance->forceFill([
            'user_id' => auth()->user()->id,
            'modified_by' => auth()->user()->id,
            'added_by' => auth()->user()->id,
        ]);

        $attendance->save();

        $attendance->refresh();

        return $attendance;
    }
}