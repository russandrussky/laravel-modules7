<?php
namespace Elfet\Modules\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class InstallCommand extends Command {
    /**
       * The console command name.
       *
       * @var string
       */
      protected $name = 'module:install';

      /**
       * The console command description.
       *
       * @var string
       */
      protected $description = 'Install the Laravel Modules package.';

      /**
       * Name of directory that will contain the modules
       *
       * @var string
       */
      protected $directory;

      /**
       * Execute the console command.
       *
       * @return mixed
       */
      public function fire() {
          if(config('modules.paths.root')) {
              $reinstall = $this->confirm(trans('elfet.modules::messages.laravel_package_instaled', [
                  'directory' => str_replace(base_path() . '/', '', config('modules.paths.root'))
              ]));

              if(!$reinstall) {
                  return $this->warn(trans('elfet.modules::messages.canceled'));
              }

              if($this->laravel->files->exists(config('modules.paths.root'))) {
                  $delete = $this->confirm(trans('elfet.modules::messages.do_you_want_delete_directory', [
                      'directory' => str_replace(base_path() . '/', '', config('modules.paths.root'))
                  ]));

                  if($delete && !$this->laravel->files->deleteDirectory(config('modules.paths.root'))) {
                      return $this->error(trans('elfet.modules::messages.not_have_permissions_to_delete_directory', [
                          'directory' => str_replace(base_path() . '/', '', config('modules.paths.root'))
                      ]));
                  }
              }
          }

          $this->directory = $this->ask(trans('elfet.modules::messages.new_modules_directory_name'), 'Modules');

          return $this->makeModulesDirectory();
      }

      protected function makeModulesDirectory() {
          if(!$this->laravel->files->exists($this->directory) && !$this->laravel->files->makeDirectory(base_path($this->directory), 0777)) {
              return $this->error(trans('elfet.modules::messages.not_have_permissions_to_create_directory'));
          }

          return $this->publishConfig();
      }

      protected function publishConfig() {
          $configFile = __DIR__ . '/../../../config/modules.php';
          $defaultContent = $this->laravel->files->get($configFile);
          $content = str_replace('{% DIRECTORY %}', $this->directory, $defaultContent);
          $this->laravel->files->put($configFile, $content);

          $this->callSilent("vendor:publish", ['--tag' => 'modules', '--force']);
          $this->laravel->files->put($configFile, $defaultContent);

          return $this->line(trans('elfet.modules::messages.package_instaled_successfuly'));
      }
}
