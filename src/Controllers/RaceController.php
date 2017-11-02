<?php

/* http://laravel.sillo.org/laravel-4-chapitre-34-les-relations-avec-eloquent-2-2/ */

//namespace App;

namespace App\Controllers;

use App\Models\Race;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class RaceController extends CommonController
{
    public function show_races(Request $request, Response $response, $args){
      $this->logger->addInfo("Route /races");

       $races = Race::get(); // all the rows of the DB

       if(!$races){
         parent::not_found($request, $response, $args);
         return;
       }

       //echo "Races: " . $races;

     return $this->view->render($response, 'home.twig', array('races' => $races));
    }

    public function show_race(Request $request, Response $response, $args){
      $this->logger->addInfo("Route /races/{id}");

      $id = $request->getAttribute('id');

       $race = Race::find($id); // all the rows of the DB

       //echo "<br>Echo, Race " . $id . ": ".$race;
       //echo "test: " . $race['name'];

       if(!$race){
         parent::not_found($request, $response, $args);
         return;
       }

     return $this->view->render($response, 'home.twig', ['race' => $race]);
    }

    /**
     * Create a new race instance which is a new entry in the DB
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $race = new Race;

        $race->name = $request->name;
        // TO DO: Set up all the other attributes

        $race->save();
    }
}
