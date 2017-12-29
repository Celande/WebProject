<?php

namespace App\Mobiles;

use App\Models\Goat;
use App\Mobiles\BreedMobile;
use App\Mobiles\ImageMobile;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;
use \DateTime;
use \DateInterval;

/** GoatMobile
* Used to control the Goat Model
**/
class GoatMobile extends CommonMobile
{
  /*** ***** Route method ***** ***/

  private $string = array();

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
    $goats = GoatMobile::getAllGoats($request, $response);
    // Get ages
    $array = array();
    foreach($goats as $goat){
      $goat->age = $this->getAge($goat->birthdate);
      $goat->img_path = ImageMobile::getImageById($request, $response, $goat->img_id);
      $goat->breed_name = BreedMobile::getBreedById($request, $response, $goat->breed_id)->name;
      $array[] = $goat;
    }

      $response = $response->withHeader('Access-Control-Allow-Origin', '*');
    return $response->withJson($array);

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
    $goat = GoatMobile::getGoatById($request, $response, $id);
    $goat->age = $this->getAge($goat->birthdate);
    $goat->img_path = ImageMobile::getImageById($request, $response, $goat->img_id);
    $goat->breed_name = BreedMobile::getBreedById($request, $response, $goat->breed_id)->name;

    $response = $response->withHeader('Access-Control-Allow-Origin', '*');
    return $response->withJson($goat);
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
      //$this-logger->addInfo("Route /goats/add - post");

      $response = $response->withAddedHeader('Access-Control-Allow-Origin', '*');

      $data = file_get_contents('php://input');
      // Create an array to manipulate the data
      $array = array();
      $array = json_decode($data, true);

      //$string[] = " Phase 1 : " . $array . "\n";

      // Get the breed id from the breed name or add it to the DB
      if($array['breed_id'] != 0){
        $array['breed_name'] = BreedMobile::getBreedById($request, $response, $array['breed_id'])->name;
      }
      $string[] = " Breed Name : " . $array["breed_name"] . "\n";
      $breed = BreedMobile::addBreed($request, $response, $array['breed_name']);

      // Add the breed to the DB
      if($breed == NULL){
        // If the breed couldn't be added, redirect to the failure page
        $string[] = " Breed failed " . "\n";
        return $response->withJson($string);
      } else {
        $array['breed_id'] = $breed->id;
        $string[] = " Breed id : " . $array["breed_id"] . "\n";
      }

      // Update the dates
      $array['created_at'] = new Datetime();
      $array['updated_at'] = new Datetime();

      $string[] = " Date " . "\n";

      // Add Image
      // Default image
      $array['img_id'] = ImageMobile::getDefaultImage()->id;

      $string[] = " Image id : " . $array["img_id"] . "\n";

      /* Upload image
      $uploadedFiles = $request->getUploadedFiles();
      $uploadedFile = $uploadedFiles['image'];

      if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        // Get the file
        $result = ImageMobile::moveUploadedFile($request, $response, $this->imgDir, $uploadedFile);
        // Check that it exists
        if($result['filename'] == NULL || $result['id'] == NULL){
          return $response->withRedirect('/failure');
        }
        // Add image to goat
        if($result['id'] != NULL){
          $array['img_id'] = $result['id'];
        }
      }
      */

      // If the goat was correctly added, you can redirect
      $string[] = " Before trying to store ";
      if($this->store($array)){
        $array[] = " Stored " . "\n";
        return $response->withJson($array);
      }
      // If the goat couldn't be added, redirect to the failure page
      $array[] = " Not Stored " . "\n";
      //return $response->withRedirect('/failure');
      return $response->withJson($array);

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

    $id = $request->getAttribute('id');

    $response = $response->withHeader('Access-Control-Allow-Origin', '*');
    // The goat was correctly deleted, redirect to success page
    if($this->delete(intval($id))){
      return $response->withJson("OK");
    }
    // The goat couldn't be deleted, redirect to failure page
    return $response->withJson("FAILURE");
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
      //$id = $request->getQueryParams()['id'];

