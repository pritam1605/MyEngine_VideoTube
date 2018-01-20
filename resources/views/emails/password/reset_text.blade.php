Hello {{ $token->user->getFirstNameOrUserName() }}!

You are receiving this email because we received a password reset request for your account.

Reset your password by copying the link in your browser: {{ route('password.reset', ['token' => $token,]) . '?email=' . urlencode($token->user->email) }}

If you did not request a password reset, no further action is required.

Thanks,
{{ config('app.name') }}