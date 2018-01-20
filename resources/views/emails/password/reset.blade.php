@component('mail::message')
<strong>Hello {{ $token->user->getFirstNameOrUserName() }}!</strong>

You are receiving this email because we received a password reset request for your account.
If you did not request a password reset, no further action is required.

@component('mail::button', [
	'url' => route('password.reset', ['token' => $token,]) . '?email=' . urlencode($token->user->email)
])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}

<hr>
If you’re having trouble clicking the <strong>"Reset Password"</strong> button, copy and paste the URL below into your web browser:<br>
<small>{{ route('password.reset', ['token' => $token,]) . '?email=' . urlencode($token->user->email) }}</small>

@endcomponent