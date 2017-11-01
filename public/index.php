<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/* Init */
//require __DIR__ .'/../config/database.php'

require __DIR__ .'/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

/* Container */
// see dependencies.php

/* Class */
spl_autoload_register(function ($classname) {
  require (__DIR__ . '/../src/Models/' . $classname . ".php");
});

/* Database */
//$this->db; // establish db conncection

/** Routes **/

$app->get('/hello/{name}', function (Request $request, Response $response) {
  $name = $request->getAttribute('name');
/*
  $response->getBody()->write("Hello, $name");

  return $response;
  */

    return $this->view->render($response, 'home.twig', [
        'name' => $name
    ]);

    // for an array of data with args as parameter: 'name' => $args['name']
});

/* Get all goat races from the database
$app->get('/races', function() {
  require_once(__DIR__ .'/../config/database.php');
  $query = "select name from race order by id";
  $result = $connection->query($query);
  // var_dump($result);
  while ($row = $result->fetch_assoc()){
    $data[] = $row;
  }

  echo json_encode($data);

});
*/

/* Get info on one goat race from the database
$app->get('/races/{id}', function(Request $request, Response $response) {
  require_once(__DIR__ .'/../config/database.php');

  $race_id = $request->getAttribute('id');

  $query = sprintf("SELECT * FROM race WHERE id = '%d'",
mysql_real_escape_int($race_id));
  $result = $connection->query($query);
  // var_dump($result);
  while ($row = $result->fetch_assoc()){
    $data[] = $row;
  }

  //echo json_encode($data);
  $response->getBody()->write(ùdata);

  return $response;

});
*/

/* Get all available goats from the database
$app->get('/goats', function() {
  require_once(__DIR__ .'/../config/database.php');
  $query = "SELECT name FROM goat ORDER BY created_at";
  $result = $connection->query($query);
  // var_dump($result);
  while ($row = $result->fetch_assoc()){
    $data[] = $row;
  }

  echo json_encode($data);

});
*/

/* Get info on one goat from the database
$app->get('/goats/{id}', function(Request $request, Response $response) {
  require_once(__DIR__ .'/../config/database.php');

  $goat_id = $request->getAttribute('id');

  $query = sprintf("SELECT * FROM goat WHERE id = '%d'",
mysql_real_escape_int($goat_id));
  $result = $connection->query($query);
  // var_dump($result);
  while ($row = $result->fetch_assoc()){
    $data[] = $row;
  }

  //echo json_encode($data);
  $response->getBody()->write(ùdata);

  return $response;

});
*/

/* Add goat to the database
$app->post('/goats/add', function($request){

  require_once(__DIR__ .'/../config/database.php');
  $race_name = 'fred';

  $query_race = sprintf("SELECT id FROM race WHERE name = '%s'",
  mysql_real_escape_string($race_name));

  $result = mysql_query($query_race);

  if (!$result) {
    $message  = 'Invalid request : ' . mysql_error() . "\n";
    $message .= 'Completed request : ' . $query_race;
    die($message);
  }

  while ($row = mysql_fetch_assoc($result)) {
    $data[] = $row;
  }

  $query = "INSERT INTO goat (name, price, birthdate, race_id, gender, localisation, identification, description) VALUES (?,?,?,?,?,?,?,?)";

  $stmt = $connection->prepare($query);

  $stmt->bind_param("sss", $name, $price, $birthdate, $race_id, $gender, $localisation, $identification, $description);

  $name = $request->getParsedBody()['name'];
  $price = $request->getParsedBody()['price'];
  $birthdate = $request->getParsedBody()['birthdate'];
  $race_id = $data; //$request->getParsedBody()['race_id']; // need a query based on the name
  $gender = $request->getParsedBody()['gender'];
  $localisation = $request->getParsedBody()['localisation'];
  $identification = $request->getParsedBody()['identification'];
  $description = $request->getParsedBody()['description'];

  $stmt->execute();

});
*/

/* Search for goats in the database
$app->post('/goats/search', function($request){

  require_once(__DIR__ .'/../config/database.php');
  $race_name = 'fred';

  $query_race = sprintf("SELECT id FROM race WHERE name = '%s'",
  mysql_real_escape_string($race_name));

  $result = mysql_query($query_race);

  if (!$result) {
    $message  = 'Invalid request : ' . mysql_error() . "\n";
    $message .= 'Completed request : ' . $query_race;
    die($message);
  }

  while ($row = mysql_fetch_assoc($result)) {
    $data[] = $row;
  }

  $query = sprintf("SELECT * FROM goat WHERE
    CONTAINS(name, '%s'),
    price < '%d', price > '%d',
    birthdate < '', birthdate > '', r
    ace_id = '%d',
    gender = '%s',
    CONTAINS(localisation, '%s'),
    identification = '%d'",
    mysql_real_escape_string($race_name));

    $stmt = $connection->prepare($query);

    $stmt->bind_param("sss", $name, $price, $birthdate, $race_id, $gender, $localisation, $identification);

    $name = $request->getParsedBody()['name'];
    $price = $request->getParsedBody()['price'];
    $birthdate = $request->getParsedBody()['birthdate'];
    $race_id = $data; //$request->getParsedBody()['race_id']; // need a query based on the name
    $gender = $request->getParsedBody()['gender'];
    $localisation = $request->getParsedBody()['localisation'];
    $identification = $request->getParsedBody()['identification'];

    $stmt->execute();

  });
*/
  $app->run();
