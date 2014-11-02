<?php   session_start() ;                                       ?>
<?php   set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/sias/includes" );        ?>
<?php   Include("../../sias/includes/siasFunctions.php") ;      ?>
<?php   sessionCheck(array('USER')) ;                                 ?>
<?php   displayHeader() ;                 ?>

<?php


    echo "<b>Check parameters with 'ini_get' function: </b>" ;
    echo "<br>" ;
    echo "upload_max_filesize: " . ini_get('upload_max_filesize');
    echo "<br>" ;
    echo "max_file_uploads: " . ini_get('max_file_uploads');
    echo "<br>" ;
    echo "post_max_size: " . ini_get('post_max_size');
?>
