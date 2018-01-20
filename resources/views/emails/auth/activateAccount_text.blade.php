Hello {{ $token->user->getFirstNameOrUserName() }}!

Please confirm your email address by copying the link in your browser: {{ route('activate.account', ['token' => $token,]) . '?email=' . urlencode($token->user->email) }}

We may need to send you critical information about our service and it is important that we have an accurate email address.

Thanks,
{{ config('app.name') }}