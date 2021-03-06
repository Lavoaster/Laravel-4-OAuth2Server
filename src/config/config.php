<?php

/* TODO: Expand on descriptions and format them to laravel style */

return [
	'oauth' => [
		'enforce_redirect_uri' => true,  // Should the Redirect URI  always be present in the request
		'enforce_state'        => false, // Should the state always be in the request
		'default_scope'        => null, // If no default is specified and the client doesn't send the scope parameter, the request must fail according to the OAuth2.0 RFC.
	],

	'access_token' => [
		'storage' => 'Lavoaster\OAuth2Server\Storage\Eloquent\AccessToken', // Class that implements the access token interface
		'repository' => 'Lavoaster\OAuth2Server\Repositories\Eloquent\AccessTokenRepository' // Class that implements the access token repository interface
	],

	'refresh_token' => [
		'storage' => 'Lavoaster\OAuth2Server\Storage\Eloquent\RefreshToken', // Class that implements the refresh token interface
		'repository' => 'Lavoaster\OAuth2Server\Repositories\Eloquent\RefreshTokenRepository' // Class that implements the refresh token repository interface
	],

	'authorization_code' => [
		'storage' => 'Lavoaster\OAuth2Server\Storage\Eloquent\AuthorizationCode', // Class that implements the authorization code interface
		'repository' => 'Lavoaster\OAuth2Server\Repositories\Eloquent\AuthorizationCodeRepository' // Class that implements the authorization code repository interface
	],

	'client' => [
		'storage' => 'Lavoaster\OAuth2Server\Storage\Eloquent\Client', // Class that implements the client interface
		'repository' => 'Lavoaster\OAuth2Server\Repositories\Eloquent\ClientRepository' // Class that implements the client repository interface
	],

	'user' => [
		'id' => 'id', // Column that stores the user id
		'table' => 'users', // Table that contains the users
		'identifier' => 'username', // Column to use when identifying users through the credentials grant type
		'storage' => 'User', // Class that implements OAuthUserInterface so the library can query your user table
		'repository' => 'UserRepository'
	]
];