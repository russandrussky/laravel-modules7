<?php
namespace Elfet\Modules\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ConsoleServiceProvider extends LaravelServiceProvider {
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Parent command namespace.
     *
     * @var string
     */
    protected $namespace = 'Elfet\\Modules\\Console\\Commands\\';

    /**
     * The available command shortname.
     *
     * @var array
     */
    protected $commands = [
        'Install',
        'Cache',
        //'Make',
        //'Disable',
        //'Enable',
        //'Delete',
        //'Install'
    ];

	/**
    * Register the commands.
    */
    public function register() {
        foreach ($this->commands as $command) {
            $this->commands($this->namespace.$command.'Command');
        }
    }

    /**
     * @return array
     */
    public function provides() {
        $provides = [];

        foreach ($this->commands as $command) {
            $provides[] = $this->namespace . $command . 'Command';
        }

        return $provides;
    }
}
