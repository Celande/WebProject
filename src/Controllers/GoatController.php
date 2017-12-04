<?php

namespace App\Controllers;

use App\Models\Goat;
use App\Controllers\BreedController;
use App\Controllers\ImageController;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;
use \DateTime;
use \DateInterval;

/** GoatController
* Used to control the Goat Model
**/
class GoatController extends CommonController
{
  /*** ***** Route method ***** ***/

  /** showGoats
  * List all the goats in the DB
  * @param Request $request
  * @param Response $response
  * @param $args
  * @return $view
  **/
  public function showGoats(Request $request, Response $response, $args){
    //$this-logger->addInfo("Route /goats");

    // Get all goats from the DB
    $goats = GoatController::getAllGoats($request, $response);
    // Get all images from the DB for the goats
    $imgs = ImageController::getGoatImages($request, $response);
    // Get all the breeds
    $breeds = BreedController::getAllBreeds();
    // Get ages
    $ages = array();
    foreach($goats as $goat){
      $ages[$goat->id] = $this->getAge($goat->birthdate);
    }

    return $this->view->render($response, 'home.twig',
    array(
      'goats' => $goats,
      'breeds' => $breeds,
      'imgs' => $imgs,
      'ages' => $ages
    ));
  }

  /** showGoat
  * Show the data of the goat corresponding to the id
  * @param Request $request
  * @param Response $response
  * @param $args
  * @return $view
  **/
  public function showGoat(Request $request, Response $response, $args){
    //$this-logger->addInfo("Route /goats/{id}");

    // Get the goat according to the id
    $id = $request->getAttribute('id');
    $goat = GoatController::getGoatById($request, $response, $id);

    // Get the image according to the id
    $img = ImageController::getImageById($request, $response, $goat->img_id);

    // Get the age according to the birthdate
    $age = $this->getAge($goat->birthdate);
    // Get all the breeds
    $breeds = BreedController::getAllBreeds();

    return $this->view->render($response, 'home.twig',
    array(
      'goat' => $goat,
      'age' => $age,
      'img' => $img,
      'breeds' => $breeds
    ));
  }

