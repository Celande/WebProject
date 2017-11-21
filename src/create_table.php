<?php
/** Create the needed tables **/
/* See the database.sql file */
/* See https://laravel.com/docs/5.0/schema */

use Illuminate\Database\Capsule\Manager as Capsule;
use \Illuminate\Database\Schema\Blueprint as Blueprint;
use Illuminate\Support\Facades\DB;

use App\Models\Breed;
use App\Models\Goat;

/** create_table
  * Create the tables in the DB
  * @param Capsule $capsule
  * @return Capsule $capsule
  **/
function create_table (Capsule $capsule, $img_breed, $img_goat){
  /*** ***** Breed Table ***** ***/
  //$capsule::schema()->dropIfExists('goat'); // because of foreign key
  $capsule::schema()->dropIfExists('race');
  //$capsule::schema()->dropIfExists('breed');
  //$capsule::schema()->dropIfExists('image');

  // Create img table
  if(!$capsule::schema()->hasTable('image')){
    $capsule::schema()->create('image', function(Blueprint $table){
      $table->increments('id');

      $table->string('path');
      $table->enum('type',['breed', 'goat']);
      $table->integer('num');
      $table->enum('ext', ['jpg', 'jpeg', 'png'])->comment('extension');

      $table->timestamp('created_at')->nullable();
      $table->timestamp('updated_at')->nullable();
    });

    // Fill the image table
    $capsule::table('image')->insert([
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '1',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '2',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '3',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([
      'path' => $img_goat,
      'type' => 'goat',
      'num' => '1',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([
      'path' => $img_goat,
      'type' => 'goat',
      'num' => '2',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([
      'path' => $img_goat,
      'type' => 'goat',
      'num' => '3',
      'ext' => 'jpg'
    ]);
  }

  // Create breed table
  if (!$capsule::schema()->hasTable('breed')) {

    $capsule::schema()->create('breed', function (Blueprint $table) {
      $table->increments('id'); // already create the integer and the primary key

      $table->string('name');
      $table->float('height')->comment('cm');
      $table->float('weight')->comment('kg');
      $table->string('color');
      $table->string('origin');
      $table->float('hair_growth')->nullable()->comment('cm/month');
      $table->float('milk_by_lactation')->nullable()->comment('l');
      $table->integer('duration_of_lactation')->nullable()->comment('month');
      $table->enum('exploitation',['milk', 'cheese', 'hair', 'meat', 'pet', 'environment']);
      $table->integer('img_id')->unsigned()->comment('300x300px max')->nullable();

      $table->unique('name');
      $table->foreign('img_id')->references('id')->on('image');
    });

    // Fill the breed table
    $capsule::table('breed')->insert([
      'name' => 'Saanen',
      'height' => '85',
      'weight' => '72.5',
      'color' => 'white',
      'origin' => 'Switzerland',
      'milk_by_lactation' => '800',
      'duration_of_lactation' => '9.3',
      'exploitation' => 'milk',
      'img_id' => '1'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Pygmy goat',
      'height' => '50',
      'weight' => '30',
      'color' => 'caramel pattern, agouti pattern, and black pattern',
      'origin' => 'Central and West Africa',
      'exploitation' => 'pet',
      'img_id' => '2'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Angora',
      'height' => '65',
      'weight' => '45',
      'color' => 'white',
      'origin' => 'Central Asia',
      'hair_growth' => '2.5',
      'exploitation' => 'hair',
      'img_id' => '3'
    ]);

  $capsule::table('breed')->insert([
      'name' => 'Thüringer goat',
      'height' => '80',
      'weight' => '60',
      'color' => 'chocolate, grey',
      'origin' => 'Germany',
      'milk_by_lactation' => '800',
      'exploitation' => 'milk',
      'img_id' => '3'
    ]);

  $capsule::table('breed')->insert([
      'name' => 'Bunte Deutsche Edelziege',
      'height' => '80',
      'weight' => '75',
      'color' => 'brown',
      'origin' => 'Germany',
      'milk_by_lactation' => '1000',
      'exploitation' => 'milk',
      'img_id' => '3'
    ]);

  $capsule::table('breed')->insert([
      'name' => 'Weiße Deutsche Edelziege',
      'height' => '80',
      'weight' => '75',
      'color' => 'white',
      'origin' => 'Germany',
      'milk_by_lactation' => '1000',
      'duration_of_lactation' => '8',
      'exploitation' => 'milk',
      'img_id' => '3'
    ]);

  $capsule::table('breed')->insert([
      'name' => 'Appenzell goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'white',
      'origin' => 'Switzerland',
      'exploitation' => 'milk',
      'img_id' => '3'
    ]);

  $capsule::table('breed')->insert([
      'name' => 'Gray rays goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'black with white rays on the head',
      'origin' => 'Switzerland',
      'exploitation' => 'milk', // and meat
      'img_id' => '3'
    ]);

  $capsule::table('breed')->insert([
      'name' => 'Alpes goat',
      'height' => '0',
      'weight' => '75',
      'color' => 'brown, black',
      'origin' => 'Switzerland',
      'milk_by_lactation' => '780',
      'exploitation' => 'milk', // and meat
      'img_id' => '3'
    ]);

  $capsule::table('breed')->insert([
      'name' => 'Peacock goat',
      'height' => '60',
      'weight' => '75',
      'color' => 'black and white',
      'origin' => 'Switzerland',
      'milk_by_lactation' => '538',
      'duration_of_lactation' => '8',
      'exploitation' => 'milk', // and meat
      'img_id' => '3'
    ]);

  $capsule::table('breed')->insert([
      'name' => 'Booted goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'brown',
      'origin' => 'Switzerland',
      'exploitation' => 'milk',
      'img_id' => '3'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Toggenbourg goat',
      'height' => '75',
      'weight' => '55',
      'color' => 'brown',
      'origin' => 'Switzerland',
      'milk_by_lactation' => '780',
      'duration_of_lactation' => '9',
      'exploitation' => 'milk',
      'img_id' => '3'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Valais black collar goat',
      'height' => '80',
      'weight' => '55',
      'color' => 'black and white',
      'origin' => 'Switzerland',
      'milk_by_lactation' => '440',
      'exploitation' => 'environment',
      'img_id' => '3'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Tennessee goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'any',
      'origin' => 'USA',
      'exploitation' => 'meat',
      'img_id' => '3'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Bush goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'any',
      'origin' => 'USA',
      'exploitation' => 'meat',
      'img_id' => '3'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Kiko',
      'height' => '0',
      'weight' => '0',
      'color' => 'any',
      'origin' => 'New-Zealand',
      'exploitation' => 'meat',
      'img_id' => '3'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Poitevine',
      'height' => '75',
      'weight' => '70',
      'color' => 'brown, black',
      'origin' => 'France',
      'milk_by_lactation' => '538',
      'duration_of_lactation' => '8.3',
      'exploitation' => 'milk',
      'img_id' => '3'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Corse',
      'height' => '0',
      'weight' => '50',
      'color' => 'any',
      'origin' => 'France',
      'exploitation' => 'milk',
      'img_id' => '3'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Ditches goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'any',
      'origin' => 'France',
      'exploitation' => 'milk', // and meat
      'img_id' => '3'
    ]);

    // TO FINISH

    $capsule::table('breed')->insert([
      'name' => 'Lorraine goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'gray',
      'origin' => 'France',
      'exploitation' => 'milk',
      'img_id' => '3'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Massif Central goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'any',
      'origin' => 'France',
      'exploitation' => 'milk',
      'img_id' => '3'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Provence goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'any',
      'origin' => 'France',
      'exploitation' => 'milk',
      'img_id' => '3'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Pyrenees goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'black, brown',
      'origin' => 'France',
      'exploitation' => 'milk', // and meat
      'img_id' => '3'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Rove goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'brown',
      'origin' => 'France',
      'exploitation' => 'milk', // and meat
      'img_id' => '3'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Catalonia goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'brown',
      'origin' => 'France',
      'exploitation' => 'milk',
      'img_id' => '3'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Savoie goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'any',
      'origin' => 'France',
      'exploitation' => 'milk', // and meat
      'img_id' => '3'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Sundgau goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'black and white',
      'origin' => 'France',
      'exploitation' => 'milk', // and meat
      'img_id' => '3'
    ]);
  }

/*** ***** Goat Table ***** ***/
  // Create goat table
  if (!$capsule::schema()->hasTable('goat')) {
    $capsule::schema()->create('goat', function (Blueprint $table) {
      $table->increments('id');

      $table->string('name');
      $table->float('price')->comment('€');
      $table->date('birthdate');
      $table->integer('breed_id')->unsigned();
      $table->enum('gender', ['male', 'female']);
      $table->string('localisation');
      $table->string('identification');
      $table->text('description');
      $table->integer('img_id')->nullable()->unsigned();

      $table->timestamps();
      //$table->touch();
      // need updated_at

      $table->foreign('breed_id')->references('id')->on('breed');
      $table->foreign('img_id')->references('id')->on('image');
    });

    // Fill the goat table
    $capsule::table('goat')->insert([
      'name' => 'Pupuce',
      'price' => '100',
      'birthdate' => '2017-08-30',
      'breed_id' => '1',
      'gender' => 'female',
      'localisation' => 'Lyon - France',
      'identification' => 'FR 001 001 00001',
      'description' => 'Young goat loving corn.',
      'created_at' => '2017-10-05',
      'updated_at' => '2017-10-05'
    ]);

    $capsule::table('goat')->insert([
      'name' => 'George',
      'price' => '200',
      'birthdate' => '2014-02-14',
      'breed_id' => '2',
      'gender' => 'male',
      'localisation' => 'Langre - France',
      'identification' => 'FR 002 002 00002',
      'description' => 'Manly male, stubborn, kicker and go-ahead type.',
      'created_at' => '2017-10-05',
      'updated_at' => '2017-10-05'
    ]);

    $capsule::table('goat')->insert([
      'name' => 'Laurel',
      'price' => '300.5',
      'birthdate' => '2013-03-15',
      'breed_id' => '3',
      'gender' => 'female',
      'localisation' => 'Chaumont - France',
      'identification' => 'FR 003 003 00003',
      'description' => 'Pretty little nanny, sleeping on feathers only.',
      'created_at' => '2017-10-05',
      'updated_at' => '2017-10-05'
    ]);
  }

  return $capsule;
}
