<?php

/* http://laravel.sillo.org/laravel-4-chapitre-34-les-relations-avec-eloquent-2-2/ */

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
  /** show_breeds
  * List of all the goat breeds
  * @param Request $request
  * @param Response $response
  * @param $args
  * @return $view
  **/
  public function show_breeds(Request $request, Response $response, $args){
    $this->logger->addInfo("Route /breeds");

    // Get all breeds from DB
    $breeds = Breed::get();
    $imgs = Image::where('type', 'like', 'breed')->get();

    return $this->view->render($response, 'breeds.twig',
        array('breeds' => $breeds, 'imgs' => $imgs)
          );

  }

  /** show_breed
  * Show data of one goat
  * @param Response $response
  * @param $args
  * @return $view
  **/
  public function show_breed(Request $request, Response $response, $args){
    $this->logger->addInfo("Route /breeds/{id}");

    // Get the id from request
    $id = $request->getAttribute('id');
    // Get the breed according to the breed id
    $breed = Breed::find($id);
    $img = Image::find($breed['img_id']);
    // Can't find the breed, redirect to 404
    if(!$breed){
      return parent::not_found($request, $response, $args);
    }

    return $this->view->render($response, 'breeds.twig', array('breed' => $breed, 'img' => $img));
  }
}
