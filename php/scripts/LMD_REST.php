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

    ###     ROUTE                                           TABLE
    ---     -----                                           -----
     0      LMD_REST.php/test_rest                          lastmile_dataportal.test_rest
     1a     LMD_REST.php/indicators                         lastmile_dataportal.tbl_indicators
     1b     LMD_REST.php/indicatorValues                    lastmile_dataportal.tbl_values
     1c     LMD_REST.php/indicatorInstances                 lastmile_dataportal.view_instances ?????
     1d     LMD_REST.php/indicatorInstancesFiltered         lastmile_dataportal.view_instances_2
     1e     LMD_REST.php/indicatorValuesFiltered            lastmile_dataportal.view_values
     2      LMD_REST.php/json_objects                       lastmile_dataportal.tbl_json_objects
     3      LMD_REST.php/reportObjects                      lastmile_dataportal.reportobjects
     4a     LMD_REST.php/markdown                           lastmile_dataportal.markdown
     4b     LMD_REST.php/markdownByName                     lastmile_dataportal.markdown
     5      LMD_REST.php/users                              lastmile_dataportal.tbl_utility_users
     6      LMD_REST.php/staff                              lastmile_chwdb.admin_staff
     7      LMD_REST.php/narratives                         lastmile_dataportal.view_report_objects
     8      LMD_REST.php/reports                            lastmile_dataportal.tbl_reports
     9a     LMD_REST.php/gis_communities_remote             lastmile_cha.view_base_geo_community
     9b     LMD_REST.php/gis_communities_nearFacility       lastmile_cha.view_base_geo_community
     9c     LMD_REST.php/gis_communities_CHW                lastmile_cha.view_base_geo_community_primary
     9d     LMD_REST.php/gis_community_data                 lastmile_dataportal.tbl_leaflet_values
     9e     LMD_REST.php/gis_district_data                  lastmile_dataportal.tbl_leaflet_values
     9f     LMD_REST.php/gis_county_data                    lastmile_dataportal.tbl_leaflet_values
     9g     LMD_REST.php/gis_data_availability              lastmile_dataportal.view_leaflet_availability_2
    10      LMD_REST.php/geoCuts                            lastmile_dataportal.tbl_geocuts
    10b     LMD_REST.php/territories                        lastmile_dataportal.view_territories
    11      LMD_REST.php/indCategories                      lastmile_dataportal.view_categories
    12      LMD_REST.php/max                                various

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


// Route 1a: Indicator metadata (lastmile_dataportal.tbl_indicators)
// "Delete" is actually an "archive" via LMD_archive()
$app->get('/indicators/:includeArchived/(:id)',function($includeArchived,$id='all') {
    LMD_get($id, "ind_id", "lastmile_dataportal.tbl_indicators", "*", $includeArchived==1 ? 1 : "archived <> 1");
});
$app->post('/indicators/', function() {
    LMD_post("lastmile_dataportal.tbl_indicators");
});
$app->put('/indicators/:id', function($id) {
    LMD_put($id, "ind_id", "lastmile_dataportal.tbl_indicators");
});
$app->delete('/indicators/:id', function($id) {
    LMD_archive($id, "ind_id", "lastmile_dataportal.tbl_indicators");
});


// Route 1b: Indicator values (lastmile_dataportal.tbl_values)
// Note: different ID field for GET requests vs. PUTs/DELETEs (non-standard behavior)
// !!!!! still need to build out other routes !!!!!
$app->get('/indicatorValues/:ind_id/(:territory_id)',function($ind_id,$territory_id='all') {
    $territory_id = $territory_id=='all' ? "all" : "'" . str_replace(",","','",$territory_id) . "'";
    LMD_get($ind_id, "ind_id", "lastmile_dataportal.tbl_values", "ind_id, month, year, territory_id, period_id, value", "value <> '' AND " . ($territory_id=='all' ? "1" : "territory_id IN ($territory_id)"));
});
//$app->post('/instanceValues/', function() {
//    LMD_post("lastmile_dataportal.tbl_indicators");
//});
//$app->put('/instanceValues/:id', function($id) {
//    LMD_put($id, "id", "lastmile_dataportal.tbl_values");
//});
//$app->delete('/instanceValues/:id', function($id) {
//    LMD_delete($id, "id", "lastmile_dataportal.tbl_values");
//});


// !!!!! phase out !!!!!
// Route 1c: Indicator instances (lastmile_dataportal.view_instances)
//$app->get('/indicatorInstances/:includeArchived/(:id)',function($includeArchived,$id='all') {
//    LMD_get($id, "instID", "lastmile_dataportal.view_instances", "*", $includeArchived==1 ? 1 : "archived <> 1");
//});
// !!!!! phase out !!!!!


