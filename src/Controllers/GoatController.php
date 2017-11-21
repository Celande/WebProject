<?php

namespace App\Controllers;

use App\Models\Goat;
use App\Models\Breed;
use App\Models\Image;
use App\Controllers\CommonController as CommonController;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;
use \DateTime as DateTime;
use \DateInterval as DateInterval;

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
    $breeds = Breed::get();
    $imgs = Image::where('type', 'like', 'goat')->get();

    return $this->view->render($response, 'home.twig', array('goats' => $goats,
    'breeds' => $breeds, 'imgs' => $imgs));
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
    }

    // Get the breed according to the id to show the breed name
    $breed = Breed::find($goat->breed_id);
    $img = Image::find($goat['img_id']);

    // Get the age according to the birthdate
    $age = $this->getAge($goat->birthdate);

    $breeds = Breed::get();

    return $this->view->render($response, 'home.twig',
    array('goat' => $goat,
    'age' => $age,
    'breed_name' => $breed->name,
    'img' => $img,
    'breeds' => $breeds
  ));
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

    // Get the list of breed for the form
    $breeds = Breed::get();
    // Return the form
    return $this->view->render($response, 'add_goat.twig', array('breeds' => $breeds));

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

    // Get the breed id from the breed name
    $array['breed_id'] = Breed::select('id')->where('name', 'like', $array['breed_name'])->get()[0]->id;

    // Update the dates
    $array['created_at'] = new Datetime(); // ->format('Y-m-d')
    $array['updated_at'] = new Datetime();

    // Add Image
    $uploadedFiles = $request->getUploadedFiles();

    $uploadedFile = $uploadedFiles['image'];
    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
      $result = $this->moveUploadedFile($this->imgDir, $uploadedFile);
      if($result['filename'] == NULL || $result['id'] == NULL){
        return $response->withRedirect('/failure');
      }

      $array['img_id'] = $result['id'];
    }

    // If the goat was correctly added, you can redirect
    if($this->store($array)){
      return $response->withRedirect('/success');
    }
    // If the goat couldn't be added, redirect to the failure page
    return $response->withRedirect('/failure');
  }
  // ERROR in method
  else{
    return parent::not_allowed($request, $response, $args);
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
    // Get the breed according to the id
    $breed = Breed::find($goat->breed_id);
    $img = Image::find($goat['img_id']);
    // Return the form
    return $this->view->render($response, 'update_goat.twig', array(
      'goat' => $goat,
      'breed_name' => $breed->name,
      'img' => $img
    ));

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
    // Get the breed id according to the breed name
    $array['breed_id'] = Breed::select('id')->where('name', 'like', $array['breed_name'])->get()[0]->id;

    // Update the dates
    $array['updated_at'] = new Datetime();

    // Add Image
    $uploadedFiles = $request->getUploadedFiles();

    $uploadedFile = $uploadedFiles['image'];
    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
      $result = $this->moveUploadedFile($this->imgDir, $uploadedFile);
      if($result['filename'] == NULL || $result['id'] == NULL){
        return $response->withRedirect('/failure');
      }

      $array['img_id'] = $result['id'];
    }

    $img = Image::find($array['img_id']);

    // If the goat was correctly updated, redirect to success page
    if($this->update($array)){
      return $response->withRedirect('/success');
    }
    // If the goat couldn't be updated, redirect failure
    return $response->withRedirect('/failure');
  }
  // ERROR in method
  else{
    return parent::not_allowed($request, $response, $args);
  }
}

