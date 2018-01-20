<?php

	return [
		'buckets' => [
			'videos' => env('S3_BUCKET_VIDEOS'),
			'images' => env('S3_BUCKET_IMAGES'),
		],

		'cdn' => [
			'cloudfront' => [
				'images' => env('CLOUDFRONT_IMAGES'),
				'videos' => env('CLOUDFRONT_VIDEOS'),
			]
		]
	];