  /** addGoat
  * Add a goat to the DB
  * GET: show the form
  * POST: add the goat if the identification doesn't already exist
  * Deal with success, failure and not allowed
  * @param Request $request
  * @param Response $response
  * @param $args
  * @return $view
  **/
  public function addGoat(Request $request, Response $response, $args){
    // GET method
    if($request->isGet()) {
      //$this-logger->addInfo("Route /goats/add - get");

      // Get the list of breed for the form
      $breeds = BreedController::getAllBreeds();
      // Return the form
      return $this->view->render($response, 'add_goat.twig',
      array(
        'breeds' => $breeds
      ));

    }
    // POST method
    else if($request->isPost()) {
      //$this-logger->addInfo("Route /goats/add - post");

      // Get the posted data
      $data = $request->getParsedBody();
      // Create an array to manipulate the data
      $array = array();
      foreach($data as $key => $value){
        $array[$key] = $value;
      }

      // Get the breed id from the breed name or add it to the DB
      $breed = BreedController::addBreed($request, $response, $array['breed_name']);

      // Add the breed to the DB
      if($breed == NULL){
        // If the breed couldn't be added, redirect to the failure page
        return $response->withRedirect('/failure');
      } else {
        $array['breed_id'] = $breed->id;
      }

      // Update the dates
      $array['created_at'] = new Datetime();
      $array['updated_at'] = new Datetime();

      // Add Image
      // Default image
      $array['img_id'] = ImageController::getDefaultImage()->id;

      // Upload image
      $uploadedFiles = $request->getUploadedFiles();
      $uploadedFile = $uploadedFiles['image'];

      if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        // Get the file
        $result = ImageController::moveUploadedFile($request, $response, $this->imgDir, $uploadedFile);
        // Check that it exists
        if($result['filename'] == NULL || $result['id'] == NULL){
          return $response->withRedirect('/failure');
        }
        // Add image to goat
        if($result['id'] != NULL){
          $array['img_id'] = $result['id'];
        }
      }

      // If the goat was correctly added, you can redirect
      if($this->store($array)){
        return $response->withRedirect('/home');
      }
      // If the goat couldn't be added, redirect to the failure page
      return $response->withRedirect('/failure');
    }
    // ERROR in method
    else{
      return parent::notAllowed($request, $response, $args);
    }
  }

  /** removeGoat
  * Defoult behavior
  * @param Request $request
  * @param Response $response
  * @param $args
  * @return $view
  **/
  public function removeGoat(Request $request, Response $response, $args){
    //$this-logger->addInfo("Route /goats/remove");

    // Get data from the post method
    $data = $request->getParsedBody();
    // Make data into array
    $array = array();
    foreach($data as $key => $value){
      $array[$key] = $value;
    }
    // The goat was correctly deleted, redirect to success page
    if($this->delete(intval($array['id']))){
      return $response->withRedirect('/home');
    }
    // The goat couldn't be deleted, redirect to failure page
    return $response->withRedirect('/failure');
  }

  /** updateGoat
  * Update a goat in the DB
  * GET: Return the form
  * POST: Update the data on the goat in the DB
  * @param Request $request
  * @param Response $response
  * @param $args
  * @return $view
  **/
  public function updateGoat(Request $request, Response $response, $args){
    // GET method
    if($request->isGet()) {
      //$this-logger->addInfo("Route /goats/update - get");

      // Get the goat according to the id
      $id = $request->getQueryParams()['id'];
      $goat = GoatController::getGoatById($request, $response, intval($id));
      // Get the breed according to the id
      $breed = BreedController::getBreedById($request, $response, $goat->breed_id);
      // Get the image according to the id
      $img = ImageController::getImageById($request, $response, $goat->img_id);
      // Return the form
      return $this->view->render($response, 'update_goat.twig',
      array(
        'goat' => $goat,
        'breed_name' => $breed->name,
        'img' => $img
      ));

    }
    // POST method
    else if($request->isPost()) {
      //$this-logger->addInfo("Route /goats/update - post");

      // Get the data from the post
      $data = $request->getParsedBody();
      // Make the data into an array
      $array = array();
      foreach($data as $key => $value){
        $array[$key] = $value;
      }

      // Get the breed id from the breed name or add it to the DB
      $breed = BreedController::addBreed($request, $response, $array['breed_name']);

      // Add the breed to the DB
      if($breed == NULL){
        // If the breed couldn't be added, redirect to the failure page
        return $response->withRedirect('/failure');
      } else {
        $array['breed_id'] = $breed->id;
      }

      if($array['breed_id'] == NULL){
        // If the goat couldn't be added, redirect to the failure page
        return $response->withRedirect('/failure');
      }

      // Update the dates
      $array['updated_at'] = new Datetime();

      // Upload image
      $uploadedFiles = $request->getUploadedFiles();
      $uploadedFile = $uploadedFiles['image'];

      if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        // Get the file
        $result = ImageController::moveUploadedFile($request, $response, $this->imgDir, $uploadedFile);
        // Check that it exists
        if($result['filename'] == NULL || $result['id'] == NULL){
          return $response->withRedirect('/failure');
        }
        // Add image to goat
        if($result['id'] != NULL){
          $array['img_id'] = $result['id'];
        }
      }

      // If the goat was correctly updated, redirect to success page
      if($this->update($array)){
        return $response->withRedirect('/goats/'.$array['id']);
      }
      // If the goat couldn't be updated, redirect failure
      return $response->withRedirect('/failure');
    }
    // ERROR in method
    else{
      return parent::notAllowed($request, $response, $args);
    }
  }

  /** searchGoat
  * Search for a goat
  * POST: Use the form to retrieve the goats
  * @param Request $request
  * @param Response $response
  * @param $args
  * @return $view
  **/
  public function searchGoat(Request $request, Response $response, $args){
    // POST method
    if($request->isPost()) {
      //$this-logger->addInfo("Route /goats/search - post");

      // Get the posted data
      $data = $request->getParsedBody();
      // Create an array to manipulate the data
      $array = array();
      foreach($data as $key => $value){
        $array[$key] = $value;
      }

      $goats = $this->getSearchGoat($array);

      // Get breeds
      $breeds = BreedController::getAllBreeds();
      // Get images
      $imgs = ImageController::getGoatImages($request, $response);
      // Get ages
      $ages = array();
      foreach($goats as $goat){
        $ages[$goat->id] = $this->getAge($goat->birthdate);
      }

      return $this->view->render($response, 'home.twig',
      array(
        'goats' => $goats,
        'breeds' => $breeds,
        'imgs' => $imgs,
        'ages' => $ages
      ));
    }
    // ERROR in method
    else{
      return parent::notAllowed($request, $response, $args);
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
    // Remove breed_name from the array
    unset($array['breed_name']);

    // Check array
    if($array == NULL
    || $array['name'] == NULL
    || $array['price'] == NULL
    || $array['birthdate'] == NULL
    || $array['breed_id'] == NULL
    || $array['gender'] == NULL
    || $array['localisation'] == NULL
    || $array['identification'] == NULL){
      return false;
    }

    // Check if the goat is already in the DB
    // count must be equal to 0
    if(Goat::where('identification', 'like', $array['identification'])->get()->count() == 0){
      // No problem in the creation
      if(Goat::create($array)){
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
    $goat = GoatController::getGoatById($request, $response, $id);
    if($goat == NULL){
      return false;
    }

    $imgId = $goat->img_id;
    if($imgId == NULL){
      return false;
    }
    // Delete the goat
    if($goat->delete()){
      // Delete the image
      ImageController::removeImage($imgId);
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
    // Remove breed_name from the array
    unset($array['breed_name']);
    // Get the goat according to its id
    $goat = GoatController::getGoatById($request, $response, intval($array['id']));
    if($goat == NULL){
      return false;
    }

    $imgToRemove = $goat->img_id;
    // Replace the data
    $goat->name = $array['name'];
    $goat->price = $array['price'];
    $goat->birthdate = $array['birthdate'];
    $goat->breed_id = $array['breed_id'];
    $goat->gender = $array['gender'];
    $goat->localisation = $array['localisation'];
    $goat->identification = $array['identification'];
    $goat->description = $array['description'];
    $goat->img_id = $array['img_id'];
    // Goat was updated
    if($goat->save()){
      // Delete old image
      if($imgToRemove != $goat->img_id){
        ImageController::removeImage($imgToRemove);
      }

      return true;
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
      if($w > 1){
        return $w . " weeks";
      } else {
        return $w . " week";
      }
    }
    // Return the age with the total number of months
    return $year . $month . "(" . (($interval->y)*12 + ($interval->m)) . " months)";
  }

  /** getSearchGoat
  * Get the goat according to the parameters
  * @param $array
  * @return $goat
  **/
  private function getSearchGoat($array){
    $array['breed_id'] = BreedController::getBreedByName($request, $response, $array['breed_name'])->id;

    // Get the date from the age
    $date = new DateTime();
    if($array['age'] == NULL){
      $array['age'] = 3000; // 250 years old
    }
    $date->sub(new DateInterval('P'.$array['age'].'M'));

    if($array['price'] == NULL){
      $array['price'] = 999999.99; // Max price
    }

    $goats = NULL;

    // No Race && Gender
    if($array['breed_id'] == "" && $array['gender'] == "" && $array['exploitation'] != ""){
      $goats = Goat::where('price', '<=', $array['price'])
      ->whereDate('birthdate', '>=', $date)
      ->get();
    }
    // No Race && Exploitation
    else if($array['breed_id'] == "" && $array['exploitation'] == "" && $array['gender'] != ""){
      $goats = Goat::where('price', '<=', $array['price'])
      ->where('gender', $array['gender'])
      ->whereDate('birthdate', '>=', $date)
      ->get();
    }
    // No Race && Gender && No Exploitation
    else if($array['breed_id'] == "" && $array['exploitation'] == "" && $array['gender'] == ""){
      $goats = Goat::where('price', '<=', $array['price'])
      ->whereDate('birthdate', '>=', $date)
      ->get();
    }
    // Gender
    else if($array['gender'] == "" && $array['exploitation'] != ""){
      $goats = Goat::where('price', '<=', $array['price'])
      ->whereDate('birthdate', '>=', $date)
      ->where('breed_id', $array['breed_id'])
      ->get();
    }
    // Exploitation
    else if($array['exploitation'] == "" && $array['gender'] != ""){
      $goats = Goat::where('price', '<=', $array['price'])
      ->where('gender', $array['gender'])
      ->whereDate('birthdate', '>=', $date)
      ->where('breed_id', $array['breed_id'])
      ->get();
    }
    // Gender && No Exploitation
    else if($array['exploitation'] == "" && $array['gender'] == ""){
      $goats = Goat::where('price', '<=', $array['price'])
      ->whereDate('birthdate', '>=', $date)
      ->where('breed_id', $array['breed_id'])
      ->get();
    }
    else{
      $goats = Goat::where('price', '<=', $array['price'])
      ->whereDate('birthdate', '>=', $date)
      ->where('gender', $array['gender'])
      ->where('breed_id', $array['breed_id'])
      ->get();
    }

    return $goats;
  }

  /** getAllGoats
  * Get all the goats
  * @return $goat
  **/
  public function getAllGoats(){
    $goats = Goat::all();
    /*
    if(!$goats){
      return parent::notFound($request, $response, $args);
    }
    */
    return $goats;
  }

  /** getGoatById
  * Get a goat according to its id
  * @param $id
  * @return $goat
  **/
  public function getGoatById($request, $response, $id){
    $goat = Goat::find($id);
    if(!$goat){
      return parent::notFound($request, $response, $args);
    }
    return $goat;
  }

}
