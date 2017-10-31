<?php
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;
$capsule->addConnection([
	'driver' => 'mysql',
	'database' => __DIR__.'/../database.sql',
	'prefix' => ''
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();
