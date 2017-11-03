<?php
/** Create the needed tables **/
/* See the database.sql file */
/* See https://laravel.com/docs/5.0/schema */

use Illuminate\Database\Capsule\Manager as Capsule;
use \Illuminate\Database\Schema\Blueprint as Blueprint;
use Illuminate\Support\Facades\DB;

use App\Models\Race;
use App\Models\Goat;

function createTable (Capsule $capsule){

  //echo " CREATE_TABLE ";

  /* race table */
  //$capsule::schema()->dropIfExists('goat'); // because of foreign key
  //$capsule::schema()->dropIfExists('race');

  // Create race table
  if (!$capsule::schema()->hasTable('race')) {

    $capsule::schema()->create('race', function (Blueprint $table) {
      $table->increments('id'); // already create the integer and the primary key

      $table->string('name');
      $table->float('height')->comment('cm');
      $table->float('weight')->comment('cm');
      $table->string('color');
      $table->string('origin');
      $table->float('hair_growth')->nullable()->comment('cm/month');
      $table->float('milk_by_lactation')->nullable()->comment('l');
      $table->integer('duration_of_lactation')->nullable()->comment('month');
      $table->string('exploitation');
      /*$table->enum('exploitation',['milk', 'cheese', 'hair', 'meat', 'pet']);*/

      $table->unique('name');
    });

    $capsule::table('race')->insert([
      'name' => 'Saanen',
      'height' => '85',
      'weight' => '72.5',
      'color' => 'white',
      'origin' => 'Switzerland',
      'milk_by_lactation' => '800',
      'duration_of_lactation' => '9.3',
      'exploitation' => 'milk'
    ]);

    $capsule::table('race')->insert([
      'name' => 'Pygmy goat',
      'height' => '50',
      'weight' => '30',
      'color' => 'caramel pattern, agouti pattern, and black pattern',
      'origin' => 'Central and West Africa',
      'exploitation' => 'pet'
    ]);

    $capsule::table('race')->insert([
      'name' => 'Angora',
      'height' => '65',
      'weight' => '45',
      'color' => 'white',
      'origin' => 'Central Asia',
      'hair_growth' => '2.5',
      'exploitation' => 'hair'
    ]);
  }

  // Create goat table

  if (!$capsule::schema()->hasTable('goat')) {
    $capsule::schema()->create('goat', function (Blueprint $table) {
      $table->increments('id');

      $table->string('name');
      $table->float('price')->comment('€');
      $table->date('birthdate');
      $table->integer('race_id')->unsigned();
      $table->enum('gender', ['male', 'female']);
      $table->string('localisation');
      $table->string('identification')->nullable();
      $table->text('description');

      $table->foreign('race_id')->references('id')->on('race');

      $table->timestamps();
    });

    $capsule::table('goat')->insert([
      'name' => 'Pupuce',
      'price' => '100',
      'birthdate' => '2017-08-30',
      'race_id' => '1',
      'gender' => 'female',
      'localisation' => 'Lyon - France',
      'identification' => 'FR 001 001 00001',
      'description' => 'Young goat loving corn.'
    ]);

    $capsule::table('goat')->insert([
      'name' => 'George',
      'price' => '200',
      'birthdate' => '2014-02-14',
      'race_id' => '2',
      'gender' => 'male',
      'localisation' => 'Langre - France',
      'identification' => 'FR 002 002 00002',
      'description' => 'Manly male, stubborn, kicker and go-ahead type.'
    ]);

    $capsule::table('goat')->insert([
      'name' => 'Laurel',
      'price' => '300.5',
      'birthdate' => '2013-03-15',
      'race_id' => '3',
      'gender' => 'female',
      'localisation' => 'Chaumont - France',
      'identification' => 'FR 003 003 00003',
      'description' => 'Pretty little nanny, sleeping on feathers only.'
    ]);
  }


  return $capsule;
}
