<?php

// Backs up all MySQL databse schemas. Should be run daily at roughly 1am (server time - EST)
// Set DB connection strings (!!!!! figure out how to set include path with CRON !!!!!)
// URL: localhost/LastMileData/php/scripts/dataBackup.php

// --skip-lock-tables option to mysqldump helps clear up the problem of mysqld using excessive amounts of memory during the nightly backups.

$user = "root";
$password = "LastMile14";
$host = "localhost";

$db1    = "lastmile_chwdb";
$db2    = "lastmile_dataportal";
$db3    = "lastmile_lms";
$db4    = "lastmile_archive";
$db5    = "lastmile_upload";
$db6    = "lastmile_dataquality";
$db8    = "lastmile_temp";
$db9    = "lastmile_pilot";
$db10   = "lastmile_cha";


$backup_file1   = $db1      . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file2   = $db2      . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file3   = $db3      . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file4   = $db4      . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file5   = $db5      . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file6   = $db6      . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file8   = $db8      . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file9   = $db9      . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file10  = $db10     . '_' . date("Y-m-d-H-i-s") . '.sql';


$logFile = 'dataBackup.log';


// For localhost
//exec('C:/Users/Avi/Desktop/Avi/xampp/mysql/bin/mysqldump --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db . ' >' . $_SERVER['DOCUMENT_ROOT'] . '/LastMileData/backups/' . $backup_file);


// For GoDaddy

// Move old files into backups/archive directory
$source = "/home/lastmilehealth/public_html/LastMileData/backups/";
$files = scandir($source);
$destination = "/home/lastmilehealth/public_html/LastMileData/backups/archive/";
foreach ($files as $file) {
    if (in_array($file, array(".",".."))) continue;
    if (substr($file, -4) != '.sql') continue;
    // If we successfully copied this file, delete it from the source folder
    if (copy($source.$file, $destination.$file)) {
        unlink($source.$file);
    }
}

// Create new backups

exec( 'echo "------------------------------------------------------" >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec( 'date >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db1 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db1 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file1 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db2 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db2 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file2 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db3 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db3 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file3 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db4 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db4 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file4 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db5 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db5 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file5 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db6 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db6 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file6 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db8 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db8 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file8 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db9 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db9 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file9 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db10 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db10 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file10 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "------------------------------------------------------" >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
