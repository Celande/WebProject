<?php

namespace App\Controllers;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/** CommonController Class
* Used as an abstract class for the other Controllers
**/
class CommonController
{
    protected $view;
    protected $logger;
    protected $table;

    /** __construct
    * Initialization of the class
    * @param $view
    * @param $logger
    * @param $table
    **/
    public function __construct(
        Twig $view,
        LoggerInterface $logger,
        Builder $table
    ) {
        $this->view = $view;
        $this->logger = $logger;
        $this->table = $table;
    }

    /** __invoke
    * Defoult behavior
    * @param Request $request
    * @param Response $response
    * @param $args
    **/
    public function __invoke(Request $request, Response $response, $args)
    {
        $widgets = $this->table->get();

        $this->view->render($response, 'home.twig', [
            'widgets' => $widgets
        ]);

        return $response;
    }

    /** not_found
    * Redirect to the 404 page
    * @param Request $request
    * @param Response $response
    * @param $args
    **/
    // TO DO: Take care with handlers, exception and/or redirect
    protected function not_found(Request $request, Response $response, $args){
      //return $this->view->render($response, 'not_found.twig');
      //this->redirect('/404');

      //$notFoundHandler = $this->container->get('notFoundHandler');
      //return $notFoundHandler($request, $response);
    }

}
