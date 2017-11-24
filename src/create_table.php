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
  //$capsule::schema()->dropIfExists('race');
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
    $capsule::table('image')->insert([ // 1
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '1',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 2
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '2',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 3
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '3',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 4
      'path' => $img_goat,
      'type' => 'goat',
      'num' => '1',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 5
      'path' => $img_goat,
      'type' => 'goat',
      'num' => '2',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 6
      'path' => $img_goat,
      'type' => 'goat',
      'num' => '3',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 7
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '4',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 8
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '5',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 9
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '6',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 10
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '7',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 11
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '8',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 12
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '9',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 13
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '10',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 14
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '11',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 15
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '12',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 16
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '13',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 17
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '14',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 18
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '15',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 19
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '16',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 20
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '17',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 21
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '18',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 22
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '19',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 23
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '20',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 24
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '21',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 25
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '22',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 26
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '23',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 27
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '24',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 28
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '25',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 29
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '26',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 30
      'path' => $img_breed,
      'type' => 'breed',
      'num' => '27',
      'ext' => 'jpg'
    ]);

    $capsule::table('image')->insert([ // 31
      'path' => $img_goat,
      'type' => 'goat',
      'num' => '0',
      'ext' => 'png'
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
      'img_id' => '7'
    ]);

  $capsule::table('breed')->insert([
      'name' => 'Bunte Deutsche Edelziege',
      'height' => '80',
      'weight' => '75',
      'color' => 'brown',
      'origin' => 'Germany',
      'milk_by_lactation' => '1000',
      'exploitation' => 'milk',
      'img_id' => '8'
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
      'img_id' => '9'
    ]);

  $capsule::table('breed')->insert([
      'name' => 'Appenzell goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'white',
      'origin' => 'Switzerland',
      'exploitation' => 'milk',
      'img_id' => '10'
    ]);

  $capsule::table('breed')->insert([
      'name' => 'Gray ray goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'black with white rays on the head',
      'origin' => 'Switzerland',
      'exploitation' => 'milk', // and meat
      'img_id' => '11'
    ]);

  $capsule::table('breed')->insert([
      'name' => 'Alpes goat',
      'height' => '0',
      'weight' => '75',
      'color' => 'brown, black',
      'origin' => 'Switzerland',
      'milk_by_lactation' => '780',
      'exploitation' => 'milk', // and meat
      'img_id' => '12'
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
      'img_id' => '13'
    ]);

  $capsule::table('breed')->insert([
      'name' => 'Booted goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'brown',
      'origin' => 'Switzerland',
      'exploitation' => 'milk',
      'img_id' => '14'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Toggenburg goat',
      'height' => '75',
      'weight' => '55',
      'color' => 'brown',
      'origin' => 'Switzerland',
      'milk_by_lactation' => '780',
      'duration_of_lactation' => '9',
      'exploitation' => 'milk',
      'img_id' => '15'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Valais black collar goat',
      'height' => '80',
      'weight' => '55',
      'color' => 'black and white',
      'origin' => 'Switzerland',
      'milk_by_lactation' => '440',
      'exploitation' => 'environment',
      'img_id' => '16'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Tennessee goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'any',
      'origin' => 'USA',
      'exploitation' => 'meat',
      'img_id' => '17'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Bush goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'any',
      'origin' => 'USA',
      'exploitation' => 'meat',
      'img_id' => '18'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Kiko',
      'height' => '0',
      'weight' => '0',
      'color' => 'any',
      'origin' => 'New-Zealand',
      'exploitation' => 'meat',
      'img_id' => '19'
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
      'img_id' => '20'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Corse',
      'height' => '0',
      'weight' => '50',
      'color' => 'any',
      'origin' => 'France',
      'exploitation' => 'milk',
      'img_id' => '21'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Ditches goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'any',
      'origin' => 'France',
      'exploitation' => 'milk', // and meat
      'img_id' => '22'
    ]);

    // TO FINISH

    $capsule::table('breed')->insert([
      'name' => 'Lorraine goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'gray',
      'origin' => 'France',
      'exploitation' => 'milk',
      'img_id' => '23'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Massif Central goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'any',
      'origin' => 'France',
      'exploitation' => 'milk',
      'img_id' => '24'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Provence goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'any',
      'origin' => 'France',
      'exploitation' => 'milk',
      'img_id' => '25'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Pyrenees goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'black, brown',
      'origin' => 'France',
      'exploitation' => 'milk', // and meat
      'img_id' => '26'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Rove goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'brown',
      'origin' => 'France',
      'exploitation' => 'milk', // and meat
      'img_id' => '27'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Catalonia goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'brown',
      'origin' => 'France',
      'exploitation' => 'milk',
      'img_id' => '28'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Savoie goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'any',
      'origin' => 'France',
      'exploitation' => 'milk', // and meat
      'img_id' => '29'
    ]);

    $capsule::table('breed')->insert([
      'name' => 'Sundgau goat',
      'height' => '0',
      'weight' => '0',
      'color' => 'black and white',
      'origin' => 'France',
      'exploitation' => 'milk', // and meat
      'img_id' => '30'
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
      'img_id' => '4',
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
      'img_id' => '5',
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
      'img_id' => '6',
      'created_at' => '2017-10-05',
      'updated_at' => '2017-10-05'
    ]);
  }

  return $capsule;
}
