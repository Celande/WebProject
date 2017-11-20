<?php

/* http://laravel.sillo.org/les-relations-avec-eloquent-12/ */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/** Race
  * Model used for the race table
  **/
class Race extends Model {

    protected $table = 'race';
    protected $timestamp = false; // No need for date
    protected $softDelete = false;
    protected $guarded = array('id');

    /** goat
    * Used to set the relation between the race table and the goat table
    * Each goat has ONE race
  **/
    public function goats(){
      return $this->hasMany('App\Models\Goat'); // race_id usefull ?
    }

    /** image
    * Used to set the relation between the race table and the goat table
    * Each goat has ONE race
  **/
  /*
    public function image(){
      $table->belongsTo('\src\Models\Image', 'img_id'); // race_id usefull ?
    }
    */

}
