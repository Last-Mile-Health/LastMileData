<?php

// Set DB connection strings (!!!!! figure out how to set include path with CRON !!!!!)
$user = "root";
$password = "LastMile14";
$host = "localhost";

$db = "lastmile_db";
$db1 = "lastmile_chwdb";
$db2 = "lastmile_dataportal";
$db3 = "lastmile_lms";

$backup_file = $db . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file1 = $db1 . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file2 = $db2 . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file3 = $db3 . '_' . date("Y-m-d-H-i-s") . '.sql';

// For localhost
// !!!!! Don't delete; reminder of issue with using full mysqldump path !!!!!
//exec('C:/Users/Avi/Desktop/Avi/xampp/mysql/bin/mysqldump --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db . ' >' . $_SERVER['DOCUMENT_ROOT'] . '/LastMileData/backups/' . $backup_file);

// For GoDaddy
exec('mysqldump --routines --events --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file);
exec('mysqldump --routines --events --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db1 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file1);
exec('mysqldump --routines --events --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db2 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file2);
exec('mysqldump --routines --events --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db3 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file3);
