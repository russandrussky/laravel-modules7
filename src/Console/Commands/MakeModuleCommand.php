<?php
namespace Elfet\Modules\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Elfet\Modules\Console\Builders\ModuleBuilder;
use Elfet\Modules\Container\Module;

class MakeModuleCommand extends Command {
  /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make new module.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        $name = $this->ask(trans('elfet.modules::messages.enter_new_module_name'), null);

        if(!$name || !is_string($name)) {
            return $this->error(trans('elfet.modules::messages.module_name_is_not_valid'));
        }

        $force = false;

        if($this->laravel->modules->where('name', $name)->count() > 0) {
            $force = $this->confirm(trans('elfet.modules::messages.module_allready_exists'), false);

            if(!$force) {
                return $this->warn(trans('elfet.modules::messages.canceled'));
            }
        }

        $description = $this->ask(trans('elfet.modules::messages.enter_description'), $name);

        if(!is_string($description)) {
            return $this->error(trans('elfet.modules::messages.description_is_not_valid'));
        }

        $priority  = intval($this->ask(trans('elfet.modules::messages.enter_boot_priority'), 0));
        $enabled   = $this->confirm(trans('elfet.modules::messages.enable_new_module'), false);

        $module = new Module([
            'name' => $name,
            'description' => $description,
            'priority' => $priority,
            'enabled' => $enabled
        ]);

        $generator = new ModuleBuilder($module, $this, $force);

        return $generator->build();
    }
}
