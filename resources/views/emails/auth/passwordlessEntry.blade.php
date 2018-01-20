@component('mail::message')
<strong>Hello {{ $token->user->getFirstNameOrUserName() }}!</strong>

Enter magically into your account without entering the password by clicking the button.

@component('mail::button', [
	'url' => route('passwordless.entry', ['token' => $token,]) . '?remember_me=' . $remember_me .'&email=' . urlencode($token->user->email)
])
Enter magically into you account
@endcomponent

Please do not share this email with anyone. If you did not request a magic link for passwordless entry, no further action is required.

Thanks,<br>
{{ config('app.name') }}

<hr>
If you’re having trouble clicking the <strong>"Enter magically into you account"</strong> button, copy and paste the URL below into your web browser:<br>
<small>{{ route('passwordless.entry', ['token' => $token,]) . '?remember_me=' . $remember_me .'&email=' . urlencode($token->user->email) }}</small>

@endcomponent