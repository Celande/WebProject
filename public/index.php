<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ .'/../vendor/autoload.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

/* Class */
/* http://www.php-fig.org/psr/psr-4/examples/ */
spl_autoload_register(function ($classname) {
  // project-specific namespace prefix
  $model_prefix = 'App\\Models\\';

  // base directory for the namespace prefix
  $model_base_dir = __DIR__ . '/../src/Models/';

  // does the class use the namespace prefix?
  $model_len = strlen($model_prefix);
  if (strncmp($model_prefix, $classname, $model_len) !== 0) {
    // no, move to the next registered autoloader
    return;
  }

  // get the relative class name
  $model_relative_class = substr($classname, $model_len);

  // replace the namespace prefix with the base directory, replace namespace
  // separators with directory separators in the relative class name, append
  // with .php
  $model_file = $model_base_dir . str_replace('\\', '/', $model_relative_class) . '.php';

  // if the file exists, require it
  if (file_exists($model_file)) {
    require $model_file;
  }
});

// Instantiate the app

$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies => containers
require_once __DIR__ . '/../src/dependencies.php';

/* Database */
//$this->db; // establish db conncection

/** Routes **/

$app->get('/hello/{name}', function (Request $request, Response $response) {
  $this->logger->addInfo("Route /hello/{name}");
  $name = $request->getAttribute('name');

  echo $name;

  return $this->view->render($response, 'home.twig', [
    'name' => $name
  ]);
});

/* https://laravel.com/docs/5.5/eloquent#eloquent-model-conventions */

/* Get all goat races from the database */
$app->get('/races', 'App\Controllers\RaceController:show_races')->setName('show_races');
/* Get info on one goat race from the database */
$app->get('/races/{id}', 'App\Controllers\RaceController:show_race');


$app->get('/goats', 'App\Controllers\GoatController:show_goats');

//$app->get('/goats/add', 'App\Controllers\GoatController:add_goat');
//$app->post('/goats/add', 'App\Controllers\GoatController:adding_goat');

$app->map(['GET', 'POST'], '/goats/add', 'App\Controllers\GoatController:add_goat');

$app->get('/goats/remove', 'App\Controllers\GoatController:remove_goat');
$app->post('/goats/removing', 'App\Controllers\GoatController:removing_goat');

$app->get('/goats/search', 'App\Controllers\GoatController:search_goat');
$app->post('/goats/searching', 'App\Controllers\GoatController:searching_goat');

$app->get('/goats/update', 'App\Controllers\GoatController:update_goat');

$app->get('/goats/{id}', 'App\Controllers\GoatController:show_goat');

$app->get('/404', function (Request $request, Response $response) {
  $this->logger->addInfo("Route /404");
  return $this->view->render($response, 'not_found.twig');
});

$app->get('/success', function (Request $request, Response $response) {
  $this->logger->addInfo("Route /success");
  $this->view->render($response, 'success.twig');
  //sleep(3);
  //return $response->withRedirect('/home');
});

$app->get('/home', function (Request $request, Response $response) {
  $this->logger->addInfo("Route /home");
  return $this->view->render($response, 'home.twig');
});

$app->run();
