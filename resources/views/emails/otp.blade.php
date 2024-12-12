<x-mail::message>
    Hello,

    You have requested a One-Time Password (OTP) for your account. Please use the following code to proceed:

    OTP Code: {{ $otp }}

    If you did not request this OTP, please disregard this message.

    Thank you for using our service.

    Best regards,
    {{ config('app.name') }}
</x-mail::message>
