<?php

namespace App\Controllers;

use App\Models\Breed;
use App\Controllers\ImageController;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/** BreedController
* Controller of the Breed Model
**/
class BreedController extends CommonController
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
    $breeds = BreedController::getAllBreeds();
    // Get all images from DB
    $imgs = ImageController::getBreedImages($request, $response);

    return $this->view->render($response, 'breeds.twig',
        array('breeds' => $breeds, 'imgs' => $imgs)
          );
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
    $breed = BreedController::getBreedById($request, $response, $id);
    // Get the image according to the id
    $img = ImageController::getImageById($request, $response, $breed->img_id);

    return $this->view->render($response, 'breeds.twig', array('breed' => $breed, 'img' => $img));
  }

  /** getAllBreeds
  * Get all breeds
  * @return Breed[]
  **/
  public function getAllBreeds(){
    $breeds = Breed::all();
    /*
    if(!$breeds){
      return parent::notFound($request, $response, NULL);
    }
    */
    return $breeds;
  }

  /** getBreedByName
  * Get a breed according to its name
  * @param $name
  * @return Breed
  **/
  public function getBreedByName(Request $request, Response $response, $name){
    $breed = Breed::where('name', 'like', $name)
                  ->first();
    if(!$breed){
      return parent::notFound($request, $response, $name);
    }
    return $breed;
  }

  /** addBreed
  * Add a breed to the DB
  * @param $name
  * @return int or NULL
  **/
  public function addBreed(Request $request, Response $response, $name){
    $name = ucfirst($name);
    $existingBreed = BreedController::getBreedByName($request, $response, $name);
    if($existingBreed == NULL){
      $breed = new Breed;
      $breed->name = $name;
      $breed->height = 0;
      $breed->weight = 0;
      $breed->color = "N/A";
      $breed->origin = "N/A";

      if($breed->save()){
        return $breed->id;
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
      return parent::notFound($request, $response, $id);
    }
    return $breed;
  }
}
