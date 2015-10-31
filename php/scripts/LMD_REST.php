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
    LMD_REST.php/indicators/        lastmile_dataportal.tbl_indicators
    LMD_REST.php/indicatorvalues/   lastmile_dataportal.tbl_indicators
*/


// Set include path; require connection strings; instantiate Slim
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
require_once("../../lib/Slim-2.6.2/Slim/Slim.php");
require_once("cxn.php");
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


// Route 0: (lastmile_dataportal.tbl_testREST)
// For testing REST clients (3 columns: `id`, `name`, `age`)
$app->get('/test_rest/(:id)',function($id='all') {
    LMD_get($id, "id", "lastmile_dataportal.test_rest", 1);
});
$app->post('/test_rest/', function() {
    LMD_post("lastmile_dataportal.test_rest");
});
$app->put('/test_rest/:id', function($id) {
    LMD_put($id, "id", "lastmile_dataportal.test_rest");
});
$app->delete('/test_rest/:id', function($id) {
    LMD_delete($id, "id", "lastmile_dataportal.test_rest");
});


// Route 1: Indicator metadata (lastmile_dataportal.tbl_indicators)
$app->get('/indicators/(:id)',function($id='all') {
    LMD_get($id, "indID", "lastmile_dataportal.tbl_indicators", "archived <> 1");
});
$app->post('/indicators/', function() {
    LMD_post("lastmile_dataportal.tbl_indicators");
});
$app->put('/indicators/:id', function($id) {
    LMD_put($id, "indID", "lastmile_dataportal.tbl_indicators");
});
$app->delete('/indicators/:id', function($id) {
    LMD_delete($id, "indID", "lastmile_dataportal.tbl_indicators");
});


// Route 2: Indicator values (lastmile_dataportal.tbl_values)
// Note: different ID field for GET requests vs. PUTs/DELETEs (non-standard behavior)
$app->get('/indicatorvalues/(:id)',function($id='all') {
    LMD_get($id, "indID", "lastmile_dataportal.tbl_values", "indValue <> ''");
});
$app->post('/indicatorvalues/', function() {
    LMD_post("lastmile_dataportal.tbl_indicators");
});
$app->put('/indicatorvalues/:id', function($id) {
    LMD_put($id, "id", "lastmile_dataportal.tbl_values");
});
$app->delete('/indicatorvalues/:id', function($id) {
    LMD_delete($id, "id", "lastmile_dataportal.tbl_values");
});


// Route 3: Data Portal sidebar (tbl_json_objects)
$app->get('/json_objects/:id',function($id) {
    LMD_get($id, "id", "lastmile_dataportal.tbl_json_objects", 1);
});
$app->put('/json_objects/:id', function($id) {
    LMD_put($id, "id", "lastmile_dataportal.tbl_json_objects");
});


// Route 4: Data Portal "report objects" (lastmile_dataportal.tbl_reportobjects)
// Note: different ID field for GET requests vs. PUTs/DELETEs (non-standard behavior)
$app->get('/reportobjects/(:id)',function($id='all') {
    // !!!!! May need to create another address for this (e.g. /GETreportobjects/) !!!!!
    LMD_get($id, "reportID", "lastmile_dataportal.tbl_reportobjects", 1);
});
$app->post('/reportobjects/', function() {
    LMD_post("lastmile_dataportal.tbl_reportobjects");
});
$app->put('/reportobjects/:id', function($id) {
    LMD_put($id, "id", "lastmile_dataportal.tbl_reportobjects");
});
$app->delete('/reportobjects/:id', function($id) {
    LMD_delete($id, "id", "lastmile_dataportal.tbl_reportobjects");
});


// Route 5: Markdown (lastmile_dataportal.tbl_markdown)
$app->get('/markdown/(:id)',function($id='all') {
    LMD_get($id, "mdName", "lastmile_dataportal.tbl_markdown", 1);
});
$app->post('/markdown/', function() {
    LMD_post("lastmile_dataportal.tbl_markdown");
});
$app->put('/markdown/:id', function($id) {
    LMD_put($id, "mdName", "lastmile_dataportal.tbl_markdown");
});
$app->delete('/markdown/:id', function($id) {
    LMD_delete($id, "mdName", "lastmile_dataportal.tbl_markdown");
});


// Run Slim
$app->run();


// Handles GET requests
function LMD_get($id, $idFieldName, $table, $whereFilter) {
    try {
        $idArray = explode(',', $id);
        $idString = "";
        foreach ($idArray as $value) {
            $idString .= "'$value',";
        }
        $idString = trim($idString, ',');
        $whereClause = ($id == 'all') ? 1 : "`$idFieldName` IN ($idString)" ;
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
            echo mysqli_insert_id($cxn); // Only works if ID field is auto-increment
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
            echo json_encode($id);
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
        $query = "DELETE FROM $table WHERE `$idFieldName`='$id'";
        $result = mysqli_query($cxn, $query);
        if (mysqli_query($cxn, $query)) {
            echo json_encode($id);
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
