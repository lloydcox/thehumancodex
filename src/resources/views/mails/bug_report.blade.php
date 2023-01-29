@component('mail::message')
    # Bug is reported

    {{$bug->title}}

    {{$bug->description}}

    {{$image1 != null ? env('APP_URL').'/images/bugs/'.$image1 : ''}}
    {{$image2 != null ? env('APP_URL').'/images/bugs/'.$image2 : ''}}
    {{$image3 != null ? env('APP_URL').'/images/bugs/'.$image3 : ''}}


    Thanks,
    {{ config('app.name') }}
@endcomponent
