Hello {{ $token->user->getFirstNameOrUserName() }}!

Enter magically into your account without entering the password by copying the link in your browser: {{ route('passwordless.entry', ['token' => $token,]) . '?remember_me=' . $remember_me .'&email=' . urlencode($token->user->email) }}

Please do not share this email with anyone. If you did not request a magic link for passwordless entry, no further action is required.

Thanks,
{{ config('app.name') }}