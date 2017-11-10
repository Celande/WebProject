<?php

namespace App\Controllers;

use App\Models\Goat;
use App\Models\Race;
use App\Controllers\CommonController as CommonController;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \Datetime as Datetime;

/** GoatController
* Used to control the Goat Model
**/
class GoatController extends CommonController
{
  /*** ***** Route method ***** ***/

  /** show_goats
    * List all the goats in the DB
    * @param Request $request
    * @param Response $response
    * @param $args
    * @return $view
    **/
  public function show_goats(Request $request, Response $response, $args){
    $this->logger->addInfo("Route /goats");

    // Get all goats from the DB
    $goats = Goat::get();
    return $this->view->render($response, 'home.twig', array('goats' => $goats));
  }

  /** show_goat
    * Show the data of the goat corresponding to the id
    * @param Request $request
    * @param Response $response
    * @param $args
    * @return $view
    **/
  public function show_goat(Request $request, Response $response, $args){
    $this->logger->addInfo("Route /goats/{id}");

    $id = $request->getAttribute('id');
    $goat = Goat::find($id);

    // Can't find the goat then 404
    if(!$goat){
      parent::not_found($request, $response, $args);
      return;
    }

    // Get the race according to the id to show the race name
    $race = Race::find($goat->race_id);

    // Get the age according to the birthdate
    $age = $this->getAge($goat->birthdate);

    return $this->view->render($response, 'home.twig',
    ['goat' => $goat,
    'age' => $age,
    'race_name' => $race->name
  ]);
}

/** add_goat
    * Add a goat to the DB
    * GET: show the form
    * POST: add the goat if the identification doesn't already exist
    * Deal with success, failure and not allowed
    * @param Request $request
    * @param Response $response
    * @param $args
    * @return $view
    **/
public function add_goat(Request $request, Response $response, $args){
  // GET method
  if($request->isGet()) {
    $this->logger->addInfo("Route /goats/add - get");

    // Get the list of race for the form
    $races = Race::get();
    // Return the form
    return $this->view->render($response, 'add_goat.twig', array('races' => $races));

  }
  // POST method
  else if($request->isPost()) {
    $this->logger->addInfo("Route /goats/add - post");

    // Get the posted data
    $data = $request->getParsedBody();
    // Create an array to manipulate the data
    $array = array();
    foreach($data as $key => $value){
      $array[$key] = $value;
    }

    // Get the race id from the race name
    $array['race_id'] = Race::select('id')->where('name', 'like', $array['race_name'])->get()[0]->id;

    // Update the dates
    $array['created_at'] = new Datetime('Y-m-d');
    $array['updated_at'] = new Datetime('Y-m-d');

    // If the goat was correctly added, you can redirect
    if($this->store($array)){
      return $response->withRedirect('/success');
      //return $response->withRedirect('/goats');
    }
    // If the goat couldn't be added, redirect to the failure page
    return $response->withRedirect('/failure');
  }
  // ERROR in method
  else{
    // ERROR
    // NOT ALLOWED: 302?
  }
}

/** remove_goat
    * Defoult behavior
    * @param Request $request
    * @param Response $response
    * @param $args
    * @return $view
    **/
public function remove_goat(Request $request, Response $response, $args){
  $this->logger->addInfo("Route /goats/remove");

  // Get data from the post method
  $data = $request->getParsedBody();
  // Make data into array
  $array = array();
  foreach($data as $key => $value){
    $array[$key] = $value;
  }
  // The goat was correctly deleted, redirect to success page
  if($this->delete(intval($array['id']))){
    return $response->withRedirect('/success');
    //return $response->withRedirect('/goats');
  }
  // The goat couldn't be deleted, redirect to failure page
  return $response->withRedirect('/failure');
}

/** update_goat
    * Update a goat in the DB
    * GET: Return the form
    * POST: Update the data on the goat in the DB
    * @param Request $request
    * @param Response $response
    * @param $args
    * @return $view
    **/
public function update_goat(Request $request, Response $response, $args){
  // GET method
  if($request->isGet()) {
    $this->logger->addInfo("Route /goats/update - get");

    // Get the goat according to the id
    $id = $request->getQueryParams()['id'];
    $goat = Goat::find(intval($id));
    // Get the race according to the id
    $race = Race::find($goat->race_id);
    // Return the form
    return $this->view->render($response, 'update_goat.twig', [
      'goat' => $goat,
      'race_name' => $race->name]);

    }
    // POST method
    else if($request->isPost()) {
      $this->logger->addInfo("Route /goats/update - post");

      // Get the data from the post
      $data = $request->getParsedBody();
      // Make the data into an array
      $array = array();
      foreach($data as $key => $value){
        $array[$key] = $value;
      }
      // Get the race id according to the race name
      $array['race_id'] = Race::select('id')->where('name', 'like', $array['race_name'])->get()[0]->id;

      // Update the dates
      //$array['updated_at'] = new Datetime('Y-m-d');
      $array['updated_at'] = new Datetime();

      // If the goat was correctly updated, redirect to success page
      if($this->update($array)){
        return $response->withRedirect('/success');
        //return $response->withRedirect('/goats');
      }
      // If the goat couldn't be updated, redirect failure
      return $response->withRedirect('/failure');
    }
    // ERROR in method
    else{
      // ERROR
      // NOT ALLOWED
    }
  }

