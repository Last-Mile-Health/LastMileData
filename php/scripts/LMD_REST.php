<?php

/*

    The *general* pattern (not followed by all routes) is as follows:

    URL                             HTTP METHOD     OPERATION
    ---                             -----------     ---------
    LMD_REST.php/routeName/         GET             Returns an array of objects
    LMD_REST.php/routeName/:id      GET             Returns the object with id of :id
    LMD_REST.php/routeName/:ids     GET             Returns an array of objects with ids of :ids (":ids" should be a comma-separated list)
    LMD_REST.php/routeName/         POST            Adds a new object (returns id)
    LMD_REST.php/routeName/:id      PUT             Updates (or creates) the object with id of :id (returns id)
    LMD_REST.php/routeName/:id      DELETE          Deletes the object with id of :id (returns id) !!!!! if needed, modify DELETE to handle :ids !!!!!


    Specific routes:

    #   ROUTE                                           TABLE
    --  ---                                             -----
     0  LMD_REST.php/test_rest                          lastmile_dataportal.test_rest
     1  LMD_REST.php/indicators                         lastmile_dataportal.tbl_indicators
     2  LMD_REST.php/instanceValues                     lastmile_dataportal.tbl_values
     3  LMD_REST.php/indicatorInstances                 lastmile_dataportal.view_instances
     4  LMD_REST.php/json_objects                       lastmile_dataportal.tbl_json_objects
     5  LMD_REST.php/reportObjects                      lastmile_dataportal.reportobjects
     6  LMD_REST.php/markdown                           lastmile_dataportal.markdown
     7  LMD_REST.php/users                              lastmile_dataportal.tbl_utility_users
     8  LMD_REST.php/staff                              lastmile_chwdb.admin_staff
     9  LMD_REST.php/narratives                         lastmile_dataportal.view_reportObjects
    10  LMD_REST.php/reports                            lastmile_dataportal.tbl_reports
    11  LMD_REST.php/gis_communities_remote             lastmile_chwdb.admin_community
    12  LMD_REST.php/gis_communities_nearFacility       lastmile_chwdb.admin_community
    13  LMD_REST.php/gis_communities_CHW                lastmile_chwdb.view_leaflet_communities_chw_2
    14  LMD_REST.php/gis_community_data                 lastmile_dataportal.tbl_leaflet_values
    15  LMD_REST.php/gis_district_data                  lastmile_dataportal.tbl_leaflet_values
    16  LMD_REST.php/gis_county_data                    lastmile_dataportal.tbl_leaflet_values
    17  LMD_REST.php/gis_data_availability              lastmile_dataportal.view_leaflet_availability_2
    18  LMD_REST.php/indicatorInstancesFiltered         lastmile_dataportal.view_instances
    19  LMD_REST.php/instanceValuesFiltered             lastmile_dataportal.view_values
    20  LMD_REST.php/geoCuts                            lastmile_dataportal.tbl_geocuts
    21  LMD_REST.php/indCategories                      lastmile_dataportal.view_categories
    22  LMD_REST.php/max                                various

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


// Route 3: Indicator instances (lastmile_dataportal.view_instances)
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
    LMD_get($id, "reportID", "lastmile_dataportal.tbl_reportobjects", "*", "archived <> 1");
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


// Route 7: LMD users (lastmile_dataportal.tbl_utility_users)
$app->get('/users/(:id)',function($id='all') {
    LMD_get($id, "pk", "lastmile_dataportal.tbl_utility_users", "pk, username, userGroups", 1);
});
$app->post('/users/', function() {
    LMD_post("lastmile_dataportal.tbl_utility_users");
});
$app->put('/users/:id', function($id) {
    LMD_put($id, "pk", "lastmile_dataportal.tbl_utility_users");
});
$app->delete('/users/:id', function($id) {
    LMD_delete($id, "pk", "lastmile_dataportal.tbl_utility_users");
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
    LMD_get($id, "id", "lastmile_dataportal.view_reportobjects", "id, reportID, reportName, displayOrder, roName, ro_narrative", 1);
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


// Route 10: Data Portal report titles (lastmile_dataportal.tbl_reports)
$app->get('/reports/(:id)',function($id='all') {
    LMD_get($id, "reportID", "lastmile_dataportal.tbl_reports", "reportID, reportName", "archived <> 1");
});
$app->post('/reports/', function() {
    LMD_post("lastmile_dataportal.tbl_reports");
});
$app->put('/reports/:id', function($id) {
    LMD_put($id, "reportID", "lastmile_dataportal.tbl_reports");
});
$app->delete('/reports/:id', function($id) {
    LMD_delete($id, "reportID", "lastmile_dataportal.tbl_reports");
});


// Route 11: Data Portal GIS: communities (remote)
$app->get('/gis_communities_remote/(:id)',function($id='all') {
    LMD_get($id, "communityID", "lastmile_chwdb.admin_community", "communityID, name, X, Y", "proximityHealthFacility='remote'");
});


// Route 12: Data Portal GIS: communities (near-facility)
$app->get('/gis_communities_nearFacility/(:id)',function($id='all') {
    LMD_get($id, "communityID", "lastmile_chwdb.admin_community", "communityID, name, X, Y", "proximityHealthFacility='near-facility'");
});


// Route 13: Data Portal GIS: communities (CHW-served)
$app->get('/gis_communities_CHW/(:id)',function($id='all') {
    LMD_get($id, "communityID", "lastmile_chwdb.view_leaflet_communities_chw_2", "*", 1);
});


// Route 14: Data Portal GIS: community data
$app->get('/gis_community_data/:period/:id',function($period,$id) {
    LMD_get($id, "indID", "lastmile_dataportal.tbl_leaflet_values", "territoryID AS `id`, indVal", "territoryID<>0 AND indLevel='community' AND periodID IN ($period, 99)");
});


// Route 15: Data Portal GIS: district data
$app->get('/gis_district_data/:period/:id',function($period,$id) {
    LMD_get($id, "indID", "lastmile_dataportal.tbl_leaflet_values", "territoryID AS `id`, indVal", "territoryID<>0 AND indLevel='district' AND periodID IN ($period, 99)");
});


// Route 16: Data Portal GIS: county data
$app->get('/gis_county_data/:period/:id',function($period,$id) {
    LMD_get($id, "indID", "lastmile_dataportal.tbl_leaflet_values", "territoryID AS `id`, indVal", "territoryID<>0 AND indLevel='county' AND periodID IN ($period, 99)");
});


// Route 17: Data Portal GIS: leaflet data availability
$app->get('/gis_data_availability/',function($id='all') {
    LMD_get($id, "indID", "lastmile_dataportal.view_leaflet_availability_2", "*", 1);
});


// Route 18: Indicator/instance metadata (filtered by category and cut) (lastmile_dataportal.view_instances)
$app->get('/indicatorInstancesFiltered/:category/(:geoName)',function($category,$geoName='all') {
    $geo = $geoName=='all' ? 1 : "geoName = '$geoName'";
    LMD_get('all', "instID", "lastmile_dataportal.view_instances", "*", "archived <> 1 AND indCategory='$category' AND $geo");
});


// Route 19: Indicator/instance values (filtered by category, cut, and date range) (lastmile_dataportal.view_values)
// minDate and maxDate should be specified in terms of "# of months since year 0" (i.e. year*12 + month)
$app->get('/instanceValuesFiltered/:category/:geoName/:startDate/:endDate',function($category,$geoName,$minDate,$maxDate) {
    $geo = $geoName=='all' ? 1 : "geoName = '$geoName'";
    LMD_get('all', "instID", "lastmile_dataportal.view_values", "month, year, instID, instValue", "archived <> 1 AND indCategory='$category' AND $geo AND ((year*12)+month) BETWEEN $minDate AND $maxDate");
});


// Route 20: Indicator/instance geoCuts (lastmile_dataportal.tbl_geocuts)
$app->get('/geoCuts/(:id)',function($id='all') {
    LMD_get($id, "geoID", "lastmile_dataportal.tbl_geocuts", "*", 1);
});
$app->post('/geoCuts/', function() {
    LMD_post("lastmile_dataportal.tbl_geocuts");
});
$app->put('/geoCuts/:id', function($id) {
    LMD_put($id, "geoID", "lastmile_dataportal.tbl_geocuts");
});
$app->delete('/geoCuts/:id', function($id) {
    LMD_delete($id, "geoID", "lastmile_dataportal.tbl_geocuts");
});


// Route 21: Indicator/instance categories (lastmile_dataportal.view_categories)
$app->get('/indCategories/(:id)',function($id='all') {
    LMD_get($id, "", "lastmile_dataportal.view_categories", "*", 1);
});


// Route 22: Returns maximum value of a field from a given table (various)
$app->get('/max/:schema/:table/:idFieldName',function($schema,$table,$idFieldName) {
    LMD_get('all', '', "$schema.$table", "MAX($idFieldName) AS max", 1);
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
