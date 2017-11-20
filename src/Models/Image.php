<?php

/* http://laravel.sillo.org/les-relations-avec-eloquent-12/ */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/** Breed
  * Model used for the breed table
  **/
class Image extends Model {

    protected $table = 'image';
    protected $timestamp = false; // No need for date
    protected $softDelete = false;
    protected $guarded = array('id');

    /** goat
    * Used to set the relation between the breed table and the goat table
    * Each goat has ONE breed
  **/
    public function goat(){
      $table->belongsTo('\src\Models\Goat', 'img_id'); // breed_id usefull ?
    }

    /** breed
    * Used to set the relation between the breed table and the goat table
    * Each goat has ONE breed
  **/
    public function breed(){
      $table->belongsTo('\src\Models\Breed', 'img_id'); // breed_id usefull ?
    }

}
