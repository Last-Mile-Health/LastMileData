<?php

// !!!!! Consolidate this with REST_indicators.php !!!!!
// !!!!! Consolidate this with REST_indicators.php !!!!!
// !!!!! Consolidate this with REST_indicators.php !!!!!

/*
        URL                             HTTP METHOD     OPERATION
        ---                             -----------     ---------
        REST_indicators.php/            GET             Returns an array of indicators
        REST_indicators.php/:id         GET             Returns the indicator with id of :id
        REST_indicators.php/            POST            Adds a new indicator (returns id)
        REST_indicators.php/:id         PUT             Updates (or creates) the indicator with id of :id (returns id)
        REST_indicators.php/:id         DELETE          Deletes the indicator with id of :id (returns id)
*/

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );

// Slim instantiation, method assignment, and bootstrapping
require_once("../../../LastMileData/lib/Slim-2.6.2/Slim/Slim.php");
require_once("cxn.php");
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
// !!!!! consider merging getAll and getSome; need optional PHP argument !!!!!
$app->get('/','getAll');
$app->get('/:id','getSome');
$app->post('/', 'addOne');
$app->put('/:id', 'updateOne');
$app->delete('/:id','deleteOne');
$app->run();


function getAll() {
    $id = 'all';
    getSome($id);
}


function getSome($id) {
    try {
        $whereClause = ($id == 'all') ? 1 : "indID IN ($id)" ;
        $cxn = getCXN();
        $query = "SELECT `month`, `year`, `indID`, `indValue` FROM dataportal.tbl_values WHERE $whereClause";
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


function addOne() {
    try {
        $app = \Slim\Slim::getInstance();
        $body = $app->request->getBody();
	$bodyDecode = json_decode($body);
        $month = $bodyDecode->month;
        $year = $bodyDecode->year;
        $indID = $bodyDecode->indID;
        $indValue = $bodyDecode->indDefinition;
        
        $cxn = getCXN();
        $query = "INSERT INTO dataportal.tbl_values SET `indName`='" . @$indName . "', `indCategory`='" . @$indCategory . "', `indFormat`='" . @$indFormat . "', `indDefinition`='" . @$indDefinition . "', `indTarget`='" . @$indTarget . "', `indNarrative`='" . @$indNarrative . "'";
        echo mysqli_query($cxn, $query) ? mysqli_insert_id($cxn) : 0;
        mysqli_close($cxn);
    }
    catch(ErrorException $e) {
        // !!!!! Build out error handler !!!!!
        echo '{"error":{"text":'. $e .'}}';
    }
}


function updateOne($id) {
    try {
        $app = \Slim\Slim::getInstance();
        $body = $app->request->getBody();
	$bodyDecode = json_decode($body);
        $month = $bodyDecode->month;
        $year = $bodyDecode->year;
        $indID = $bodyDecode->indID;
        $indValue = $bodyDecode->indDefinition;
        
        $cxn = getCXN();
        $query = "REPLACE INTO dataportal.tbl_values SET `id`='" . @$id . "', `month`='" . @$month . "', `year`='" . @$year . "', `indID`='" . @$indID . "', `indValue`='" . @$indValue . "'";
        echo mysqli_query($cxn, $query) ? $bodyID : 0;
        mysqli_close($cxn);
    }
    catch(ErrorException $e) {
        // !!!!! Build out error handler !!!!!
        echo '{"error":{"text":'. $e .'}}';
    }
}


function deleteOne($id) {
    try {
        $cxn = getCXN();
        $query = "DELETE FROM dataportal.tbl_values WHERE id=$id";
        $result = mysqli_query($cxn, $query);
        echo mysqli_query($cxn, $query) ? $id : 0;
        mysqli_close($cxn);
    }
    catch(ErrorException $e) {
        // !!!!! Build out error handler !!!!!
        echo '{"error":{"text":'. $e .'}}';
    }
}
