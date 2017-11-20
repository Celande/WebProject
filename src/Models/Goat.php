<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** show_races
  * Model of the goat table
  **/
class Goat extends Model {

    protected $table = 'goat';
    public $timestamps = true;
    protected $softDelete = false;
    protected $guarded = array('id');

    /** race
    * Used to set up the relation between the goat table and the race table
    * Each goat has ONE race
  **/
    public function race(){
      return $this->belongsTo('App\Models\Race'); // race_id usefull ?
    }

}
