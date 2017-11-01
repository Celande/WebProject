<?php
/** Create the needed tables **/
/* See the database.sql file */
/* See https://laravel.com/docs/5.0/schema */

use Illuminate\Database\Capsule\Manager as Capsule;
use \Illuminate\Database\Schema\Blueprint as Blueprint

/* race table */
$capsule = new Capsule;

$capsule::schema()->dropIfExists('race');

$capsule::schema()->create('race', function (Blueprint $table) {
  $table->increments('id'); // already create the integer and the primary key

  $table->string('name');
  $table->float('height')->comment('cm');
  $table->float('weight')->comment('cm');
  $table->string('color');
  $table->string('origin');
  $table->float('hair_growth')->nullable()->comment('cm');
  $table->float('milk_by_lactation')->nullable()->comment('l');
  $table->integer('duration_of_lactation')->nullable()->comment('month');
  $table->string('exploitation');
  /*$table->enum('exploitation',['milk', 'cheese', 'hair', 'meat', 'pet']);*/

  $table->timestamps();
});

/*

$components_table->insert([
["component" => "website"],
["component" => "mobile app"]
]);
$components_table->saveData();
*/

/* goat table */
$capsule = new Capsule;

$capsule::schema()->dropIfExists('goat');

$capsule::schema()->create('goat', function (Blueprint $table) {
  $table->increments('id');

  $table->string('name');
  $table->float('price')->comment('€');
  $table->date('birthdate');
  $table->integer('race_id')->unsigned();
  $table->enum('gender', ['male', 'female']);
  $table->string('localisation');
  $table->string('identifiaction');
  $table->text('description');

  $table->timestamps();
});

$capsule::schema()->table('goat', function(Blueprint $table) {
  $table->foreign('race_id')->references('id')->on('race');

  $table->timestamps();
});
