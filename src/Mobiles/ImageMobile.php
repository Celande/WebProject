<?php

namespace App\Mobiles;

use App\Models\Image;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;

/** ImageMobile
* Mobile of the Image Model
**/
class ImageMobile extends CommonMobile
{

  /** getImagesByType
  * Get an image according to its type
  * @param 'goat' or 'breed'
  * @return Image
  **/
  private function getImagesByType($request, $response, $type){
    if($type != 'goat' && $type != 'breed'){
      //return parent::notFound($request, $response, $type);
      return NULL;
    }
    return Image::where('type', 'like', $type)->get();
  }

  /** getGoatImages
  * Get goat image
  * @return Image[]
  **/
  public function getGoatImages($request, $response){
    $img = ImageMobile::getImagesByType($request, $response, 'goat');
    /*
    if(!$img){
      return parent::notFound($request, $response, NULL);
    }
    */
    return $img;
  }

  /** getBreedImages
  * Get breed image
  * @return Image[]
  **/
  public function getBreedImages($request, $response){
    $img = ImageMobile::getImagesByType($request, $response, 'breed');
    /*
    if(!$img){
      return parent::notFound($request, $response, NULL);
    }
    */
    return $img;
  }

  /** getImageById
  * Get a image according to its id
  * @param int
  * @return Image
  **/
  public function getImageJsonById(Request $request, Response $response, $args){
    $id = $request->getAttribute('id');
    $img = Image::find($id);
    $array = array();
    $array[] = $img;

    $response = $response->withHeader('Access-Control-Allow-Origin', '*');
    return $response->withJson($array);
  }

  public function getImageById(Request $request, Response $response, $id){
    //$id = $request->getAttribute('id');
    $img = Image::find($id);

    return $img->path . $img->type . $img->num . '.' . $img->ext;
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

    /** moveUploadedFile
  * Add a file to the project
  * @param $directory
  * @param $uploadedFile
  * @return $result
  **/
  public function moveUploadedFile($request, $response, $directory, UploadedFile $uploadedFile)
  {
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = "";

    // Result returned
    $result = array();
    $result['filename'] = NULL;
    $result['id'] = NULL;

    // No image except for the default
    if(ImageMobile::getGoatImages($request, $response)->count() <= 1){
      $basename = "goat1";
    } else {
      // Get last entry
      $lastImg = ImageMobile::getLastImageByType('goat');
      if($lastImg == NULL){
        return result;
      }
      $lastNum = $lastImg->num;
      $num = $lastNum + 1;
      $basename = "goat".$num;
    }
    // Create filename
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $path = __DIR__ . "/../../public/" . $directory;
    /* Testing the path on the server
    if(is_writable($path)) echo $path . " is Writable!";
    else echo $path . "is NOT writable!";
    exit;
    */

    // Upload file
    $uploadedFile->moveTo($path . $filename);

    // Add to DB
    $image = ImageMobile::addImage($directory, 'goat', $num, $extension);
    if($image != NULL){
      $result['filename'] = $filename;
      $result['id'] = $image->id;
    }
    return $result;
  }
}
