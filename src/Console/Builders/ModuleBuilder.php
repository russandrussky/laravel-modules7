<?php
namespace Elfet\Modules\Console\Builders;

use Illuminate\Console\Command as Console;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Elfet\Modules\Container\Module;
use Elfet\Modules\Console\Builders\Builder;

class ModuleBuilder extends Builder {
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
		$this->console->getLaravel()->files->makeDirectory($this->config['paths']['root'] . '/' . ucfirst($this->module->name));

        foreach ($this->config['paths']['structure'] as $key => $folder) {
            $this->console->getLaravel()->files->makeDirectory($this->config['paths']['root'] . '/' . ucfirst($this->module->name) . '/' . $folder, 0777, true);
        }
	}

	private function makeFilesFromTemplates() {
        $this->makeServiceProvider();
        $this->makeRoutes();
        $this->makeConfigs();
    }

    private function makeServiceProvider() {
        $template = $this->console->getLaravel()->files->get(__DIR__ . '/../Templates/ModuleServiceProvider.template');
        $template = str_replace('{% MODULE %}', $this->module->name, $template);
		$path = $this->config['paths']['root'] . '/' . ucfirst($this->module->name) . '/' . $this->config['paths']['structure']['providers'] . '/ModuleServiceProvider.php';
        $this->console->getLaravel()->files->put($path, $template);
    }

    private function makeRoutes() {
        $template = $this->console->getLaravel()->files->get(__DIR__ . '/../Templates/routes.template');
        $template = str_replace('{% MODULE %}', $this->module->name, $template);
        $this->console->getLaravel()->files->put($this->config['paths']['root'] . '/' . ucfirst($this->module->name) . '/Http/routes.php', $template);
    }

    private function makeConfigs() {
        $template = $this->console->getLaravel()->files->get(__DIR__ . '/../Templates/config.menu.template');
        $this->console->getLaravel()->files->put($this->config['paths']['root'] . '/' . ucfirst($this->module->name) . '/Config/menu.php', $template);

        $template = $this->console->getLaravel()->files->get(__DIR__ . '/../Templates/config.permissions.template');
        $this->console->getLaravel()->files->put($this->config['paths']['root'] . '/' . ucfirst($this->module->name) . '/Config/permissions.php', $template);
    }

	private function createModuleJson() {
		$path = $this->config['paths']['root'] . '/' . ucfirst($this->module->name);

		$this->console->getLaravel()->files->put($path . '/module.json', json_encode($this->module->toJson(), JSON_PRETTY_PRINT));
	}

	private function overrideModule() {
		if($this->force) {
			$path = $this->config['paths']['root'] . '/' . ucfirst($this->module->name);

			if($this->console->getLaravel()->files->exists($path)) {
				$this->console->getLaravel()->files->deleteDirectory($path);
			}
		}
	}

	public function build() {
		$this->overrideModule();
        $this->buildSkeleton();
		$this->makeFilesFromTemplates();
		$this->createModuleJson();

		$this->console->callSilent("module:cache", []);

		return $this->console->info('Module "' . ucfirst($this->module->name) . '" was successfuly created.');
    }

}
