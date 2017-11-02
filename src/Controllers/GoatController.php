<?php

namespace App\Controllers;

use App\Models\Goat;
use App\Controllers\CommonController as CommonController;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class GoatController extends CommonController
{
    public function show_goats(Request $request, Response $response, $args){
      $this->logger->addInfo("Route /goats");

       $goats = Goat::get(); // all the rows of the DB

       if(!$goats){
         parent::not_found($request, $response, $args);
         return;
       }

     return $this->view->render($response, 'home.twig', array('goats' => $goats));
    }

    public function show_goat(Request $request, Response $response, $args){
      $this->logger->addInfo("Route /goats/{id}");

      $id = $request->getAttribute('id');

       $goat = Goat::find($id); // all the rows of the DB

       // TO DO: Calculate AGE & SHOW NAME OF RACE

       if(!$goat){
         parent::not_found($request, $response, $args);
         return;
       }

     return $this->view->render($response, 'home.twig', ['goat' => $goat]);
    }

    public function add_goat(Request $request, Response $response, $args){
      $this->logger->addInfo("Route /goats/add");

      $this->view->render($response, 'add_goat.twig');
    }

    public function adding_goat(Request $request, Response $response, $args){
      $this->logger->addInfo("Route /goats/adding");
    }

    /* Delete Model by KEY
    App\Flight::destroy(1);

App\Flight::destroy([1, 2, 3]);

App\Flight::destroy(1, 2, 3);
*/

/* Delete Model by query
$deletedRows = App\Flight::where('active', 0)->delete();
*/

/** Dynamic scope <=> search
     * Scope a query to only include users of a given type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $type
     * @return \Illuminate\Database\Eloquent\Builder
     */ /*
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
    */
}