  /*** ***** Other method ***** ***/

  /** store
    * Add a goat to the DB if its identification doesn't already exist
    * @param $array
    * @return boolean
    **/
  private function store($array)
  {
    // Remove race_name from the array
    unset($array['race_name']);

    // Check if the goat is already in the DB
    // count must be equal to 0
    if(Goat::where('identification', 'like', $array['identification'])->get()->count() == 0){
      // No problem in the creation
      if(Goat::create($array)){
        $table->touch();
        return TRUE;
      }
    }
    // Any problem
    return FALSE;
  }

  /** delete
    * Remove a goat from the DB according to its id
    * @param $id
    * @return boolean
    **/
  private function delete($id){
    // Get the goat from the DB
    $goat = Goat::find($id);
    // Deleted the goat
    if($goat->delete()){
      return TRUE;
    }
    // If the goat doesn't exist or if there is any problem
    return FALSE;
  }

  /** update
    * Update the data of a goat in the DB
    * @param $array
    * @return boolean
    **/
  private function update($array)
  {
    // Remove race_name from the array
    unset($array['race_name']);
    // Get the goat according to its id
    $goat = Goat::find(intval($array['id']));
    // Replace the data
    $goat->name = $array['name'];
    $goat->price = $array['price'];
    $goat->birthdate = $array['birthdate'];
    $goat->race_id = $array['race_id'];
    $goat->gender = $array['gender'];
    $goat->localisation = $array['localisation'];
    $goat->identification = $array['identification'];
    $goat->description = $array['description'];
    // Goat was updated
    if($goat->save()){
      //$table->touch();
      return TRUE;
    }
    // Any problem
    return FALSE;
  }

  /** getAge
    * Get the age of a goat
    * @param $birthdate
    * @return string
    **/
  public function getAge($birthdate){
    // Get dates
    $date = new DateTime($birthdate);
    $now = new DateTime();
    // Calculate the difference
    $interval = $now->diff($date);
    // Get the number of years
    $year = "";
    if($interval->y > 1){
      $year = $interval->format("%y years ");
    } else if($interval->y == 1){
      $year = $interval->format("%y year ");
    }
    // Get the number of months
    $month = "";
    if($interval->m > 1){
      $month = $interval->format("%m months ");
    } else if($interval->m == 1){
      $month = $interval->format("%m month ");
    }
    // If less than a month, get the number of weeks
    if(empty($year) && empty($month)){
      $week = "";
      $w = intval(($interval->d)/7);
      if(w > 1){
        return $w . " weeks";
      }
      return $w . " week";
    }
    // Return the age with the total number of months
    return $year . $month . "(" . (($interval->y)*12 + ($interval->m)) . " months)";
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
