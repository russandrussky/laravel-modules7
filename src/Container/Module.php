<?php
namespace Elfet\Modules\Container;

use Illuminate\Support\Facades\Validator;
use App;

class Module {
    public $name;
    public $description;
    public $priority;
    public $provider = '';
    public $enabled;
    public $booted = false;

    public function __construct(Array $args) {
        $validation = Validator::make($args, [
            'name'        => 'required|string',
            'description' => 'string',
            'priority'    => 'required|integer',
            'enabled'     => 'required|boolean'
        ]);

        if($validation->fails()) {
            $message = current($validation->messages()->toArray());
            throw new \InvalidArgumentException($message[0]);
        }

        $this->name        = $args['name'];
        $this->description = $args['description'];
        $this->priority    = $args['priority'];
        $this->enabled     = $args['enabled'];
        $this->provider    = (isset($args['provider'])) ? $args['provider'] : $this->generateProviderNamespace();
    }

    public function boot() {
        if($this->enabled && !$this->booted && class_exists($this->provider)) {
            $this->booted = true;
            App::register($this->provider);
		}
    }

    private function generateProviderNamespace() {
        $modulesPath = config('modules.paths.directory', 'Modules');
        $providersPath = config('modules.paths.structure.providers', 'Providers');

        $path = [
            $modulesPath,
            $this->name,
            $providersPath,
            'ModuleServiceProvider'
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
        ], JSON_PRETTY_PRINT);
    }
}
