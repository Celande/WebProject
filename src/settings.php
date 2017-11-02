<?php
return [
    'settings' => [
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,
        // Eloquence, install: http://laravel.sillo.org/laravel-4-chapitre-34-les-relations-avec-eloquent-2-2/
        // NO NEED FOR ARTISAN & LARAVEL
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            /*
            'read' => [
                'host' => '192.168.1.1',
            ],
            'write' => [
                'host' => '196.168.1.2'
            ],
            */
            //'sticky'    => true, // can read new data just after write // Need to do an Update Route
            'database' => 'web_project',
            'username' => 'root',
            'password' => 'root', // Gardevoir
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
          ],

        // Renderer settings, used with index.phtml
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ]
      ]
    ];
