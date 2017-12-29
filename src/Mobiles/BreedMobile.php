<?php

namespace App\Mobiles;

use App\Models\Breed;
use App\Mobiles\ImageMobile;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/** BreedMobile
* Mobile of the Breed Model
**/
class BreedMobile extends CommonMobile
{
  /** showBreeds
  * List of all the goat breeds
  * @param Request $request
  * @param Response $response
  * @param $args
  * @return $view
  **/
  public function showBreeds(Request $request, Response $response, $args){
    //$this-logger->addInfo("Route /breeds");

    // Get all breeds from DB
    $breeds = BreedMobile::getAllBreeds();
    // Get all images from DB
    $imgs = ImageMobile::getBreedImages($request, $response);

    foreach($breeds as $breed){
      $breed->img_path = ImageMobile::getImageById($request, $response, $breed->img_id);
      $array[] = $breed;
    }
      $response = $response->withHeader('Access-Control-Allow-Origin', '*');
      return $response->withJson($array);
  }

  /** showBreed
  * Show data of one goat
  * @param Response $response
  * @param $args
  * @return $view
  **/
  public function showBreed(Request $request, Response $response, $args){
    //$this-logger->addInfo("Route /breeds/{id}");

    // Get the id from request
    $id = $request->getAttribute('id');
    // Get the breed according to the breed id
    $breed = BreedMobile::getBreedById($request, $response, $id);

    $breed->img_path = ImageMobile::getImageById($request, $response, $breed->img_id);

    $response = $response->withHeader('Access-Control-Allow-Origin', '*');
    return $response->withJson($breed);
  }

  /** getAllBreeds
  * Get all breeds
  * @return Breed[]
  **/
  public function getAllBreeds(){
    $breeds = Breed::all();
    if(!$breeds){
      //return parent::notFound($request, $response, NULL);
    }
    return $breeds;
  }

  /** getBreedByName
  * Get a breed according to its name
  * @param $name
  * @return Breed
  **/
  public function getBreedByName(Request $request, Response $response, $name){
$breed = NULL;
    if($name == "" || $name == null){
      return $breed;
    }
    $breed = Breed::where('name', 'like', $name)
                  ->first();

    return $breed;
  }

  /** addBreed
  * Add a breed to the DB
  * @param $name
  * @return int or NULL
  **/
  public function addBreed(Request $request, Response $response, $name){
    $name = ucfirst(strtolower($name));
    $existingBreed = BreedMobile::getBreedByName($request, $response, $name);
    if($existingBreed == NULL){
      $breed = new Breed;
      $breed->name = $name;
      $breed->height = 0;
      $breed->weight = 0;
      $breed->color = "N/A";
      $breed->origin = "N/A";

      if($breed->save()){
        return $breed;
      }

      return NULL;
    }

    return $existingBreed;
  }

  /** getBreedById
  * Get a breed according to its id
  * @param int
  * @return Breed
  **/
  public function getBreedById(Request $request, Response $response, $id){
    $breed = Breed::find($id);
    if(!$breed){
      //return parent::notFound($request, $response, $id);
    }
    return $breed;
  }
}
