<?php

/*

    The general pattern is as follows:

    URL                             HTTP METHOD     OPERATION
    ---                             -----------     ---------
    LMD_REST.php/routeName/         GET             Returns an array of objects
    LMD_REST.php/routeName/:id      GET             Returns the object with id of :id
    LMD_REST.php/routeName/:ids     GET             Returns an array of objects with ids of :ids (":ids" should be a comma-separated list)
    LMD_REST.php/routeName/         POST            Adds a new object (returns id)
    LMD_REST.php/routeName/:id      PUT             Updates (or creates) the object with id of :id (returns id)
    LMD_REST.php/routeName/:id      DELETE          Deletes the object with id of :id (returns id) !!!!! if needed, modify DELETE to handle :ids !!!!!


    Specific routes:

    ROUTE                               TABLE
    ---                                 -----
    LMD_REST.php/test_rest              lastmile_dataportal.test_rest
    LMD_REST.php/indicators             lastmile_dataportal.tbl_indicators
    LMD_REST.php/instanceValues         lastmile_dataportal.tbl_indicators
    LMD_REST.php/indicatorInstances     lastmile_dataportal.tbl_indicators
    LMD_REST.php/json_objects           lastmile_dataportal.tbl_json_objects
    LMD_REST.php/reportObjects          lastmile_dataportal.reportobjects
    LMD_REST.php/markdown               lastmile_dataportal.markdown
    LMD_REST.php/users                  lastmile_db.tbl_utility_users
    LMD_REST.php/staff                  lastmile_chwdb.admin_staff
    LMD_REST.php/narratives             lastmile_dataportal.view_reportObjects
    LMD_REST.php/reports                lastmile_dataportal.tbl_reports

*/


// Set include path; require connection strings; instantiate Slim
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
require_once("../../lib/Slim-2.6.2/Slim/Slim.php");
require_once("cxn.php");
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


