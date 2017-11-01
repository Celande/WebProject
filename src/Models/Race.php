<?php

/* http://laravel.sillo.org/les-relations-avec-eloquent-12/ */

use Eloquent;

class Race extends Eloquent {

    protected $table = 'race';
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

    public function goat(){
      this->hasMany('\src\Models\Goat', 'race_id'); // race_id usefull ?
    }

}
