@component('mail::message')
    # Trying To Contact Administrator

    Name: {{$name}}

    Email : {{$email}}

    Message : {{$message}}


    Thanks,
    {{ config('app.name') }}
@endcomponent
