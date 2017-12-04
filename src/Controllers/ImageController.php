<?php

namespace App\Controllers;

use App\Models\Image;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/** ImageController
* Controller of the Image Model
**/
class ImageController extends CommonController
{

  /** getImagesByType
  * Get an image according to its type
  * @param 'goat' or 'breed'
  * @return Image
  **/
  private function getImagesByType($type){
    if($type != 'goat' && $type != 'breed'){
      return NULL;
    }
    return Image::where('type', 'like', $type)->get();
  }

  /** getGoatImages
  * Get goat image
  * @return Image[]
  **/
  public function getGoatImages(){
    return ImageController::getImagesByType('goat');
  }

  /** getBreedImages
  * Get breed image
  * @return Image[]
  **/
  public function getBreedImages(){
    return ImageController::getImagesByType('breed');
  }

  /** getImageById
  * Get a image according to its id
  * @param int
  * @return Image
  **/
  public function getImageById($id){
    return Image::find($id);
  }

  /** getDefaultImage
  * Get the default image
  * @return Image
  **/
  public static function getDefaultImage(){
    return Image::where('type', 'like', 'goat')
                  ->where('num', '=', '0')
                  ->first();
  }

  /** getAllImages
  * Get all images
  * @return Image[]
  **/
  public function getAllImages(){
    return Image::all();
  }

  /** removeImage
  * Delete the image in the DB and delete the file
  * @param $id
  * @return boolean
  **/
  public function removeImage($id){
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
    $file = "public/" . $img->path . $img->type . $img->num . "." . $img->ext;

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

  /** getLastImageByType
  * Get the last image entry according to type
  * @param 'goat' or 'breed'
  * @return Image
  **/
  public function getLastImageByType($type){
    if($type != 'goat' && $type != 'breed'){
      return NULL;
    }
    return Image::where('type', 'like', $type)
                  ->orderBy('num', 'desc')
                  ->first();
  }

  /** addImage
  * Add image to the DB
  * @param string
  * @param 'goat' or 'breed'
  * @param int
  * @param ext
  * @return Image
  **/
  public function addImage($path, $type, $num, $ext){
    $image = new Image;
    $image->path = $path;
    $image->type = $type;
    $image->num = $num;
    $image->ext = $ext;

    if($image->save()){
      return $image;
    }

    return NULL;
  }
}
