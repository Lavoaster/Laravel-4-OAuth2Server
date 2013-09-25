<?php

class AuthorizeRequestTest extends PHPUnit_Framework_TestCase
{
	public function testValidateRequiredParamsReturnsFalseWhenResponseTypeIsNotCode()
	{
		$class = $this->getClass($this->getRequest(['response_type' => null]), $this->getConfig());

		$this->assertFalse($class->validateRequiredParams());
	}

	public function testValidateRequiredParamsReturnsFalseWhenClientIdMissing()
	{
		$class = $this->getClass($this->getRequest(['client_id' => null]), $this->getConfig());

		$this->assertFalse($class->validateRequiredParams());
	}

	public function testValidateRequiredParamsReturnsFalseWhenRedirectUriIsMissingAndIsEnforced()
	{
		$class = $this->getClass($this->getRequest(['redirect_uri' => null]), $this->getConfig());

		$this->assertFalse($class->validateRequiredParams());
	}

	public function testValidateRequiredParamsReturnsFalseWhenScopeIsMissingAndDefaultIsNull()
	{
		$class = $this->getClass($this->getRequest(['scope' => null]), $this->getConfig());

		$this->assertFalse($class->validateRequiredParams());
	}

	public function testValidateRequiredParamsReturnsFalseWhenStateIsMissingAndEnforced()
	{
		$class = $this->getClass($this->getRequest(['state' => null]), $this->getConfig(true, true));

		$this->assertFalse($class->validateRequiredParams());
	}

	public function testValidateRequiredParamsReturnsTrueWhenRedirectUriIsMissingAndIsNotEnforced()
	{
		$class = $this->getClass($this->getRequest(['redirect_uri' => null]), $this->getConfig(false));

		$this->assertTrue($class->validateRequiredParams());
	}

	public function testValidateUriCallsClientInterfaceAndFails()
	{
		$client = $this->getClient();
		$client->shouldReceive('checkRedirectUri')->once()->andReturn(false);

		$class = $this->getClass($this->getRequest(), $this->getConfig());

		$this->assertFalse($class->validateRedirectUri('http://localhost', $client));
	}

	// TODO: Grammar fix, needs this does. HA, See what I did there?
	public function testValidateScopeFailsWhenScopeIsNotSupportedForClient()
	{
		$client = $this->getClient();
		$client->shouldReceive('hasScopes')->with(['user'])->andReturn(false);
		$class = $this->getClass($this->getRequest(['scope']), $this->getConfig());

		$this->assertFalse($class->validateScope('user', $client));
	}

	public function testErrorIsSetWhenSomethingFails()
	{
		$error = [
			'error' => 'invalid_request',
			'error_description' => 'response_type parameter MUST be set to code',
			'error_uri' => 'http://tools.ietf.org/html/rfc6749#section-4.1.1'
		];

		$class = $this->getClass($this->getRequest(['response_type' => 'notcode']), $this->getConfig());

		$this->assertFalse($class->validateRequest());
		$this->assertEqualsArrays($error, $class->getError(), 'Error wasn\'t set properly');
	}

	/*****************************************HELPER METHODS**************************************************/

	protected function getClass(array $request, array $config)
	{
		return new Lavoaster\OAuth2Server\Request\AuthorizationRequest($request, $config,
			Mockery::mock('Lavoaster\OAuth2Server\Repositories\AuthorizationCodeRepositoryInterface'),
			Mockery::mock('Lavoaster\OAuth2Server\Repositories\ClientRepositoryInterface')
		);
	}

	protected function getRequest(array $request = [])
	{
		$default = [
			'response_type' => 'code',
			'client_id'     => '1',
			'redirect_uri'  => 'http://localhost/',
			'scope'         => 'user',
			'state'         => 'login',
		];

		return array_merge($default, $request);
	}

	protected function getConfig($uri = true, $state = false, $scope = null)
	{
		return [
			'oauth' => [
				'enforce_redirect_uri' => $uri,  // Should the Redirect URI  always be present in the request
				'enforce_state'        => $state, // Should the state always be in the request
				'default_scope'        => $scope, // If no default is specified and the client doesn't send the scope parameter, the request must fail according to the OAuth2.0 RFC.
			]
		];
	}

	protected function getClient()
	{
		return Mockery::mock('Lavoaster\OAuth2Server\Storage\ClientInterface');
	}

	protected function assertEqualsArrays($expected, $actual, $message) {
		$this->assertTrue(count($expected) == count(array_intersect($expected, $actual)), $message);
	}
}