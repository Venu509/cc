<?php

namespace Domain\API\Authentication\Actions;

use Domain\API\Authentication\Data\SendOTPData;
use Exception;
use Domain\API\Authentication\Models\OTP;
use Illuminate\Database\Eloquent\Builder;

class GenerateOPTAction
{
    public function execute(
        SendOTPData $sendOTPData,
        OTP $otp = new OTP()
    ): OTP {
        OTP::query()->when($sendOTPData->via === 'email', function (Builder $builder) use ($sendOTPData) {
            return $builder->where('email', $sendOTPData->email);
        })->when($sendOTPData->via === 'phone', function (Builder $builder) use ($sendOTPData) {
            return $builder->where('phone', $sendOTPData->phone);
        })->delete();

        try {
            $otp->forceFill([
                'code' => str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT),
                'via' => $sendOTPData->via,
                'email' => $sendOTPData->via === 'email' ? $sendOTPData->email : null,
                'phone' => $sendOTPData->via === 'phone' ? $sendOTPData->phone : null,
                'expired_at' => now()->addHours(1),
            ]);
        } catch (Exception $e) {
        }

        $otp->save();

        $otp->refresh();

        return $otp;
    }
}
