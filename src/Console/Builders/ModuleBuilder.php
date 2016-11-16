<?php
namespace Elfet\Modules\Console\Builders;

use Illuminate\Console\Command as Console;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Elfet\Modules\Container\Module;
use Elfet\Modules\Console\Builders\Builder;

class ModuleGenerator extends Builder {
	/**
     * The module name will created.
     *
     * @var Module
     */
    protected $module;

    /**
     * Force status.
     *
     * @var bool
     */
    protected $force = false;

    /**
     * The laravel console instance.
     *
     * @var Console
     */
    protected $console;

	/**
     * The laravel hmvc config.
     *
     * @var array
     */
    protected $config;

    /**
     * The constructor.
     */
    public function __construct(Module $module, Console $console, $force = false) {
        $this->config = config('modules');
		$this->console = $console;
		$this->module = $module;
		$this->force = $force;
    }

	private function buildSkeleton() {

	}

	private function overrideModule() {
		if($this->console->laravel->files->exists()) {

		}


	}

	public function build() {
		if($force) {
			$this->overrideModule();
		}

        $this->buildSkeleton();

		return $this->console->info('Module "' . $this->module->getName() . '" was successfuly created.');
    }

}
