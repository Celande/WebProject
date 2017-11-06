<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/* https://github.com/aimeos/aimeos-slim/issues/4 */

/*
$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
    //return $this->view->render($response, 'home.twig');
    //return $this->renderer->render($response, 'home.twig', $args);
});
*/
