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
use \Datetime as DateTime;

class GoatController extends CommonController
{
  public function show_goats(Request $request, Response $response, $args){
    $this->logger->addInfo("Route /goats");

    $goats = Goat::get(); // all the rows of the DB

    /*
    if(!$goats){
    parent::not_found($request, $response, $args);
    return;
  }
  */

  return $this->view->render($response, 'home.twig', array('goats' => $goats));
}

public function show_goat(Request $request, Response $response, $args){
  $this->logger->addInfo("Route /goats/{id}");

  $id = $request->getAttribute('id');

  $goat = Goat::find($id); // all the rows of the DB

  if(!$goat){
    parent::not_found($request, $response, $args);
    return;
  }

  $race = Race::find($goat->race_id);

  // TO DO: Calculate AGE & SHOW NAME OF RACE
  //get age from date or birthdate
  $age = $this->getAge($goat->birthdate);
  //echo " birth = ".$goat->birthdate;
  //echo " Age is: " . $age;

  return $this->view->render($response, 'home.twig',
  ['goat' => $goat,
  'age' => $age,
  'race_name' => $race->name
]);
}

public function add_goat(Request $request, Response $response, $args){
  if($request->isGet()) {
    $this->logger->addInfo("Route /goats/add - get");

    $races = Race::get();

    return $this->view->render($response, 'add_goat.twig', array('races' => $races));

  }
  else if($request->isPost()) {
    $this->logger->addInfo("Route /goats/add - post");

    $data = $request->getParsedBody();
    $array = array();


    foreach($data as $key => $value){
      //echo $key . " = " . $value . " ";
      $array[$key] = $value;
    }

    // Get race_id
    $array['race_id'] = Race::select('id')->where('name', 'like', $array['race_name'])->get()[0]->id;

    if($this->store($array)){
      //echo " YEAH! ";
      //return $response->withRedirect('/success');
      return $response->withRedirect('/goats');
    }

    // FAIL
    return $response->withRedirect('/failure');
  }
  else{
    // ERROR
    // NOT ALLOWED
  }
}

public function remove_goat(Request $request, Response $response, $args){
  $this->logger->addInfo("Route /goats/remove");

  $data = $request->getParsedBody();
  $array = array();

  foreach($data as $key => $value){
    //echo $key . " = " . $value . " ";
    $array[$key] = $value;
  }

  if($this->delete(intval($array['id']))){
    //echo " YEAH! ";
    //return $response->withRedirect('/success');
    return $response->withRedirect('/goats');
  }

  // FAIL
  return $response->withRedirect('/failure');
}

public function update_goat(Request $request, Response $response, $args){
  if($request->isGet()) {
    $this->logger->addInfo("Route /goats/update - get");

    $id = $request->getQueryParams()['id'];
    //echo " ID = ".$id;

    $goat = Goat::find(intval($id));
    $race = Race::find($goat->race_id);

    return $this->view->render($response, 'update_goat.twig', [
      'goat' => $goat,
      'race_name' => $race->name]);

    }
    else if($request->isPost()) {
      $this->logger->addInfo("Route /goats/update - post");

      $data = $request->getParsedBody();
      $array = array();


      foreach($data as $key => $value){
        //echo $key . " = " . $value . " ";
        $array[$key] = $value;
      }

      // Get race_id
      $array['race_id'] = Race::select('id')->where('name', 'like', $array['race_name'])->get()[0]->id;

      if($this->update($array)){
        //echo " YEAH! ";
        //return $response->withRedirect('/success');
        return $response->withRedirect('/goats');
      }

      // FAIL
      return $response->withRedirect('/failure');
    }
    else{
      // ERROR
      // NOT ALLOWED
    }
  }

  private function store($array)
  {
    unset($array['race_name']);

    if(Goat::where('identification', 'like', $array['identification'])->get()->count() == 0){
      if(Goat::create($array)){
        return TRUE;
      }
    }

    return FALSE;
  }

  private function delete($id){
    $goat = Goat::find($id);

    if($goat->delete()){
      return TRUE;
    }

    return FALSE;
  }

  private function update($array)
  {
    unset($array['race_name']);

    $goat = Goat::find(intval($array['id']));

    $goat->name = $array['name'];
    $goat->price = $array['price'];
    $goat->birthdate = $array['birthdate'];
    $goat->race_id = $array['race_id'];
    $goat->gender = $array['gender'];
    $goat->localisation = $array['localisation'];
    $goat->identification = $array['identification'];
    $goat->description = $array['description'];

    if($goat->save()){
      return TRUE;
    }

    return FALSE;
  }

  public function getAge($birthdate){
    $date = new DateTime($birthdate);
    $now = new DateTime();
    $interval = $now->diff($date);
    //$age = $interval->format('%y year(s) %m month(s)');

    $year = "";
    if($interval->y > 1){
      $year = $interval->format("%y years ");
    } else if($interval->y == 1){
      $year = $interval->format("%y year ");
    }

    $month = "";
    if($interval->m > 1){
      $month = $interval->format("%m months ");
    } else if($interval->m == 1){
      $month = $interval->format("%m month ");
    }

    if(empty($year) && empty($month)){
      $week = "";
      $w = intval(($interval->d)/7);
      if(w > 1){
        return $w . " weeks";
      }
      return $w . " week";
    }

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
