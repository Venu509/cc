<?php

namespace Domain\Global\Actions;

use Domain\Attendance\Models\Attendance;
use Stevebauman\Location\Facades\Location;

class AutoClockOutAction
{
    public function execute()
    {
        $ip = request()->ip();
        $location = Location::get($ip);

        $coordinates = [
            'latitude' => $location->latitude ?? null,
            'longitude' => $location->longitude ?? null,
        ];

        return Attendance::query()
            ->whereNull('clock_out_at')
            ->select(['id', 'user_id', 'clock_in_at'])
            ->latest()
            ->get()
            ->each(function ($attendance) use ($coordinates, $ip) {
                $attendance->update([
                    'clock_out_at' => now(),
                    'clock_out_ip' => $ip,
                    'clock_out_coordinates' => json_encode($coordinates, JSON_THROW_ON_ERROR | true),
                ]);
            });
    }
}