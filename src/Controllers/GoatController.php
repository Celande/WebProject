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

  /** showGoats
  * List all the goats in the DB
  * @param Request $request
  * @param Response $response
  * @param $args
  * @return $view
  **/
  public function showGoats(Request $request, Response $response, $args){
    $this->logger->addInfo("Route /goats");

    // Get all goats from the DB
    $goats = Goat::get();
    // Get all images from the DB for the goats
    $imgs = Image::where('type', 'like', 'goat')->get();
    // Get all the breeds
    $breeds = Breed::all();
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
    $this->logger->addInfo("Route /goats/{id}");

    // Get the goat according to the id
    $id = $request->getAttribute('id');
    $goat = Goat::find($id);

    // Can't find the goat then 404
    if(!$goat){
      parent::notFound($request, $response, $args);
    }

    // Get the image according to the id
    $img = Image::find($goat['img_id']);

    // Get the age according to the birthdate
    $age = $this->getAge($goat->birthdate);
    // Get all the breeds
    $breeds = Breed::all();

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
    $this->logger->addInfo("Route /goats/add - get");

    // Get the list of breed for the form
    $breeds = Breed::all();
    // Return the form
    return $this->view->render($response, 'add_goat.twig',
    array(
      'breeds' => $breeds
    ));

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
    $array['breed_id'] = Breed::where('name', 'like', $array['breed_name'])
                                ->first()
                                ->id;

    // Update the dates
    $array['created_at'] = new Datetime();
    $array['updated_at'] = new Datetime();

    // Add Image
    // Default image
    $array['img_id'] = Image::where('type', 'like', 'goat')
                          ->where('num', '=', '0')
                          ->first()
                          ->id;

    // Upload image
    $uploadedFiles = $request->getUploadedFiles();
    $uploadedFile = $uploadedFiles['image'];

    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
      // Get the file
      $result = $this->moveUploadedFile($this->imgDir, $uploadedFile);
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
      return $response->withRedirect('/success');
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
    $this->logger->addInfo("Route /goats/update - get");

    // Get the goat according to the id
    $id = $request->getQueryParams()['id'];
    $goat = Goat::find(intval($id));
    // Get the breed according to the id
    $breed = Breed::find($goat->breed_id);
    // Get the image according to the id
    $img = Image::find($goat->id);
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
    $this->logger->addInfo("Route /goats/update - post");

    // Get the data from the post
    $data = $request->getParsedBody();
    // Make the data into an array
    $array = array();
    foreach($data as $key => $value){
      $array[$key] = $value;
    }
    // Get the breed id according to the breed name
    $array['breed_id'] = Breed::where('name', 'like', $array['breed_name'])
                                ->first()
                                ->id;

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

    // If the goat was correctly updated, redirect to success page
    if($this->update($array)){
      return $response->withRedirect('/success');
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
    $this->logger->addInfo("Route /goats/search - post");

    // Get the posted data
    $data = $request->getParsedBody();
    // Create an array to manipulate the data
    $array = array();
    foreach($data as $key => $value){
      $array[$key] = $value;
    }

    $array['breed_id'] = Breed::where('name', 'like', $array['breed_name'])
                                ->first()
                                ->id;

    // Get the date from the age
    $date = new DateTime();
    if($array['age'] == NULL){
      $array['age'] = 3000; // 250 years old
    }
    $date->sub(new DateInterval('P'.$array['age'].'M'));

    if($array['price'] == NULL){
      $array['price'] = 999999.99; // Max price
    }

    if($array['height'] == NULL){
      $array['height'] = 0; // Min height
    }

    if($array['weight'] == NULL){
      $array['weight'] = 0; // Min weight
    }

    $array['color'] = strtolower($array['color']); // LowerCase

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
    // No Gender
    else if($array['gender'] == "" && $array['exploitation'] != ""){
      $goats = Goat::where('price', '<=', $array['price'])
      ->where('breed_id', $array['breed_id'])
      ->whereDate('birthdate', '>=', $date)
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
      ->whereDate('birthdate', '>=', $date)
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
      ->whereDate('birthdate', '>=', $date)
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
      ->whereDate('birthdate', '>=', $date)
      ->whereHas('breed', function($breedQuery) use($array){
        $breedQuery->where('id', '=', $array['breed_id'])
        ->where('height', '>=', $array['height'])
        ->where('weight', '>=', $array['weight'])
        ->where('color', 'like', '%'.$array['color'].'%')
        ->where('exploitation', $array['exploitation']);
      })
      ->get();
    }

    // Get breeds
    $breeds = Breed::all();
    // Get images
    $imgs = Image::all();
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
  // Delete the goat
  if($goat->delete()){
    // Delete the image
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
    // Delete old image
    if($imgToRemove != NULL){
      $this->deleteImg($imgToRemove);
      return TRUE;
    }
  }
  // Any problem
  return FALSE;
}

/** deleteImg
* Delete the image in the DB and delete the file
* @param $id
* @return boolean
**/
private function deleteImg($id){
  // Don't delete default image
  if($id == Image::where('type', 'like', 'goat')
                          ->where('num', '=', '0')
                          ->first()
                          ->id
                        ){
    return true;
  }

  // Get image
  $img = Image::find($id);
  // Get file
  $file = "public/".$img->path.$img->type.$img->num.".".$img->ext;

  if($img != NULL){
    if (file_exists($file)) {
      // Delete DB
      if($img->delete()){
        // Delete file
        unlink($file);
        return true;
      }
    }
  } else {
    return true;
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
    if($w > 1){
      return $w . " weeks";
    } else {
      return $w . " week";
    }
  }
  // Return the age with the total number of months
  return $year . $month . "(" . (($interval->y)*12 + ($interval->m)) . " months)";
}

private function moveUploadedFile($directory, UploadedFile $uploadedFile)
{
  $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
  $basename = "";
  // No image except for the default
  if(Image::where('type', 'like', 'goat')->get()->count() == 1){
    $basename = "goat1";
  } else {
    // Get last entry
    $lastNum = Image::where('type', 'like', 'goat')
    ->orderBy('num', 'desc')
    ->first();
    $num = $lastNum->num + 1;
    $basename = "goat".$num;
  }
  // Create filename
  $filename = sprintf('%s.%0.8s', $basename, $extension);

  // Upload file
  $uploadedFile->moveTo("public/" . $directory . DIRECTORY_SEPARATOR . $filename);

  // Create object
  $image = new Image;
  $image->path = $directory;
  $image->type = 'goat';
  $image->num = $num;
  $image->ext = $extension;

  // Result returned
  $result = array();
  $result['filename'] = NULL;
  $result['id'] = NULL;

  // Add to DB
  if($image->save()){
    $result['filename'] = $filename;
    $result['id'] = $image->id;
  }
  return $result;
}
}
