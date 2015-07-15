<?php
/*
    URL                             HTTP METHOD     OPERATION
    ---                             -----------     ---------
    test_rest.php/routeName/         GET             Returns an array of objects
    test_rest.php/routeName/:id      GET             Returns the object with id of :id
    test_rest.php/routeName/:ids     GET             Returns an array of objects with ids of :ids (":ids" should be a comma-separated list)
    test_rest.php/routeName/         POST            Adds a new object (returns id)
    test_rest.php/routeName/:id      PUT             Updates (or creates) the object with id of :id (returns id)
    test_rest.php/routeName/:id      DELETE          Deletes the object with id of :id (returns id) !!!!! if needed, modify DELETE to handle :ids !!!!!
*/


// Set include path; require connection strings; instantiate Slim
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php-html/includes" );
require_once("../../LastMileData/lib/Slim-2.6.2/Slim/Slim.php");
require_once("cxn.php");
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


// Route 1: (test_rest.tbl_users)
$app->get('/api1/(:id)',function($id='all') {
    LMD_get($id, "user_id", "test_rest.tbl_users", 1);
});
$app->post('/api1/', function() {
    LMD_post("test_rest.tbl_users");
});
$app->put('/api1/:id', function($id) {
    LMD_put($id, "user_id", "test_rest.tbl_users");
});
$app->delete('/api1/:id', function($id) {
    LMD_delete($id, "user_id", "test_rest.tbl_users");
});


// Run Slim
$app->run();


// Handles GET requests
function LMD_get($id, $idFieldName, $table, $whereFilter) {
    try {
        $whereClause = ($id == 'all') ? 1 : "$idFieldName IN ($id)" ;
        $whereClause .= " AND " . $whereFilter;
        $cxn = getCXN();
        $query = "SELECT * FROM $table WHERE $whereClause";
        $result = mysqli_query($cxn, $query);
        $resultSet = array();
        
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($resultSet,$row);
        }

        if (mysqli_num_rows($result)==1 ) {
            // Result set contains just one row
            echo json_encode($resultSet[0]);
        } else {
            // Result set contains multiple rows
            echo json_encode($resultSet);
        }
        
        mysqli_close($cxn);
    }
    catch(ErrorException $e) {
        // !!!!! Build out error handler !!!!!
        echo '{"error":{"text":'. $e .'}}';
    }
}


// Handles POST requests
// !!!!! test empty request !!!!!
function LMD_post($table) {
    try {
        $app = \Slim\Slim::getInstance();
        $body = $app->request->getBody();
	$bodyDecoded = json_decode($body) ? json_decode($body) : $_POST;
        $cxn = getCXN();

        $query = "INSERT INTO $table SET ";
        foreach ($bodyDecoded as $key => $value) {
            $query .= "`$key` = '" . addslashes($value) . "', " ;
        }
        $query = substr($query, 0, -2) ;
        if (mysqli_query($cxn, $query)) {
            echo mysqli_insert_id($cxn);
        } else {
            header("HTTP/1.1 404 Not Found"); // !!!!! change this to 500 / Internal server error !!!!!
        }
        mysqli_close($cxn);
    }
    catch(ErrorException $e) {
        // !!!!! Build out error handler !!!!!
        echo '{"error":{"text":'. $e .'}}';
    }
}


// Handles PUT requests
// !!!!! test empty request !!!!!
function LMD_put($id, $idFieldName, $table) {
    try {
        $app = \Slim\Slim::getInstance();
	$bodyDecoded = $app->request->put() ? $app->request->put() : json_decode($app->request->getBody());
        $cxn = getCXN();
        
        $query = "REPLACE INTO $table SET ";
        foreach ($bodyDecoded as $key => $value) {
            $query .= "`$key` = '" . addslashes($value) . "', " ;
        }
        // If id string has not already been included in request body, add it
        if (substr_count($query, "`$idFieldName`")==0) {
            $query .= "`$idFieldName`='" . $id . "', ";
        }
        $query = substr($query, 0, -2) ;
        if (mysqli_query($cxn, $query)) {
            echo $id;
        } else {
            header("HTTP/1.1 404 Not Found"); // !!!!! change this to 500 / Internal server error !!!!!
        }
        mysqli_close($cxn);
    }
    catch(ErrorException $e) {
        // !!!!! Build out error handler !!!!!
        echo '{"error":{"text":'. $e .'}}';
    }
}


// Handles DELETE requests
function LMD_delete($id, $idFieldName, $table) {
    try {
        $cxn = getCXN();
        $query = "DELETE FROM $table WHERE `$idFieldName`=$id";
        $result = mysqli_query($cxn, $query);
        if (mysqli_query($cxn, $query)) {
            echo $id;
        } else {
            header("HTTP/1.1 404 Not Found"); // !!!!! change this to 500 / Internal server error !!!!!
        }
        mysqli_close($cxn);
    }
    catch(ErrorException $e) {
        // !!!!! Build out error handler !!!!!
        echo '{"error":{"text":'. $e .'}}';
    }
}
