<x-mail::message>
# Introduction

The body of your message.
<br/>
Login ID: {{ $request->username }}
<br/>
Password: {{ $password }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
