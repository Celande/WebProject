<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

// Autoload
require __DIR__ .'/../vendor/autoload.php';

session_start();

// Middleware
require __DIR__ . '/../src/middleware.php';

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

/* TODO : Doesn't work
spl_autoload_register(function ($classname) {
    require __DIR__ . '/../src/Models/' . $classname . '.php';
});
*/

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies => containers
require __DIR__ . '/../src/dependencies.php';

require __DIR__ . '/../src/routes.php';

$app->run();
