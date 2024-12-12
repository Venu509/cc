<?php

namespace Domain\API\Authentication\Actions;

use Domain\API\Authentication\Data\RegisterData;
use Domain\API\Authentication\Data\VerifyOTPData;
use Domain\API\Authentication\Models\OTP;
use Domain\Global\Data\EmployeeData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class VerifyRegisterOTPAction
{
    public function execute(
        VerifyOTPData $verifyOTPData
    ): Collection
    {
        $otpBuilder = $this->otpBuilder($verifyOTPData);

        if (!$otpBuilder->exists()) {
            return collect([
                'status' => false,
                'message' => 'OTP not found'
            ]);
        }

        if($otpBuilder->where('expired_at', '>=', now())->doesntExist()) {
            return collect([
                'status' => false,
                'message' => 'OTP expired! Please re-generate new OTP'
            ]);
        }

        if ($otpBuilder->where('expired_at', '>=', now())->first()->code !== $verifyOTPData->code) {
            return collect([
                'status' => false,
                'message' => __("We're unable to verified your OTP with your :via (:provider)", [
                    'via' => $verifyOTPData->via,
                    'provider' => $verifyOTPData->via === 'email' ? $verifyOTPData->email : $verifyOTPData->phone,
                ])
            ]);
        }

        $otpBuilder->first()->forceFill([
            'is_verified' => true
        ])->save();

        return collect([
            'status' => true,
            'message' => __("We've verified your OTP with your :via (:provider)", [
                'via' => $verifyOTPData->via,
                'provider' => $verifyOTPData->via === 'email' ? $verifyOTPData->email : $verifyOTPData->phone,
            ])
        ]);
    }

    public function otpBuilder(VerifyOTPData|RegisterData $verifyOTPData): Builder
    {
        return OTP::query()
            ->when($verifyOTPData->via === 'email', function (Builder $builder) use ($verifyOTPData) {
                return $builder->where('email', $verifyOTPData->email);
            })
            ->when($verifyOTPData->via === 'phone', function (Builder $builder) use ($verifyOTPData) {
                return $builder->where('phone', $verifyOTPData->phone);
            });
    }
}
