@if ($user->socialLogin->count() > 1)

	@component('mail::panel')
		You have following social media accounts linked with us:

		@foreach ($user->socialLogin as $social)
			{{ config("sociallogin.services.{$social->login_service}.name") }}
		@endforeach
	@endcomponent
@endif