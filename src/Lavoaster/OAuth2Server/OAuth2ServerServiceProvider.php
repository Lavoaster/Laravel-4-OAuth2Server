<?php namespace Lavoaster\OAuth2Server;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class OAuth2ServerServiceProvider extends ServiceProvider {
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('lavoaster/oauth2server', 'lavoaster/oauth2server');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerRepositories();
		$this->registerStorage();
		$this->registerOAuth();
	}

	protected function registerRepositories()
	{
		$this->app['oauth2server.repositories.access_token'] = $this->app->share(function(Application $app)
		{
			$app->singleton(
				'Lavoaster\OAuth2Server\Repositories\AccessTokenRepositoryInterface',
				$app['config']['lavoaster/oauth2server::access_token.repository']
			);

			return $app->make('Lavoaster\OAuth2Server\Repositories\AccessTokenRepositoryInterface');
		});

		$this->app['oauth2server.repositories.refresh_token'] = $this->app->share(function(Application $app)
		{
			$app->singleton(
				'Lavoaster\OAuth2Server\Repositories\RefreshTokenRepositoryInterface',
				$app['config']['lavoaster/oauth2server::refresh_token.repository']
			);

			return $app->make('Lavoaster\OAuth2Server\Repositories\RefreshTokenRepositoryInterface');
		});

		$this->app['oauth2server.repositories.client'] = $this->app->share(function(Application $app)
		{
			$app->singleton(
				'Lavoaster\OAuth2Server\Repositories\AccessTokenRepositoryInterface',
				$app['config']['lavoaster/oauth2server::client.repository']
			);

			return $app->make('Lavoaster\OAuth2Server\Repositories\AccessTokenRepositoryInterfac');
		});

		$this->app['oauth2server.repositories.authorization_code'] = $this->app->share(function(Application $app)
		{
			$app->singleton(
				'Lavoaster\OAuth2Server\Repositories\AuthorizationCodeRepositoryInterface',
				$app['config']['lavoaster/oauth2server::authorization_code.repository']
			);

			return $app->make('Lavoaster\OAuth2Server\Repositories\AuthorizationCodeRepositoryInterface');
		});
	}

	protected function registerStorage()
	{
		$this->app['oauth2server.storage.access_token'] = $this->app->share(function(Application $app)
		{
			$app->singleton(
				'Lavoaster\OAuth2Server\Storage\AccessTokenInterface',
				$app['config']['lavoaster/oauth2server::access_token.storage']
			);

			return $app->make('Lavoaster\OAuth2Server\Storage\AccessTokenInterface');
		});

		$this->app['oauth2server.storage.refresh_token'] = $this->app->share(function(Application $app)
		{
			$app->singleton(
				'Lavoaster\OAuth2Server\Storage\RefreshTokenInterface',
				$app['config']['lavoaster/oauth2server::refresh_token.storage']
			);

			return $app->make('Lavoaster\OAuth2Server\Storage\RefreshTokenInterface');
		});

		$this->app['oauth2server.storage.client'] = $this->app->share(function(Application $app)
		{
			$app->singleton(
				'Lavoaster\OAuth2Server\Storage\AccessTokenInterface',
				$app['config']['lavoaster/oauth2server::client.storage']
			);

			return $app->make('Lavoaster\OAuth2Server\Storage\AccessTokenInterface');
		});

		$this->app['oauth2server.storage.authorization_code'] = $this->app->share(function(Application $app)
		{
			$app->singleton(
				'Lavoaster\OAuth2Server\Storage\AuthorizationCodeInterface',
				$app['config']['lavoaster/oauth2server::authorization_code.storage']
			);

			return $app->make('Lavoaster\OAuth2Server\Storage\AuthorizationCodeInterface');
		});
	}

	protected function registerOAuth()
	{
		$this->app['oauth2server'] = $this->app->share(function(Application $app)
		{
			return $app->make('Lavoaster\OAuth2Server\OAuth2Server');
		});
	}
}