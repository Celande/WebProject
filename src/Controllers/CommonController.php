<?php

namespace App\Controllers;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class CommonController
{
    protected $view;
    protected $logger;
    protected $table;

    public function __construct(
        Twig $view,
        LoggerInterface $logger,
        Builder $table
    ) {
        $this->view = $view;
        $this->logger = $logger;
        $this->table = $table;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $widgets = $this->table->get();

        $this->view->render($response, 'home.twig', [
            'widgets' => $widgets
        ]);

        return $response;
    }

    protected function not_found(Request $request, Response $response, $args){
      return $this->view->render($response, 'not_found.twig');
    }

}
