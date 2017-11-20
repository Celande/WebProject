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
require_once __DIR__ . '/../src/dependencies.php';

/*
// Middleware
$app->add(function($request, $response, $next) {
    $response = $next($request, $response);
    $response = $response->withAddedHeader('Content-Type', 'image/jpeg .jpeg .jpg .jpe .JPG');
    $response = $response->withAddedHeader('Content-Type', 'text/html .html .htm');
    return $response;
});


$app->group('', function () {
    //json routes
})->add(function($request, $response, $next) {
        $response = $next($request, $response);
        return $response->withHeader('Content-Type', 'text/html .html .htm');
});

$app->group('', function () {
    //json routes
})->add(function($request, $response, $next) {
        $response = $next($request, $response);
        return $response->withHeader('Content-Type', 'image/jpeg .jpeg .jpg .jpe .JPG');
});
*/

/* https://laravel.com/docs/5.5/eloquent#eloquent-model-conventions */

// Get all goat races from the database
$app->get('/races', 'App\Controllers\RaceController:show_races')->setName('show_races');
// Get info on one goat race from the database
$app->get('/races/{id}', 'App\Controllers\RaceController:show_race');

// Get list of all goats from the DB
$app->get('/goats', 'App\Controllers\GoatController:show_goats');
// GET: Get access to the form to add a goat
// POST: Add the goat to the DB if its identification isn't already in
$app->map(['GET', 'POST'], '/goats/add', 'App\Controllers\GoatController:add_goat');
// Remove a goat from the DB
$app->post('/goats/remove', 'App\Controllers\GoatController:remove_goat');
// GET: Get access to the form to modify data on a goat
// POST: Replace updated goat in the DB
$app->map(['GET', 'POST'], '/goats/update', 'App\Controllers\GoatController:update_goat');
// TODO : Search
$app->get('/goats/search', 'App\Controllers\GoatController:search_goat');
$app->post('/goats/searching', 'App\Controllers\GoatController:searching_goat');
// Get info on one goat
$app->get('/goats/{id}', 'App\Controllers\GoatController:show_goat');

// Success returned if goat successfully added/updated/removed
// Return to the goat list after a littke delay
$app->get('/success', function (Request $request, Response $response) {
  $this->logger->addInfo("Route /success");
  $this->view->render($response, 'success.twig');
  //sleep(3);
  //return $response->withRedirect('/home');
});
// Failure returned if goat successfully added/updated/removed
// Return to the goat list after a littke delay
$app->get('/failure', function (Request $request, Response $response) {
  $this->logger->addInfo("Route /failure");
  $this->view->render($response, 'failure.twig');
  //sleep(3);
  //return $response->withRedirect('/home');
});

// Home page
$app->get('/home', function (Request $request, Response $response) {
  $this->logger->addInfo("Route /home");
  return $this->view->render($response, 'home.twig');
});

// 404 not found page
$app->get('/404', function (Request $request, Response $response) {
  $this->logger->addInfo("Route /404");
  $notFoundHandler = $this->container->get('notFoundHandler');
  return $notFoundHandler($request, $response);
});
/*
$app->get('/img/race', function(Request $request, Response $response){
    //$data = $args['data'];
    $image = @file_get_contents("race1.jpg");
    if($image === FALSE) {
        $response->write('Could not find test.jpg.');
        //$response->withStatus(404);
        return $this->view->render($response, 'home.twig');
    }

    $response->write($image);
    $response->withHeader('Content-Type', 'image/jpeg');

    return $this->view->render($response, 'home.twig');
  });
*/
/*
$app->group('/img', function(Request $request, Response $response){
  $this->get('/race/{data:\w+}', function($request, $response){
    $data = $args['data'];
    $image = @file_get_contents("$data");
    if($image === FALSE) {
        $handler = $this->notFoundHandler;
        return $handler($request, $response);
    }

    $response->write($image);
    return $response->withHeader('Content-Type', FILEINFO_MIME_TYPE);
  });
  $this->get('/goat/{data:\w+}', function($request, $response){

  });
});
*/
$app->run();
