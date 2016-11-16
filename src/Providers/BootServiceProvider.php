<?php
namespace Elfet\Modules\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Support\Facades\Cache;
use Elfet\Modules\Container\ModulesRepository;
use Elfet\Modules\Container\Module;

class BootServiceProvider extends LaravelServiceProvider {
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
	public $defer = false;

    /**
    * Make ModulesRepository and load modules
    * @return
    */
    public function register() {
        $this->app->singleton('modules', function ($app) {
            return new ModulesRepository(null);
        });

        $this->loadModules();
    }

    /**
    * Trying to boot modules from ModulesRepository
    * @return
    */
    public function boot() {
        $this->app->modules->each(function(&$module) {
            $module->boot();
        });
    }

    /**
    * Load modules from cache and add to ModulesRepository.
    * @return
    */
    private function loadModules() {
        if(Cache::has('elfet_modules')) {
            $modules = json_decode(Cache::get('elfet_modules'));

            if($modules && json_last_error() == JSON_ERROR_NONE) {
                foreach ($modules as $item) {
                    $this->app->modules->push((new Module($item)));
                }
            }
        }
    }
}
