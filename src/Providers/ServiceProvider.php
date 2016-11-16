<?php
namespace Elfet\Modules\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Elfet\Modules\Providers\BootServiceProvider;
use Elfet\Modules\Providers\ConsoleServiceProvider;

class ServiceProvider extends LaravelServiceProvider {
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
	public $defer = false;

    /**
     * Register and publish configs.
     */
	public function register() {
		$this->publishes([
            __DIR__ . '/../../config/modules.php' => config_path('modules.php')
        ], 'modules');
	}

    /**
    * Boot the service providers.
    */
	public function boot() {
        $this->loadTranslationsFrom(realpath(__DIR__ . '/../../langs'), 'elfet.modules');

        $this->app->register(BootServiceProvider::class);

        if($this->app->runningInConsole()) {
            $this->app->register(ConsoleServiceProvider::class);
        }
	}

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return ['modules'];
    }
}
