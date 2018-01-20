@component('mail::message')
<strong>Hello {{ $token->user->getFirstNameOrUserName() }}!</strong>

Please confirm your email address by clicking the button.

@component('mail::button', [
	'url' => route('activate.account', ['token' => $token,]) . '?email=' . urlencode($token->user->email)
])
Confirm email address
@endcomponent


We may need to send you critical information about our service and it is important that we have an accurate email address.

Thanks,<br>
{{ config('app.name') }}

<hr>
If you’re having trouble clicking the <strong>"Confirm email address"</strong> button, copy and paste the URL below into your web browser:<br>
<small>{{ route('activate.account', ['token' => $token,]) . '?email=' . urlencode($token->user->email) }}</small>

@endcomponent