<?php

$dir    = '../../backups/CSVs/';
$files = scandir($dir);

echo "<br><h1>Download CSV Datasets</h1>";

foreach($files as $file) {
    if (!in_array($file,['.','..'])) {
        echo "<p><a href='../../php/scripts/downloadFile.php?file=$file'>$file</a></p>";
    }
}
