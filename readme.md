# BlueMoonRestWrapper

Wrapper around the Bluemoon REST API for Laravel.

## Installation

### Via Composer

``` bash
$ composer require primitivesocial/bluemoonwrapper
```

### Via composer.json file for git repo
``` json
"repositories" : [
  ...,
  {
    "type": "package",
    "package": {
      "name": "primitivesocial/bluemoonrestwrapper",
      "version": "0.0.1",
      "source": {
        "type" : "git",
        "url" : "git@github.com:PrimitiveSocial/bluemoon-rest-wrapper.git",
        "reference" : "0.0.1"
      },
      "dist": {
        "url": "https://github.com/PrimitiveSocial/bluemoon-rest-wrapper/archive/master.zip",
        "type": "zip"
      }
    }
  }
]
```

### Env vars
``` bash
# REST API VARS, THESE ARE REQUIRED
BLUEMOON_CLIENT_URL=
BLUEMOON_CLIENT_SECRET=
BLUEMOON_CLIENT_ID=
BLUEMOON_USERNAME=
BLUEMOON_PASSWORD=
BLUEMOON_LICENSE=

# SOAP API VARS
BLUEMOON_SOAP_CLIENT_URL=
BLUEMOON_SOAP_USERNAME=
BLUEMOON_SOAP_PASSWORD=
BLUEMOON_SOAP_SERIAL=

# BROWSER APPLICATION
BLUEMOON_APPLICATION_URL=
BLUEMOON_LEASE_URL=
BLUEMOON_APPLICATION_API_URL=
BLUEMOON_ESIGNATURE_API_URL=
BLUEMOON_DEBUG=true
```

You must add a config file called `bluemoon.php`. The package comes with one for all three Bluemoon setups out of the box.


``` php
<?php

return [
	// For use with REST api, so you need this
	'rest' => [
		'url' => env('BLUEMOON_CLIENT_URL'),
		'secret' => env('BLUEMOON_CLIENT_SECRET'),
		'id' => env('BLUEMOON_CLIENT_ID'),
		'username' => env('BLUEMOON_USERNAME'),
		'password' => env('BLUEMOON_PASSWORD'),
		'license' => env('BLUEMOON_LICENSE'),
	],
	// For user with SOAP api if you're using that
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
```

You can also install the config by running `php artisan vendor:publish`.

## Usage

### Create New Wrapper

The wrapper takes six variables. You can store these in the config or set these in this order:
`clientLicense`: The Bluemoon property license.
`clientUrl`: The Bluemoon API URL
`clientSecret`: The Bluemoon API Secret
`clientId`: The Bluemoon API ID
`clientUsername`: The Bluemoon API username
`clientPassword`: The Bluemoon API password

``` php
$bm = new BlueMoonWrapper($licenseNumber);
```

### Setters
`setPropertyId($id)`: Bluemoon Property ID of the property you are working with
`setExternalId($id)`: For use with an external identifying ID. Helpful for tracking applications or leases in your app.
`setApplicationId($id)`: ID of application in Bluemoon

### Getters
`getApplications`: Gets all Bluemoon applications
`getApplication`: Gets Bluemoon application with ID set in `setApplicationId`
`getApplicationAndParse`: Gets Bluemoon application with ID set in `setApplicationId` and parses into it's various categories (i.e. pets, vehicles)
`getApplicationFields`: Gets all fields associated with applications for the property set in `setPropertyId`
`getLeaseFields`: Gets all fields associated with leases for the property set in `setPropertyId`
`getToken`: Gets bearer token