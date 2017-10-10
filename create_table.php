<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use \Illuminate\Database\Schema\Blueprint as Blueprint
$capsule = new Capsule;

    $capsule::schema()->dropIfExists('articles');

    $capsule::schema()->create('articles', function (Blueprint $table) {
        $table->increments('id');
        $table->string('title')->default('');
        //….
        $table->timestamps();
    });
