<?php

/* http://laravel.sillo.org/les-relations-avec-eloquent-12/ */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/** Image
  * Model used for the image table
  **/
class Image extends Model {

    protected $table = 'image';
    protected $timestamp = false; // No need for date
    protected $softDelete = false;
    protected $guarded = array('id');

    /** goat
    * Used to set the relation between the breed table and the goat table
    * Each image has ONE goat
  **/
    public function goat(){
      $this->belongsTo('App\Models\Goat');
    }

    /** breed
    * Used to set the relation between the breed table and the goat table
    * Each image has ONE breed
  **/
    public function breed(){
      $this->belongsTo('App\Models\Breed');
    }

}
