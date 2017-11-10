<?php

/* http://laravel.sillo.org/les-relations-avec-eloquent-12/ */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/** Race
  * Model used for the race table
  **/
class Image extends Model {

    protected $table = 'image';
    protected $timestamp = false; // No need for date
    protected $softDelete = false;
    protected $guarded = array('id');

    /** goat
    * Used to set the relation between the race table and the goat table
    * Each goat has ONE race
  **/
    public function goat(){
      $table->belongsTo('\src\Models\Goat', 'img_id'); // race_id usefull ?
    }

    /** race
    * Used to set the relation between the race table and the goat table
    * Each goat has ONE race
  **/
    public function race(){
      $table->belongsTo('\src\Models\Race', 'img_id'); // race_id usefull ?
    }

}