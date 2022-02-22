@component('mail::message')

@lang('auth.please_follow')

@component('mail::button', ['url' => route('register.verify', ['token' => $user->verify_token])])
    @lang('auth.verify_email')
@endcomponent

@lang('auth.thanks'),<br>
{{ config('app.name') }}
@endcomponent