      $id = $request->getAttribute('id');
    $goat = GoatMobile::getGoatById($request, $response, $id);
    $goat->age = $this->getAge($goat->birthdate);
    $goat->img_path = ImageMobile::getImageById($request, $response, $goat->img_id);
    $goat->breed_name = BreedMobile::getBreedById($request, $response, $goat->breed_id)->name;

    $response = $response->withHeader('Access-Control-Allow-Origin', '*');
    return $response->withJson($goat);

    }
    // POST method
    else if($request->isPost() || $request->isOptions()) {
      //$this-logger->addInfo("Route /goats/update - post");

      $response = $response->withAddedHeader('Access-Control-Allow-Origin', '*');

      $id = $request->getAttribute('id');
      $goat = GoatMobile::getGoatById($request, $response, $id);
      $data = file_get_contents('php://input');
      // Create an array to manipulate the data
      $array = array();
      $array = json_decode($data, true);

      $array["id"] = $id;

      // Get the breed id from the breed name or add it to the DB
      // Get the breed id from the breed name or add it to the DB
      if($array['breed_id'] != 0){
        $array['breed_name'] = BreedMobile::getBreedById($request, $response, $array['breed_id'])->name;
      }
      $string[] = " Breed Name : " . $array["breed_name"] . "\n";
      $breed = BreedMobile::addBreed($request, $response, $array['breed_name']);

      // Add the breed to the DB
      if($breed == NULL){
        // If the breed couldn't be added, redirect to the failure page
        $string[] = " Breed failed " . "\n";
        return $response->withJson($string);
      } else {
        $array['breed_id'] = $breed->id;
        $string[] = " Breed id : " . $array["breed_id"] . "\n";
      }

      // Update the dates
      $array['updated_at'] = new Datetime();

      // Upload image
      /*
      $uploadedFiles = $request->getUploadedFiles();
      $uploadedFile = $uploadedFiles['image'];

      if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        // Get the file
        $result = ImageMobile::moveUploadedFile($request, $response, $this->imgDir, $uploadedFile);
        // Check that it exists
        if($result['filename'] == NULL || $result['id'] == NULL){
          return $response->withRedirect('/failure');
        }
        // Add image to goat
        if($result['id'] != NULL){
          $array['img_id'] = $result['id'];
        }
      }
      */

      // If the goat was correctly updated, redirect to success page
      // If the goat was correctly added, you can redirect
      $string[] = " Before trying to store ";
      if($this->update($array)){
        $array[] = " Updated " . "\n";
        return $response->withJson($array);
      }
      // If the goat couldn't be added, redirect to the failure page
      $array[] = " Not Updated " . "\n";
      //return $response->withRedirect('/failure');
      return $response->withJson($array);
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
      echo " Store Empty field ";
      return false;
    }

    // Check if the goat is already in the DB
    // count must be equal to 0
    $string[] = "Before Identification";
    if(Goat::where('identification', 'like', $array['identification'])->get()->count() == 0){
      // No problem in the creation
      if(Goat::create($array)){
        return TRUE;
      }
      echo " No Create ";
      return;
    } else {
      echo " No identification ";
      return;
    }
    // Any problem
    echo " Store False ";
    return FALSE;
  }

  /** delete
  * Remove a goat from the DB according to its id
  * @param $id
  * @return boolean
  **/
  private function delete($id){
    // Get the goat from the DB
    $goat = GoatMobile::getGoatById($request, $response, $id);
    if($goat == NULL){
      return false;
    }
    /*
    $imgId = $goat->img_id;
    if($imgId == NULL){
      return false;
    }
    */
    // Delete the goat
    if($goat->delete()){
      // Delete the image
      //ImageMobile::removeImage($imgId);
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
    $goat = GoatMobile::getGoatById($request, $response, intval($array['id']));
    if($goat == NULL){
      echo " No Goat ";
      return false;
    }

    //$imgToRemove = $goat->img_id;
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
      /*
      if($imgToRemove != $goat->img_id){
        ImageMobile::removeImage($imgToRemove);
      }
      */

      return true;
    }
    echo " No Goat Not Updated";
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
