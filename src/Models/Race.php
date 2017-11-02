<?php

/* http://laravel.sillo.org/les-relations-avec-eloquent-12/ */

// use Illuminate\Database\Eloquent   and   extends Eloquent

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Race extends Model {

    protected $table = 'race';
    public $timestamps = true;
    protected $softDelete = false;
    protected $guarded = array('id');

    public function goat(){
      $table->hasMany('\src\Models\Goat', 'race_id'); // race_id usefull ?
    }

}
