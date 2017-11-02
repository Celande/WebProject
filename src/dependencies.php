<?php
// DIC configuration

/* http://www.bradcypert.com/building-a-restful-api-in-php-using-slim-eloquent/ */

use Illuminate\Database\Capsule\Manager as Capsule;
use \Illuminate\Database\Schema\Blueprint as Blueprint;

require __DIR__ . '/../src/create_table.php';

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
  $settings = $c->get('settings')['renderer'];
  return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog from settings
$container['logger'] = function ($c) {
  $settings = $c->get('settings')['logger'];
  $logger = new Monolog\Logger($settings['name']);
  $logger->pushProcessor(new Monolog\Processor\UidProcessor());
  $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
  return $logger;
};

// Service factory for the ORM // Eloquence Database
$container['db'] = function ($container) {
  $capsule = new Capsule;

  // Connection to the DB
$capsule->addConnection($container['settings']['db']);

$capsule->setAsGlobal();
$capsule->bootEloquent();

$capsule = createTable($capsule);

$capsule->getContainer()->singleton(
  Illuminate\Contracts\Debug\ExceptionHandler::class,
  App\Exceptions\Handler::class
);

return $capsule;
};

// Twig view
$container['view'] = function ($container) {
  $templates = __DIR__ . '/../templates/';
  $cache = __DIR__ . '/tmp/views/';

  //$view = new Slim\Views\Twig($templates, compact('cache'));

  /* For development */
  $view = new Slim\Views\Twig($templates, array(
    'cache' => false,
));

  // Instantiate and add Slim specific extension
  $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
  $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

  return $view;
};

// Eloquence - Controller of the table
$container[App\Controllers\RaceController::class] = function ($c) {
  $view = $c->get('view');
  $logger = $c->get('logger');
  $table = $c->get('db')->table('race'); // I have 2 tables: goat and race
  return new App\Controllers\RaceController($view, $logger, $table);
};

// Eloquence - Controller of the table
$container[App\Controllers\GoatController::class] = function ($c) {
  $view = $c->get('view');
  $logger = $c->get('logger');
  $table = $c->get('db')->table('goat'); // I have 2 tables: goat and race
  return new App\Controllers\GoatController($view, $logger, $table);
};
