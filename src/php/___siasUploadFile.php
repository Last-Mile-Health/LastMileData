<?php

// !!!!! taken from SIAS code; modify !!!!!

// Extract POST variables
extract($_POST);

// Check for file error
if ( $_FILES["myFile"]["error"] <> 0 ) { $errorMessage = 'Error. File "<b>' . @$myFileName . '</b>" is invalid.'; }

// Check for empty selection
if( empty($_FILES["myFile"]["name"]) ) { $errorMessage = 'No file was selected.'; }

// Check for file size (file)
if ( $_FILES["myFile"]["size"] > 26214400 ) { $errorMessage = 'Error. File "<b>' . $myFileName . '</b>" is too large. Max file size is 25MB'; }

// If there's an error, display error message
if ( isset($errorMessage) )
{
    die("<br><br><br><div style='text-align:center'>$errorMessage Please <a href='$backURL2'>try again</a>.</div>");
}

// Get filename and extract extension
$myFileName = $_FILES["myFile"]["name"] ;

// Set target path
$targetPath = $_SERVER['DOCUMENT_ROOT'] . "/sias/uploads/" . $folder . "/" . $myFileName ;
while ( file_exists($targetPath) )      // !!!!! validate this step !!!!!
{
    assignUniqueFilename($myFileName) ;
    $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/sias/uploads/" . $folder . "/" . $myFileName ;
}

// Move file
move_uploaded_file($_FILES["myFile"]["tmp_name"], $targetPath) ;

// Generate query to insert image records into database "uploads" table
Include("../../sias/includes/cxn.php") ;
$query = "INSERT INTO tbl_data_uploads (myFilename, myFolder, mySecondaryKey) VALUES ('$myFileName', '$folder', '$sKey');" ;

// Dump query
queryDump($query) ;

// Execute query
mysqli_query($cxn, $query) or die("Failed to connect to database.<br>Please contact Avi Kenny for assistance.<br><a href='mailto:avi.kenny@gmail.com'>avi.kenny@gmail.com</a><br>914.316.3681<br><br><a href='' onclick='parent.$.fancybox.close();'>Return to previous page</a>") ;


// Redirect
if ( $stayInFrame == 'yes' ) { Echo "<script> window.location = '/sias/phpOther/A_Router.php?rMode=DB&page=$backPage'; </script>" ; }
else { Echo "<script> window.parent.location = '/sias/phpOther/A_Router.php?rMode=DB&page=$backPage'; </script>" ; }

?>