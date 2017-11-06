<?php

/* http://laravel.sillo.org/laravel-4-chapitre-34-les-relations-avec-eloquent-2-2/ */

namespace App\Controllers;

use App\Models\Race;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/** RaceController
* Controller of the Race Model
**/
class RaceController extends CommonController
{
  /** show_races
  * List of all the goat races
  * @param Request $request
  * @param Response $response
  * @param $args
  * @return $view
  **/
  public function show_races(Request $request, Response $response, $args){
    $this->logger->addInfo("Route /races");

    // Get all races from DB
    $races = Race::get();
    return $this->view->render($response, 'home.twig', array('races' => $races));
  }

  /** show_race
  * Show data of one goat
  * @param Response $response
  * @param $args
  * @return $view
  **/
  public function show_race(Request $request, Response $response, $args){
    $this->logger->addInfo("Route /races/{id}");

    // Get the id from request
    $id = $request->getAttribute('id');
    // Get the race according to the race id
    $race = Race::find($id);
    // Can't find the race, redirect to 404
    if(!$race){
      parent::not_found($request, $response, $args);
      return;
    }

    return $this->view->render($response, 'home.twig', ['race' => $race]);
  }
}
