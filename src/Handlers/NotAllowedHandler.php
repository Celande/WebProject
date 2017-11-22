<?php

namespace App\Handlers;

use Slim\Handlers\NotAllowed;
use Slim\Views\Twig;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class NotAllowedHandler extends NotAllowed {

    private $view;

    public function __construct(Twig $view) {
        $this->view = $view;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $methods) {
        $methods = array('GET', 'POST');
        parent::__invoke($request, $response, $methods);
        $response->withStatus(405);
        return $this->view->render($response, '405.twig',
        array(
          $methods => 'methods'
        ));
    }
}

?>
