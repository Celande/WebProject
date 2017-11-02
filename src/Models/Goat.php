<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goat extends Model {

    protected $table = 'goat';
    public $timestamps = true;
    protected $softDelete = false;
    protected $guarded = array('id');

    public function race(){
      $table->belongsTo('\src\Models\Race', 'race_id'); // race_id usefull ?
    }

}
