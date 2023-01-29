@component('mail::message')
# Your password has been reset

Your new password is: **{{ $newPassword }}**

Remember to change it after the first use.
You can do it in your account settings (Settings->Login->Password).

@component('mail::button', ['url' => $loginUrl])
Click here to login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
