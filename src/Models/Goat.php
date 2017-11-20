<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** show_breeds
  * Model of the goat table
  **/
class Goat extends Model {

    protected $table = 'goat';
    public $timestamps = true;
    protected $softDelete = false;
    protected $guarded = array('id');

    /** breed
    * Used to set up the relation between the goat table and the breed table
    * Each goat has ONE breed
  **/
    public function breed(){
      return $this->belongsTo('App\Models\Breed'); // breed_id usefull ?
    }

}
