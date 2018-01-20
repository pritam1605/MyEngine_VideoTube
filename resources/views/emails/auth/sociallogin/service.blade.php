@component('mail::message')
<strong>Hello {{ $user->getFirstNameOrUserName() }}!</strong>

Your **{{ title_case($service) }}** account was successfully linked with MyEngine.

{{-- @include('emails.auth.socialLogin.partials._linked')
 --}}
Thanks,<br>
{{ config('app.name') }}
@endcomponent