<?php

use Eloquent;

class Goat extends Eloquent {

    protected $table = 'goat';
    public $timestamps = true;
    protected $softDelete = false;
    protected $guarded = array('id');

/*
    public function villes()
    {
        return $this->hasMany('\Lib\Villes\Ville');
    }

    public function auteurs()
    {
        return $this->hasManyThrough('\Lib\Auteurs\Auteur', '\Lib\Villes\Ville');
    }
    */

    public function race(){
      return this->belongsTo('\src\Models\Race', 'race_id'); // race_id usefull ?
    }

}
