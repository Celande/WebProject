<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/* https://laravel.com/docs/5.5/eloquent#eloquent-model-conventions */

$app->group('/races', function (){{
  // Get all goat races from the database
  $this->get('', 'App\Controllers\RaceController:show_races')->setName('show_races');
  // Get info on one goat race from the database
  $this->get('/{id}', 'App\Controllers\RaceController:show_race');
}});

$app->group('/goats', function(){
  // Get list of all goats from the DB
  $this->get('', 'App\Controllers\GoatController:show_goats');
  // GET: Get access to the form to add a goat
  // POST: Add the goat to the DB if its identification isn't already in
  $this->map(['GET', 'POST'], '/add', 'App\Controllers\GoatController:add_goat');
  // Remove a goat from the DB
  $this->post('/remove', 'App\Controllers\GoatController:remove_goat');
  // GET: Get access to the form to modify data on a goat
  // POST: Replace updated goat in the DB
  $this->map(['GET', 'POST'], '/update', 'App\Controllers\GoatController:update_goat');
  // TODO : Search
  $this->post('/search', 'App\Controllers\GoatController:search_goat');
  // Get info on one goat
  $this->get('/{id}', 'App\Controllers\GoatController:show_goat');
});

// Success returned if goat successfully added/updated/removed
// Return to the goat list after a littke delay
$app->get('/success', function (Request $request, Response $response) {
  $this->logger->addInfo("Route /success");
  $this->view->render($response, 'success.twig');
});
// Failure returned if goat successfully added/updated/removed
// Return to the goat list after a littke delay
$app->get('/failure', function (Request $request, Response $response) {
  $this->logger->addInfo("Route /failure");
  $this->view->render($response, 'failure.twig');
});

// Home page
$app->get('/home', function (Request $request, Response $response) {
  $this->logger->addInfo("Route /home");
  return $this->view->render($response, 'home.twig');
});

// 404 not found page
$app->get('/404', function (Request $request, Response $response) {
  $this->logger->addInfo("Route /404");
  $notFoundHandler = $this->notFoundHandler;
  return $notFoundHandler($request, $response);
});

// 405 allowed page
$app->get('/405', function (Request $request, Response $response) {
  $this->logger->addInfo("Route /405");
  $notAllowedHandler = $this->notAllowedHandler;
  $methods = array();
  return $notAllowedHandler($request, $response, $methods);
});
