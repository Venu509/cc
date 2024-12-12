<x-mail::message>
# Hello!

You are receiving this email because we received a password reset request for your account. <br/>

<x-mail::button url="{{ $link }}">
    Reset Your Password
</x-mail::button>

This password reset link will expire in 60 minutes. <br/>

If you did not request a password reset, no further action is required. <br/>

If you're having trouble clicking the "Reset Your Password" button, copy and paste the URL below into your web browser: {{ $link }} <br/>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>