<?php

// Backs up all MySQL databse schemas. Should be run daily at roughly 1am (server time - EST)
// Set DB connection strings (!!!!! figure out how to set include path with CRON !!!!!)
// URL: localhost/LastMileData/php/scripts/dataBackup.php

// --skip-lock-tables option to mysqldump helps clear up the problem of mysqld using excessive amounts of memory during the nightly backups.

$user = "root";
$password = "LastMile14";
$host = "localhost";

$db1    = "lastmile_archive";
$db2    = "lastmile_upload";
$db3    = "lastmile_ifi";
$db4    = "lastmile_cha";
$db5    = "lastmile_ncha";
$db6    = "lastmile_dataquality";
$db7    = "lastmile_dataportal";
$db8    = "lastmile_lms";
$db9    = "lastmile_program";
$db10   = "lastmile_report";
$db11   = "lastmile_pilot";
$db12   = "lastmile_develop";
$db13   = "lastmile_temp";
$db14   = "lastmile_liberiamohdata";

$backup_file1   = $db1      . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file2   = $db2      . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file3   = $db3      . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file4   = $db4      . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file5   = $db5      . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file6   = $db6      . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file7   = $db7      . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file8   = $db8      . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file9   = $db9      . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file10  = $db10     . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file11  = $db11     . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file12  = $db12     . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file13  = $db13     . '_' . date("Y-m-d-H-i-s") . '.sql';
$backup_file14  = $db14     . '_' . date("Y-m-d-H-i-s") . '.sql';

$logFile = 'dataBackup.log';


// For localhost
//exec('C:/Users/Avi/Desktop/Avi/xampp/mysql/bin/mysqldump --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db . ' >' . $_SERVER['DOCUMENT_ROOT'] . '/LastMileData/backups/' . $backup_file);


// Centos Linux backup 

exec( 'date >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

// compress SQL files with xz command.  To decompress, run command "xz --decompress *.xz".

exec( 'echo compressing all *.sql file with the xz command' . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('xz ' . '/home/lastmilehealth/public_html/LastMileData/backups/*.sql' . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'date >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );


// Move old files into backups/archive directory
exec( 'echo moving all *.xz files to the arcive directory ' . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

$source = "/home/lastmilehealth/public_html/LastMileData/backups/";
$files = scandir($source);
$destination = "/home/lastmilehealth/public_html/LastMileData/backups/archive/";

foreach ( $files as $file ) {

    if ( in_array( $file, array( ".",".." ) ) ) continue;

    if ( substr( $file, -3 ) != '.xz' ) continue;

    // If we successfully copied this file, delete it from the source folder

    if ( copy( $source.$file, $destination.$file ) ) {
        unlink( $source.$file );
    }
}


// Create new backups

exec( 'date >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db2 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --force --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db2 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file2 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db3 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --force --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db3 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file3 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db5 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --force --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db5 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file5 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db7 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --force --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db7 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file7 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db8 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --force --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db8 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file8 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db9 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --force --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db9 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file9 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db10 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --force --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db10 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file10 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );


// dump these databases only on Monday mornings, Sunday night 
if(date('w') == 1){

exec( 'echo "dumping" ' . $db1 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --force --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db1 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file1 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db4 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --force --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db4 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file4 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db6 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --force --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db6 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file6 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db11 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --force --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db11 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file11 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db12 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --force --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db12 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file12 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db13 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --force --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db13 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file13 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

exec( 'echo "dumping" ' . $db14 . ' >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec('mysqldump --skip-lock-tables --routines --events --force --add-drop-trigger --user="' . $user . '" --password="' . $password . '" --host="' . $host . '" ' . $db14 . ' >' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $backup_file14 . ' 2>> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

}


exec( 'date >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );
exec( 'echo "------------------------------------------------------" >> ' . '/home/lastmilehealth/public_html/LastMileData/backups/' . $logFile );

