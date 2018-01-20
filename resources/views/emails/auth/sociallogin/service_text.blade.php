Hello {{ $user->getFirstNameOrUserName() }}!

Your {{ title_case($service) }} account was successfully linked with MyEngine.
{{-- @include('emails.auth.socialLogin.partials._linked') --}}
Thanks,
{{ config('app.name') }}