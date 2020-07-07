<?php
namespace Elfet\Modules\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Cache;

class CacheCommand extends Command {
  /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan and cache all modules.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $path = config('modules.paths.root');

        if($this->laravel->files->exists($path)) {
            $directories = $this->laravel->files->directories($path);

            if(count($directories) > 0) {
                foreach ($directories as $key => $directory) {
                    if($this->laravel->files->exists($directory . '/module.json')) {
                        $module = $this->laravel->files->get($directory . '/module.json');

                        if($module && !empty($module)) {
                            $module = json_decode($module);
                            $this->addModuleToCache($module);
                        }
                    }
                }
            }
        }
    }

    private function addModuleToCache($module) {
        $repository = [];

        if(Cache::has('elfet_modules')) {
            $repository = Cache::get('elfet_modules');
            $repository = json_decode($repository);
        }

        $repository[] = $module;

        Cache::forget('elfet_modules');
        Cache::forever('elfet_modules', json_encode($repository));
    }
}
