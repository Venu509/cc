<?php

namespace Domain\API\Authentication\Actions;

use App\Mail\SendOTP;
use Domain\API\Authentication\Data\SendOTPData;
use Illuminate\Support\Facades\Mail;

class SendOTPAction
{
    public function execute(
        SendOTPData $sendOTPData
    ): string
    {
        $otp = (new GenerateOPTAction())->execute($sendOTPData);

        if ($sendOTPData->via === 'email') {
            Mail::to($sendOTPData->email)->send(new SendOTP($otp->code));
        }

        return $otp->code;
    }
}
