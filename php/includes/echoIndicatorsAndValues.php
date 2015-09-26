<?php
    
    // !!!!! Rewrite this as an object or function !!!!!

    // Initiate/configure CURL session
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

    // Echo JSON (indicator METADATA)
    $url1 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/indicators/$indIDString";
    curl_setopt($ch,CURLOPT_URL,$url1);
    $json1 = curl_exec($ch);

    // Echo JSON (indicator DATA)
    $url2 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/indicatorvalues/$indIDString";
    curl_setopt($ch,CURLOPT_URL,$url2);
    $json2 = curl_exec($ch);

    // Echo JSON (report model)
    if (isset($reportObjectString)) {
        $url3 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/reportobjects/$reportObjectString";
        curl_setopt($ch,CURLOPT_URL,$url3);
        $json3 = curl_exec($ch);
    }

    // Close CURL session and echo JSON
    curl_close($ch);
    echo "var data_indicators = $json1;". "\n\n";
    echo "var data_rawValues = $json2;". "\n\n";
    
    if(isset($reportObjectString)) {
        // !!!!! this needs to be generalized !!!!!
        echo "var model_konobo = $json3;". "\n\n";
    }
