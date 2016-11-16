<?php
namespace Elfet\Modules\Container;

use Illuminate\Support\Facades\Validator;
use App;

class Module {
    protected $name;
    protected $description;
    protected $priority;
    protected $provider = '';
    protected $enabled;
    protected $booted = false;

    public function __construct(Array $args) {
        $validation = Validator::make($args, [
            'name'        => 'required|string',
            'description' => 'string',
            'priority'    => 'required|integer',
            'enabled'     => 'required|boolean'
        ]);

        if($validate->fails()) {
            $message = current($validate->messages()->toArray());
            throw new InvalidArgumentException($message[0]);
        }

        $this->name        = $args['name'];
        $this->description = $args['description'];
        $this->priority    = $args['priority'];
        $this->enabled     = $args['enabled'];
        $this->provider    = (isset($args['provider'])) ? $args['provider'] : $this->generateProviderNamespace();
    }

    public function boot() {
        if($this->enabled && !$this->booted) {
            $this->booted = true;
            App::register($this->provider);
		}
    }

    private function generateProviderNamespace() {
        $modulesPath = config('modules.paths.root', 'Modules');
        $providersPath = config('modules.paths.structure.providers', 'Providers');

        $path = [
            $modulesPath,
            $this->name,
            $providersPath,
            'ModulesServiceProvider'
        ];

        return implode('\\', $path);
    }

    public function toJson() {
        return json_encode([
            'name'        => $this->name,
            'description' => $this->description,
            'priority'    => $this->priority,
            'enabled'     => $this->enabled,
            'provider'    => $this->provider
        ]);
    }
}