// Route 1d: Indicator/instance metadata (filtered by category and cut) (lastmile_dataportal.view_instances)
// Used by "Edit Data" tool
$app->get('/indicatorInstancesFiltered/:includeArchived/:category/(:territory_name)',function($includeArchived,$category,$territory_name='all') {
    $territory = $territory_name=='all' ? 1 : "territory_name = '$territory_name'";
    LMD_get('all', "inst_id", "lastmile_dataportal.view_instances_2", "*", $includeArchived==1 ? 1 : "archived <> 1 AND ind_category='$category' AND $territory");
});


// Route 1e: Indicator/instance values (filtered by category, cut, and date range) (lastmile_dataportal.view_values)
// Used by "Edit Data" tool
// minDate and maxDate should be specified in terms of "# of months since year 0" (i.e. year*12 + month)
$app->get('/indicatorValuesFiltered/:category/:territory_name/:minDate/:maxDate',function($category,$territory_name,$minDate,$maxDate) {
    $territory = $territory_name=='all' ? 1 : "territory_name = '$territory_name'";
    LMD_get('all', "inst_id", "lastmile_dataportal.view_values", "month, year, inst_id, value", "archived <> 1 AND ind_category='$category' AND $territory AND ((year*12)+month) BETWEEN $minDate AND $maxDate");
});


// Route 2: Data Portal sidebar (tbl_json_objects)
$app->get('/json_objects/:id',function($id) {
    LMD_get($id, "id", "lastmile_dataportal.tbl_json_objects", "*", 1);
});
$app->put('/json_objects/:id', function($id) {
    LMD_put($id, "id", "lastmile_dataportal.tbl_json_objects");
});


// Route 3: Data Portal "report objects" (lastmile_dataportal.tbl_report_objects)
// Note: different ID field for GET requests vs. PUTs/DELETEs (non-standard behavior)
$app->get('/reportObjects/:includeArchived/(:id)',function($includeArchived,$id='all') {
    LMD_get($id, "report_id", "lastmile_dataportal.tbl_report_objects", "*", $includeArchived==1 ? 1 : "archived <> 1");
});
$app->post('/reportObjects/', function() {
    LMD_post("lastmile_dataportal.tbl_report_objects");
});
$app->put('/reportObjects/:id', function($id) {
    LMD_put($id, "id", "lastmile_dataportal.tbl_report_objects");
});
$app->delete('/reportObjects/:id', function($id) {
    LMD_delete($id, "id", "lastmile_dataportal.tbl_report_objects");
});


// Route 4a: Markdown (lastmile_dataportal.tbl_markdown)
// Note: the "/1/" in the URL is to enable admin_editingInterface to work
$app->get('/markdown/1/(:id)',function($id='all') {
    LMD_get($id, "id", "lastmile_dataportal.tbl_markdown", "*", 1);
});
$app->post('/markdown/', function() {
    LMD_post("lastmile_dataportal.tbl_markdown");
});
$app->put('/markdown/:id', function($id) {
    LMD_put($id, "id", "lastmile_dataportal.tbl_markdown");
});
$app->delete('/markdown/:id', function($id) {
    LMD_delete($id, "id", "lastmile_dataportal.tbl_markdown");
});


// Route 4b: Markdown (by name) (lastmile_dataportal.tbl_markdown)
$app->get('/markdownByName/:id',function($id) {
    LMD_get($id, "md_name", "lastmile_dataportal.tbl_markdown", "*", 1);
});


// Route 5: LMD users (lastmile_dataportal.tbl_utility_users)
// Note: the "/1/" in the URL is to enable admin_editingInterface to work
$app->get('/users/1/(:id)',function($id='all') {
    LMD_get($id, "id", "lastmile_dataportal.tbl_utility_users", "id, username, user_groups", 1);
});
$app->post('/users/', function() {
    LMD_post("lastmile_dataportal.tbl_utility_users");
});
$app->put('/users/:id', function($id) {
    LMD_put($id, "id", "lastmile_dataportal.tbl_utility_users");
});
$app->delete('/users/:id', function($id) {
    LMD_delete($id, "id", "lastmile_dataportal.tbl_utility_users");
});


// Route 6: Program staff - CHWs, CHWLs, CCSs (lastmile_chwdb.admin_staff)
// Note: the "/1/" in the URL is to enable admin_editingInterface to work
//$app->get('/staff/1/(:id)',function($id='all') {
//    LMD_get($id, "staffID", "lastmile_chwdb.admin_staff", "staffID, firstName, lastName, dateOfBirth, gender", 1);
//});
//$app->post('/staff/', function() {
//    LMD_post("lastmile_chwdb.admin_staff");
//});
//$app->put('/staff/:id', function($id) {
//    LMD_put($id, "staffID", "lastmile_chwdb.admin_staff");
//});
//$app->delete('/staff/:id', function($id) {
//    LMD_delete($id, "staffID", "lastmile_chwdb.admin_staff");
//});