public function search_goat(Request $request, Response $response, $args){
  // POST method
  if($request->isPost()) {
    $this->logger->addInfo("Route /goats/search - post");

    // Get the posted data
    $data = $request->getParsedBody();
    // Create an array to manipulate the data
    $array = array();
    foreach($data as $key => $value){
      $array[$key] = $value;
    }

    $array['breed_id'] = Breed::select('id')->where('name', 'like', $array['breed_name'])->get()[0]->id;

    $date = new DateTime();
    if($array['age'] != NULL){
      $date->sub(new DateInterval('P'.$array['age'].'M'));
    } else {
      $array['age'] = 3000; // 250 years old
    }

    if($array['price'] == NULL){
      $array['price'] = 999999.99;
    }

    if($array['height'] == NULL){
      $array['height'] = 0;
    }

    if($array['weight'] == NULL){
      $array['weight'] = 0;
    }

    $array['color'] = strtolower($array['color']);

    // No Race && Gender
    if($array['breed_id'] == "" && $array['gender'] == "" && $array['exploitation'] != ""){
      $goats = Goat::where('price', '<=', $array['price'])
      ->whereDate('birthdate', '<=', $date)
      ->get();
    }
    // No Race && Exploitation
    else if($array['breed_id'] == "" && $array['exploitation'] == "" && $array['gender'] != ""){
      $goats = Goat::where('price', '<=', $array['price'])
      ->where('gender', $array['gender'])
      ->whereDate('birthdate', '<=', $date)
      ->get();
    }
    // No Race && Gender && No Exploitation
    else if($array['breed_id'] == "" && $array['exploitation'] == "" && $array['gender'] == ""){
      $goats = Goat::where('price', '<=', $array['price'])
      ->whereDate('birthdate', '<=', $date)
      ->get();
    }
    // No Gender
    else if($array['gender'] == "" && $array['exploitation'] != ""){
      $goats = Goat::where('price', '<=', $array['price'])
      ->where('breed_id', $array['breed_id'])
      ->whereDate('birthdate', '<=', $date)
      ->whereHas('breed', function($breedQuery) use($array){
        $breedQuery->where('id', '=', $array['breed_id'])
        ->where('height', '>=', $array['height'])
        ->where('weight', '>=', $array['weight'])
        ->where('color', 'like', '%'.$array['color'].'%')
        ->where('exploitation', $array['exploitation']);
      })
      ->get();
    }
    // No Exploitation
    else if($array['exploitation'] == "" && $array['gender'] != ""){
      $goats = Goat::where('price', '<=', $array['price'])
      ->where('breed_id', $array['breed_id'])
      ->where('gender', $array['gender'])
      ->whereDate('birthdate', '<=', $date)
      ->whereHas('breed', function($breedQuery) use($array){
        $breedQuery->where('id', '=', $array['breed_id'])
        ->where('height', '>=', $array['height'])
        ->where('weight', '>=', $array['weight'])
        ->where('color', 'like', '%'.$array['color'].'%');
      })
      ->get();
    }
    // No Gender && No Exploitation
    else if($array['exploitation'] == "" && $array['gender'] == ""){
      $goats = Goat::where('price', '<=', $array['price'])
      ->where('breed_id', $array['breed_id'])
      ->whereDate('birthdate', '<=', $date)
      ->whereHas('breed', function($breedQuery) use($array){
        $breedQuery->where('id', '=', $array['breed_id'])
        ->where('height', '>=', $array['height'])
        ->where('weight', '>=', $array['weight'])
        ->where('color', 'like', '%'.$array['color'].'%');
      })
      ->get();
    }
    else{
      $goats = Goat::where('price', '<=', $array['price'])
      ->where('breed_id', $array['breed_id'])
      ->where('gender', $array['gender'])
      ->whereDate('birthdate', '<=', $date)
      ->whereHas('breed', function($breedQuery) use($array){
        $breedQuery->where('id', '=', $array['breed_id'])
        ->where('height', '>=', $array['height'])
        ->where('weight', '>=', $array['weight'])
        ->where('color', 'like', '%'.$array['color'].'%')
        ->where('exploitation', $array['exploitation']);
      })
      ->get();
    }
    $breeds = Breed::get();
    $imgs = Image::get();
    return $this->view->render($response, 'home.twig', array('goats' => $goats,
    'breeds' => $breeds,
    'imgs' => $imgs
  ));
  }
  // ERROR in method
  else{
    return parent::not_allowed($request, $response, $args);
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
  $goat = Goat::find($id);
  $imgId = $goat->img_id;
  // Deleted the goat
  if($goat->delete()){
    if($this->deleteImg($imgId)){
      return TRUE;
    }
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
  $goat = Goat::find(intval($array['id']));
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
    if($imgToRemove != NULL){
      $this->deleteImg($imgToRemove);
      return TRUE;
    }
  }
  // Any problem
  return FALSE;
}

private function deleteImg($id){
  $img = Image::find($id);
  echo " IMG ID : " . $id;
  $file = "public/".$img->path.$img->type.$img->num.".".$img->ext;
  if (file_exists($file)) {
    if($img->delete()){
      unlink($file);
      return true;
    }
  }
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

private function moveUploadedFile($directory, UploadedFile $uploadedFile)
{
  $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
  //$basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
  $basename = "";
  // No image except for the default
  if(Image::where('type', 'like', 'goat')->get()->count() == 1){
    $basename = "goat1";
  } else {
    $last_num = Image::where('type', 'like', 'goat')
    ->orderBy('num', 'desc')
    ->first();
    $num = $last_num->num + 1;
    $basename = "goat".$num;
  }
  $filename = sprintf('%s.%0.8s', $basename, $extension);

  $uploadedFile->moveTo("public/" . $directory . DIRECTORY_SEPARATOR . $filename);

  // Add to DB
  $image = new Image;
  $image->path = $directory;
  $image->type = 'goat';
  $image->num = $num;
  $image->ext = $extension;

  $result = array();
  $result['filename'] = NULL;
  $result['lastId'] = NULL;
  if($image->save()){
    $result['filename'] = $filename;
    $result['id'] = $image->id;
  }
  return $result;
}
}
