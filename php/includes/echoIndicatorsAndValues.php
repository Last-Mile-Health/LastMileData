<?php

    // This file should be included within a pair of <script> tags
    // Echoes out 2-3 javascript objects: data_indicators, data_rawValues, [model_report]
    
    // Initiate/configure CURL session
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

    // Echo JSON (report model)
    if (isset($reportID)) {
        $url1 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/reportobjects/$reportID";
        curl_setopt($ch,CURLOPT_URL,$url1);
        $json1 = curl_exec($ch);
    }
    
    // Get indicator IDs of all indicators in the report
    $indIDString = "";
    foreach (json_decode($json1) as $value) {
        $indIDString .= $value->indicators. ",";
        $indIDString .= $value->chart_indicators. ",";
    }
    $indIDString = trim($indIDString, ",");

    // Echo JSON (indicator metadata)
    $url2 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/indicators/$indIDString";
    curl_setopt($ch,CURLOPT_URL,$url2);
    $json2 = curl_exec($ch);

    // Echo JSON (indicator data)
    $url3 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/indicatorvalues/$indIDString";
    curl_setopt($ch,CURLOPT_URL,$url3);
    $json3 = curl_exec($ch);

    // Close CURL session and echo JSON
    curl_close($ch);
    if(isset($reportID)) {
        echo "var model_report = $json1;". "\n\n";
    }
    echo "var data_indicators = $json2;". "\n\n";
    echo "var data_rawValues = $json3;". "\n\n";
