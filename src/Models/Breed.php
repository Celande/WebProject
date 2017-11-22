<?php

/* http://laravel.sillo.org/les-relations-avec-eloquent-12/ */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/** Breed
  * Model used for the breed table
  **/
class Breed extends Model {

    protected $table = 'breed';
    protected $timestamp = false; // No need for date
    protected $softDelete = false;
    protected $guarded = array('id');

    /** goats
    * Used to set up the relation between the goat table and the breed table
    * Each breed is in many goat
    **/
    public function goats(){
      return $this->hasMany('App\Models\Goat');
    }

}
