<?php

//      URL                     HTTP Method  Operation
//      ---                     -----------  ---------
//      /users                  GET          Returns an array of users
//      /users/:id              GET          Returns the user with id of :id
//      /users                  POST         Adds a new user (returns id)
//      /users/:id              PUT          Updates (or creates) the user with id of :id (returns id)
//      /users/:id              DELETE       Deletes the contact with id of :id (returns id)

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );

// Slim instantiation, method assignment, and bootstrapping
require_once("../../LastMileData/lib/Slim-2.6.2/Slim/Slim.php");
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->get('/users','getAllUsers');
$app->get('/users/:id','getOneUser');
$app->post('/users', 'insertUser');
//$app->post('/users/:id', 'temp');
$app->put('/users/:id', 'updateUser');
$app->delete('/users/:id','deleteUpdate');
$app->run();

// GET: /LastMileData/testing/test_rest.php/users
function getAllUsers() {
    try {
        $cxn = getCXN();
        $query = "SELECT user_id,username,name,age FROM test_rest.users ORDER BY user_id DESC";
        $result = mysqli_query($cxn, $query);
//        echo '{"users": ' . json_encode(mysqli_fetch_all($result,MYSQLI_ASSOC)) . '}';
        echo json_encode(mysqli_fetch_all($result,MYSQLI_ASSOC));
        mysqli_close($cxn);
    }
    catch(ErrorException $e) {
        // !!!!! Build out error handler !!!!!
        echo '{"error":{"text":'. $e .'}}';
    }
}

// GET: /LastMileData/testing/test_rest.php/users/:id
function getOneUser($id) {
    try {
        $cxn = getCXN();
        $query = "SELECT user_id,username,name,age FROM test_rest.users WHERE user_id='$id'";
        $result = mysqli_query($cxn, $query);
        echo json_encode(mysqli_fetch_all($result,MYSQLI_ASSOC)[0]);
//        echo '{"users": ' . json_encode(mysqli_fetch_all($result,MYSQLI_ASSOC)) . '}';
        mysqli_close($cxn);
    }
    catch(ErrorException $e) {
        // !!!!! Build out error handler !!!!!
        echo '{"error":{"text":'. $e .'}}';
    }
}

// POST: /LastMileData/testing/test_rest.php/users
// Create a resource
// "The URI in a POST request identifies the resource that will handle the enclosed entity"
// "That resource might be a data-accepting process, a gateway to some other protocol, or a separate entity that accepts annotations."
// 
// "POST is used when the processing you wish to happen on the server should be repeated" (i.e. NOT 'idempotent')
function insertUser() {
    try {
        $app = \Slim\Slim::getInstance();
        $body = $app->request->getBody();
	$bodyDecode = json_decode($body) ? json_decode($body) : $_POST;
        extract($bodyDecode);
        $cxn = getCXN();
        $query = "INSERT INTO test_rest.users SET `username`='" . @$bodyUsername . "', `name`='" . @$bodyName . "'";
        echo mysqli_query($cxn, $query) ? mysqli_insert_id($cxn) : 0;
        mysqli_close($cxn);
    }
    catch(ErrorException $e) {
        // !!!!! Build out error handler !!!!!
        echo '{"error":{"text":'. $e .'}}';
    }
}

// PUT: /LastMileData/testing/test_rest.php/users/:id
// Update a resource (or create it if it doesn't exist)
// "In contrast, the URI in a PUT request identifies the entity enclosed with the request"
// " -- the user agent knows what URI is intended and the server MUST NOT attempt to apply the request to some other resource."
// 
// Each request should achieve the SAME result (i.e. 'idempotent')
// !!!!! BROKEN !!!!!
// !!!!! BROKEN !!!!!
// !!!!! BROKEN !!!!!
function updateUser($id) {
    try {
        $app = \Slim\Slim::getInstance();
        $body = $app->request->getBody();
        $bodyDecode = $app->request->put();
//        extract($bodyDecode);
//        $bodyID = $id;
//        $bodyName = $bodyDecode->name;
//        $bodyUsername = $bodyDecode->username;
        
//        $query = "INSERT INTO test_rest.users SET `username`='" . @$bodyUsername . "', `name`='" . @$bodyName . "'";
//        echo mysqli_query($cxn, $query) ? mysqli_insert_id($cxn) : 0;
//        mysqli_close($cxn);
        
        
        // Get PUT array
//        $app = \Slim\Slim::getInstance();
//        $PUT = $app->request->put();
        
//        $cxn = getCXN();
//        $query = "UPDATE test_rest.users SET `username`='$username', `name`='$name' WHERE `user_id`=$id";
//        $query = "REPLACE INTO test_rest.users SET `username`='$bodyUsername', `name`='$bodyName', `user_id`=$bodyID";
//        echo mysqli_query($cxn, $query) ? $bodyID : 0;
        print_r($app->request);
//        mysqli_close($cxn);
    }
    catch(ErrorException $e) {
        // !!!!! Build out error handler !!!!!
        echo '{"error":{"text":'. $e .'}}';
    }
}

// DELETE: /LastMileData/testing/test_rest.php/users/:user_id
function deleteUpdate($id) {
    try {
        $cxn = getCXN();
        $query = "DELETE FROM test_rest.users WHERE user_id=$id";
        $result = mysqli_query($cxn, $query);
        echo mysqli_query($cxn, $query) ? $id : 0;
        mysqli_close($cxn);
    }
    catch(ErrorException $e) {
        // !!!!! Build out error handler !!!!!
        echo '{"error":{"text":'. $e .'}}';
    }
}

// Database code
function getCXN() {
    $dbhost="localhost";$dbuser="lastmile_admin";$dbpass="LastMile14";$dbname="test_rest";
    $cxn = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die("Error");
    return $cxn;
}





//function temp($id) {
//    try {
//        extract($_POST);
//        $cxn = getCXN();
//        $query = "REPLACE INTO test_rest.users SET `username`='$username', `name`='$name', `user_id`=$id";
//        echo mysqli_query($cxn, $query) ? $id : 0;
//        mysqli_close($cxn);
//    }
//    catch(ErrorException $e) {
//        // !!!!! Build out error handler !!!!!
//        echo '{"error":{"text":'. $e .'}}';
//    }
//}
