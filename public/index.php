<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Autoload
require __DIR__ .'/../vendor/autoload.php';

// Middleware
require __DIR__ . '/../src/middleware.php';

// Routes: TODO: SET UP routes.php
//require __DIR__ . '/../src/routes.php';

// Models
/* http://www.php-fig.org/psr/psr-4/examples/ */
spl_autoload_register(function($classname) {
  $model_prefix = 'App\\Models\\';

  $model_base_dir = __DIR__ . '/../src/Models/';

  // does the class use the namespace prefix?
  $model_len = strlen($model_prefix);
  if (strncmp($model_prefix, $classname, $model_len) !== 0) {
    // no, move to the next registered autoloader
    return;
  }

  $model_relative_class = substr($classname, $model_len);

  $model_file = $model_base_dir . str_replace('\\', '/', $model_relative_class) . '.php';

  if (file_exists($model_file)) {
    require $model_file;
  }
});

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies => containers
require __DIR__ . '/../src/dependencies.php';

require __DIR__ . '/../src/routes.php';

$app->run();