// Route 7: Data Portal narratives (lastmile_dataportal.view_report_objects)
// Note: the "/1/" in the URL is to enable admin_editingInterface to work
$app->get('/narratives/1/(:id)',function($id='all') {
    LMD_get($id, "id", "lastmile_dataportal.view_report_objects", "*", 1);
});
$app->post('/narratives/', function() {
    LMD_post("lastmile_dataportal.tbl_report_objects");
});
$app->put('/narratives/:id', function($id) {
    LMD_put($id, "id", "lastmile_dataportal.tbl_report_objects");
});
$app->delete('/narratives/:id', function($id) {
    LMD_delete($id, "id", "lastmile_dataportal.tbl_report_objects");
});


// Route 8: Data Portal report titles (lastmile_dataportal.tbl_reports)
$app->get('/reports/:includeArchived/(:id)',function($includeArchived,$id='all') {
    LMD_get($id, "report_id", "lastmile_dataportal.tbl_reports", "report_id, report_name, header_note", $includeArchived==1 ? 1 : "archived <> 1");
});
$app->post('/reports/', function() {
    LMD_post("lastmile_dataportal.tbl_reports");
});
$app->put('/reports/:id', function($id) {
    LMD_put($id, "report_id", "lastmile_dataportal.tbl_reports");
});
$app->delete('/reports/:id', function($id) {
    LMD_delete($id, "report_id", "lastmile_dataportal.tbl_reports");
});


// Route 9a: Data Portal GIS: communities (remote)
$app->get('/gis_communities_remote/(:id)',function($id='all') {
    LMD_get($id, "community_id", "lastmile_cha.view_base_geo_community", "community_id, community AS `name`, x, y", "health_facility_proximity='remote' AND archived<>1");
});


// Route 9b: Data Portal GIS: communities (near-facility)
$app->get('/gis_communities_nearFacility/(:id)',function($id='all') {
    LMD_get($id, "community_id", "lastmile_cha.view_base_geo_community", "community_id, community AS `name`, x, y", "health_facility_proximity='near-facility' AND archived<>1");
});


// Route 9c: Data Portal GIS: communities (CHW-served; primary)
$app->get('/gis_communities_CHW/(:id)',function($id='all') {
    LMD_get($id, "community_id", "lastmile_cha.view_base_geo_community_primary", "community_id, community AS `name`, x, y", 1);
});


// Route 9d: Data Portal GIS: community data
$app->get('/gis_community_data/:period/:id',function($period,$id) {
    LMD_get($id, "indID", "lastmile_dataportal.tbl_leaflet_values", "territoryID AS `id`, indVal", "territoryID<>0 AND indLevel='community' AND periodID IN ($period, 99)");
});


// Route 9e: Data Portal GIS: district data
$app->get('/gis_district_data/:period/:id',function($period,$id) {
    LMD_get($id, "indID", "lastmile_dataportal.tbl_leaflet_values", "territoryID AS `id`, indVal", "territoryID<>0 AND indLevel='district' AND periodID IN ($period, 99)");
});


// Route 9f: Data Portal GIS: county data
$app->get('/gis_county_data/:period/:id',function($period,$id) {
    LMD_get($id, "indID", "lastmile_dataportal.tbl_leaflet_values", "territoryID AS `id`, indVal", "territoryID<>0 AND indLevel='county' AND periodID IN ($period, 99)");
});


// Route 9g: Data Portal GIS: leaflet data availability
$app->get('/gis_data_availability/',function($id='all') {
    LMD_get($id, "indID", "lastmile_dataportal.view_leaflet_availability_2", "*", 1);
});


// Route 10: Active territories (lastmile_dataportal.view_territories_active)
$app->get('/territories_active/(:id)',function($id='all') {
    LMD_get($id, "territory_id", "lastmile_dataportal.view_territories_active", "*", 1);
});


// Route 10b: Territories (lastmile_dataportal.view_territories)
$app->get('/territories/(:id)',function($id='all') {
    LMD_get($id, "territory_id", "lastmile_dataportal.view_territories", "*", 1);
});


// Route 11: Indicator/instance categories (lastmile_dataportal.view_categories)
$app->get('/indCategories/(:id)',function($id='all') {
    LMD_get($id, "", "lastmile_dataportal.view_categories", "*", 1);
});


// Route 12: Returns maximum value of a field from a given table (various)
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


// Handles ARCHIVE requests
// Normally passed as "DELETE" requests
function LMD_archive($id, $idFieldName, $table) {
    try {
        $cxn = getCXN();
        $query = "UPDATE $table SET archived=1 WHERE `$idFieldName`='$id'";
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


