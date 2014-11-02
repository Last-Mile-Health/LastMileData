<?php

// Set DB connection strings (!!!!! figure out how to set iunclude path with CRON !!!!!)
$user = "lastmiledata";
$password = "LastMile14";
$host = "localhost";
$db = "lastmiledata";

$backup_file = $db . '_' . date("Y-m-d-H-i-s") . '.sql';

// For Localhost (not needed)
//exec('C:/Users/Avi/Desktop/Avi/xampp/mysql/bin/mysqldump --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db . ' >' . $_SERVER['DOCUMENT_ROOT'] . '/LastMileData/backups/' . $backup_file);

// For GoDaddy
exec('mysqldump --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file);

?>