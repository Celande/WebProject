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
    protected $imgDir;

    /** __construct
    * Initialization of the class
    * @param $view
    * @param $logger
    * @param $table
    * @param $imgDir
    **/
    public function __construct(
        Twig $view,
        LoggerInterface $logger,
        Builder $table,
        string $imgDir
    ) {
        $this->view = $view;
        $this->logger = $logger;
        $this->table = $table;
        $this->imgDir = $imgDir;
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
    protected function not_found(Request $request, Response $response, $args){
      return $response->withRedirect('/404');
    }

    /** not_allowed
    * Redirect to the 405 page
    * @param Request $request
    * @param Response $response
    * @param $args
    **/
    protected function not_allowed(Request $request, Response $response, $args){
      return $response->withRedirect('/405');
    }

}
