<?php

$dir    = '../../backups/CSVs/';

$files = glob( $dir . "{de_chss_commodity_distribution,odk_chaRestock}*.csv", GLOB_BRACE );

echo "<br><h2>Village Reach Download Datasets</h2>";

foreach($files as $file) {

    if (!in_array($file,['.','..'])) {

        $display_filename = str_replace( '../../backups/CSVs/','', $file );

        echo "<p><a href='../../php/scripts/downloadFile.php?file=$file'>$display_filename</a></p>";

    }
 
}
