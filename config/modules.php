<?php
return [
    'paths' => [
        'root' => base_path('{% DIRECTORY %}'),
        'directory' => '{% DIRECTORY %}',
        'structure' => [
            'assets' => 'Assets/js/modules',
            'config' => 'Config',
            'migration' => 'Database/Migrations',
            'model' => 'Entities',
            'seeder' => 'Database/Seeders',
            'controller' => 'Http/Controllers',
            'middleware' => 'Http/Middleware',
            'request' => 'Http/Requests',
            'providers' => 'Providers',
            'lang' => 'Resources/lang',
            'views' => 'Resources/views'
		]
	]
];
