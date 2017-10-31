<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
    //return $this->view->render($response, 'home.twig');
    //return $this->renderer->render($response, 'home.twig', $args);
});
/*
$app->get('/', function ($request, $response) {
    return $this->view->render($response, 'home.twig');
});
*/
