<?php

return [
	'services' => [
		'github' => [
			'name' => 'Github',
			'events' => [
				'created' => \App\Events\SocialLogin\GithubLoginAccountWasLinked::class,
			]
		],

		'twitter' => [
			'name' => 'Twitter',
			'events' => [
				'created' => \App\Events\SocialLogin\TwitterLoginAccountWasLinked::class,
			]
		],

		'facebook' => [
			'name' => 'Facebook',
			'events' => [
				'created' => \App\Events\SocialLogin\FacebookLoginAccountWasLinked::class,
			]
		],

		'google' => [
			'name' => 'Google',
			'events' => [
				'created' => \App\Events\SocialLogin\GoogleLoginAccountWasLinked::class,
			]
		],
	]
];