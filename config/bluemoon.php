<?php

return [
	// For use with REST api if you're using that
	'rest' => [
		'url' => env('BLUEMOON_CLIENT_URL'),
		'secret' => env('BLUEMOON_CLIENT_SECRET'),
		'id' => env('BLUEMOON_CLIENT_ID'),
		'username' => env('BLUEMOON_USERNAME'),
		'password' => env('BLUEMOON_PASSWORD'),
		'license' => env('BLUEMOON_LICENSE'),
	],
	// For user with SOAP api, so you need this
	'soap' => [
		'url' => env('BLUEMOON_SOAP_CLIENT_URL'),
		'username' => env('BLUEMOON_SOAP_USERNAME'),
		'password' => env('BLUEMOON_SOAP_PASSWORD'),
		'serial' => env('BLUEMOON_SOAP_SERIAL'),
	],
	// For use with in browser application
	'application' => [
		'api_url' => env('BLUEMOON_APPLICATION_API_URL'),
		'application_url' => env('BLUEMOON_APPLICATION_URL'),
		'esignature_url' => env('BLUEMOON_ESIGNATURE_API_URL'),
		'lease_url' => env('BLUEMOON_LEASE_URL'),
		'license' => env('BLUEMOON_LICENSE'),
		'debug' => env('BLUEMOON_DEBUG')
	]
];