// Route 0: (lastmile_dataportal.test_rest)
// For testing REST clients (3 columns: `id`, `name`, `age`)
$app->get('/test_rest/(:id)',function($id='all') {
    LMD_get($id, "id", "lastmile_dataportal.test_rest", "*", 1);
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
    LMD_get($id, "indID", "lastmile_dataportal.tbl_indicators", "*", "archived <> 1");
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
$app->get('/instanceValues/(:id)',function($id='all') {
    LMD_get($id, "instID", "lastmile_dataportal.tbl_values", "*", "instValue <> ''");
});
$app->post('/instanceValues/', function() {
    LMD_post("lastmile_dataportal.tbl_indicators");
});
$app->put('/instanceValues/:id', function($id) {
    LMD_put($id, "id", "lastmile_dataportal.tbl_values");
});
$app->delete('/instanceValues/:id', function($id) {
    LMD_delete($id, "id", "lastmile_dataportal.tbl_values");
});


// Route 3: Indicator instances (lastmile_dataportal.tbl_instances)
$app->get('/indicatorInstances/(:id)',function($id='all') {
    LMD_get($id, "instID", "lastmile_dataportal.view_instances", "*", "archived <> 1");
});


// Route 4: Data Portal sidebar (tbl_json_objects)
//  !!!!! switch to jsonObjects to be consistent with camel-case style !!!!!
$app->get('/json_objects/:id',function($id) {
    LMD_get($id, "id", "lastmile_dataportal.tbl_json_objects", "*", 1);
});
$app->put('/json_objects/:id', function($id) {
    LMD_put($id, "id", "lastmile_dataportal.tbl_json_objects");
});


// Route 5: Data Portal "report objects" (lastmile_dataportal.tbl_reportobjects)
// Note: different ID field for GET requests vs. PUTs/DELETEs (non-standard behavior)
$app->get('/reportObjects/(:id)',function($id='all') {
    // !!!!! May need to create another address for this (e.g. /GETreportobjects/) !!!!!
    LMD_get($id, "reportID", "lastmile_dataportal.tbl_reportobjects", "*", 1);
});
$app->post('/reportObjects/', function() {
    LMD_post("lastmile_dataportal.tbl_reportobjects");
});
$app->put('/reportObjects/:id', function($id) {
    LMD_put($id, "id", "lastmile_dataportal.tbl_reportobjects");
});
$app->delete('/reportObjects/:id', function($id) {
    LMD_delete($id, "id", "lastmile_dataportal.tbl_reportobjects");
});


// Route 6: Markdown (lastmile_dataportal.tbl_markdown)
$app->get('/markdown/(:id)',function($id='all') {
    LMD_get($id, "mdName", "lastmile_dataportal.tbl_markdown", "*", 1);
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


// Route 7: LMD users (lastmile_db.tbl_utility_users)
$app->get('/users/(:id)',function($id='all') {
    LMD_get($id, "pk", "lastmile_db.tbl_utility_users", "pk, username, userGroups", 1);
});
$app->post('/users/', function() {
    LMD_post("lastmile_db.tbl_utility_users");
});
$app->put('/users/:id', function($id) {
    LMD_put($id, "pk", "lastmile_db.tbl_utility_users");
});
$app->delete('/users/:id', function($id) {
    LMD_delete($id, "pk", "lastmile_db.tbl_utility_users");
});


// Route 8: Program staff - CHWs, CHWLs, CCSs (lastmile_chwdb.admin_staff)
$app->get('/staff/(:id)',function($id='all') {
    LMD_get($id, "staffID", "lastmile_chwdb.admin_staff", "staffID, firstName, lastName, dateOfBirth, gender", 1);
});
$app->post('/staff/', function() {
    LMD_post("lastmile_chwdb.admin_staff");
});
$app->put('/staff/:id', function($id) {
    LMD_put($id, "staffID", "lastmile_chwdb.admin_staff");
});
$app->delete('/staff/:id', function($id) {
    LMD_delete($id, "staffID", "lastmile_chwdb.admin_staff");
});


// Route 9: Data Portal narratives (lastmile_dataportal.view_reportObjects)
$app->get('/narratives/(:id)',function($id='all') {
    LMD_get($id, "id", "lastmile_dataportal.view_reportobjects", "id, reportID, reportName, displayOrder, roName, roMetaData_target, roMetadata_narrative", 1);
});
$app->post('/narratives/', function() {
    LMD_post("lastmile_dataportal.tbl_reportobjects");
});
$app->put('/narratives/:id', function($id) {
    LMD_put($id, "id", "lastmile_dataportal.tbl_reportobjects");
});
$app->delete('/narratives/:id', function($id) {
    LMD_delete($id, "id", "lastmile_dataportal.tbl_reportobjects");
});


// Route 10: Data Portal report titles (tbl_reports)
// Route 1: Indicator metadata (lastmile_dataportal.tbl_indicators)
$app->get('/reports/(:id)',function($id='all') {
    LMD_get($id, "reportID", "lastmile_dataportal.tbl_reports", "*", 1);
});
// Run Slim
$app->run();


// Handles GET requests
function LMD_get($id, $idFieldName, $table, $fields, $whereFilter) {
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
        $query = "SELECT $fields FROM $table WHERE $whereClause";
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
// Note: this does not handle cases where the PUT request is an INSERT; use POST for INSERTS
function LMD_put($id, $idFieldName, $table) {
    try {
        $app = \Slim\Slim::getInstance();
	$bodyDecoded = $app->request->put() ? $app->request->put() : json_decode($app->request->getBody());
        $cxn = getCXN();
        
        $query = "UPDATE $table SET ";
        foreach ($bodyDecoded as $key => $value) {
            $query .= "`$key` = '" . addslashes($value) . "', " ;
        }
        $query = substr($query, 0, -2) ;
        $query .= " WHERE `$idFieldName`='" . $id . "';";
        
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
