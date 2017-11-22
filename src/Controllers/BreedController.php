<?php

namespace App\Controllers;

use App\Models\Breed;
use App\Models\Image;

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
    $this->logger->addInfo("Route /breeds");

    // Get all breeds from DB
    $breeds = Breed::get();
    // Get all images from DB
    $imgs = Image::where('type', 'like', 'breed')->get();

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
    $this->logger->addInfo("Route /breeds/{id}");

    // Get the id from request
    $id = $request->getAttribute('id');
    // Get the breed according to the breed id
    $breed = Breed::find($id);
    // Get the image according to the id
    $img = Image::find($breed->img_id);

    // Can't find the breed, redirect to 404
    if(!$breed){
      return parent::notFound($request, $response, $args);
    }

    return $this->view->render($response, 'breeds.twig', array('breed' => $breed, 'img' => $img));
  }
}
