<?php
namespace Elfet\Modules\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrateCommand extends Command {
  /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'module:migrate {module}';
    //protected $name = 'module:make-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration file';

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle() {
        // Handle the command
        $name = $this->argument('name');
        $module = $this->argument('module');


    }
}
