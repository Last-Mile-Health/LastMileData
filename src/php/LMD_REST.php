<?php

/*
    URL                             HTTP METHOD     OPERATION
    ---                             -----------     ---------
    LMD_REST.php/routeName/         GET             Returns an array of objects
    LMD_REST.php/routeName/:id      GET             Returns the object with id of :id
    LMD_REST.php/routeName/:ids     GET             Returns an array of objects with ids of :ids (":ids" should be a comma-separated list)
    LMD_REST.php/routeName/         POST            Adds a new object (returns id)
    LMD_REST.php/routeName/:id      PUT             Updates (or creates) the object with id of :id (returns id)
    LMD_REST.php/routeName/:id      DELETE          Deletes the object with id of :id (returns id) !!!!! if needed, modify DELETE to handle :ids !!!!!


    ROUTE                           TABLE
    ---                             -----
    LMD_REST.php/indicators/        dataportal.tbl_indicators
    LMD_REST.php/indicatorvalues/   dataportal.tbl_indicators
*/


// Set include path; require connection strings; instantiate Slim
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );
require_once("../../../LastMileData/lib/Slim-2.6.2/Slim/Slim.php");
require_once("cxn.php");
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


// Route 1: (dataportal.tbl_indicators)
$app->get('/indicators/(:id)',function($id='all') {
    LMD_get($id, "indID", "dataportal.tbl_indicators");
});
$app->post('/indicators/', function() {
    LMD_post("dataportal.tbl_indicators");
});
$app->put('/indicators/:id', function($id) {
    LMD_put($id, "indID", "dataportal.tbl_indicators");
});
$app->delete('/indicators/:id', function($id) {
    LMD_delete($id, "indID", "dataportal.tbl_indicators");
});


// Route 2: (dataportal.tbl_values)
// Note: different ID field for GET requests vs. PUTs/DELETEs (non-standard behavior)
$app->get('/indicatorvalues/(:id)',function($id='all') {
    LMD_get($id, "indID", "dataportal.tbl_values");
});
$app->post('/indicatorvalues/', function() {
    LMD_post("dataportal.tbl_indicators");
});
$app->put('/indicatorvalues/:id', function($id) {
    LMD_put($id, "id", "dataportal.tbl_values");
});
$app->delete('/indicatorvalues/:id', function($id) {
    LMD_delete($id, "id", "dataportal.tbl_values");
});


// Route 3: (dataportal.sidebar)
// Note: this stores and retrieves the object representing the data portal sidebar
$app->get('/json_objects/:id',function($id) {
    LMD_get($id, "id", "dataportal.tbl_json_objects");
});
$app->put('/json_objects/:id', function($id) {
    LMD_put($id, "id", "dataportal.tbl_json_objects");
});


// Run Slim
$app->run();


// Handles GET requests
function LMD_get($id, $idFieldName, $table) {
    try {
        $whereClause = ($id == 'all') ? 1 : "$idFieldName IN ($id)" ;
        $cxn = getCXN();
        $query = "SELECT * FROM $table WHERE $whereClause";
        $result = mysqli_query($cxn, $query);
        
        if (mysqli_num_rows($result)==1 ) {
            // Result set contains just one row
            echo json_encode(mysqli_fetch_all($result,MYSQLI_ASSOC)[0]);
        } else {
            // Result set contains multiple rows
            echo json_encode(mysqli_fetch_all($result,MYSQLI_ASSOC));
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
            $query .= "$key = '" . addslashes($value) . "', " ;
        }
        $query = substr($query, 0, -2) ;
        echo mysqli_query($cxn, $query) ? mysqli_insert_id($cxn) : 0;
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
        $bodyDecoded = $app->request->put();
        $cxn = getCXN();
        
        $query = "REPLACE INTO $table SET `$idFieldName`='" . $id . "', ";
        foreach ($bodyDecoded as $key => $value) {
            $query .= "$key = '" . addslashes($value) . "', " ;
        }
        $query = substr($query, 0, -2) ;
        echo mysqli_query($cxn, $query) ? $id : 0;
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
        echo mysqli_query($cxn, $query) ? $id : 0;
        mysqli_close($cxn);
    }
    catch(ErrorException $e) {
        // !!!!! Build out error handler !!!!!
        echo '{"error":{"text":'. $e .'}}';
    }
}
