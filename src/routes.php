<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->group('/breeds', function (){{
  // Get all goat breeds from the database
  $this->get('', 'App\Controllers\BreedController:showBreeds');
  // Get info on one goat breed from the database
  $this->get('/{id}', 'App\Controllers\BreedController:showBreed');
}});

$app->group('/goats', function(){
  // Get list of all goats from the DB
  $this->get('', 'App\Controllers\GoatController:showGoats');
  // GET: Get access to the form to add a goat
  // POST: Add the goat to the DB if its identification isn't already in
  $this->map(['GET', 'POST'], '/add', 'App\Controllers\GoatController:addGoat');
  // Remove a goat from the DB
  $this->post('/remove', 'App\Controllers\GoatController:removeGoat');
  // GET: Get access to the form to modify data on a goat
  // POST: Replace updated goat in the DB
  $this->map(['GET', 'POST'], '/update', 'App\Controllers\GoatController:updateGoat');
  // TODO : Search
  $this->post('/search', 'App\Controllers\GoatController:searchGoat');
  // Get info on one goat
  $this->get('/{id}', 'App\Controllers\GoatController:showGoat');
});

$app->group('/mobile', function(){
  $this->group('/breeds', function (){{
    // Get all goat breeds from the database
    $this->get('', 'App\Mobiles\BreedMobile:showBreeds');
    // Get info on one goat breed from the database
    $this->get('/{id}', 'App\Mobiles\BreedMobile:showBreed');
  }});

  $this->group('/goats', function(){
    // Get list of all goats from the DB
    $this->get('', 'App\Mobiles\GoatMobile:showGoats');
    // GET: Get access to the form to add a goat
    // POST: Add the goat to the DB if its identification isn't already in
    $this->map(['OPTIONS', 'POST'], '/add', 'App\Mobiles\GoatMobile:addGoat');
    // Remove a goat from the DB
    $this->get('/remove/{id}', 'App\Mobiles\GoatMobile:removeGoat');
    // GET: Get access to the form to modify data on a goat
    // POST: Replace updated goat in the DB
    $this->map(['GET', 'POST', 'OPTIONS'], '/update/{id}', 'App\Mobiles\GoatMobile:updateGoat');
    // Get info on one goat
    $this->get('/{id}', 'App\Mobiles\GoatMobile:showGoat');
  });
  $this->get('/image/{id}', 'App\Mobiles\ImageMobile:getImageJsonById');
});

// Success returned if goat successfully added/updated/removed
// Return to the goat list after a littke delay
$app->get('/success', function (Request $request, Response $response) {
  //$this->logger->addInfo("Route /success");
  $this->view->render($response, 'success.twig');
});
// Failure returned if goat successfully added/updated/removed
// Return to the goat list after a littke delay
$app->get('/failure', function (Request $request, Response $response) {
  //$this->logger->addInfo("Route /failure");
  $this->view->render($response, 'failure.twig');
});

// Home page
$app->get('/home', function (Request $request, Response $response) {
  //$this->logger->addInfo("Route /home");
  return $response->withRedirect('/goats');
});

// 404 not found page
$app->get('/404', function (Request $request, Response $response) {
  //$this->logger->addInfo("Route /404");
  $notFoundHandler = $this->notFoundHandler;
  return $notFoundHandler($request, $response);
});

// 405 allowed page
$app->get('/405', function (Request $request, Response $response) {
  //$this->logger->addInfo("Route /405");
  $notAllowedHandler = $this->notAllowedHandler;
  $methods = array();
  return $notAllowedHandler($request, $response, $methods);
});

// Home page
$app->get('/', function (Request $request, Response $response) {
  //$this->logger->addInfo("Route /");
  return $response->withRedirect('/goats');
});
