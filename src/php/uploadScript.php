<?php

// Start session; Set include path
session_start();
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );

// Require connection; set backURL
require_once("cxn.php");
$backURL = "/LastMileData/src/pages/page_medocs.php?folder=" . urlencode($_POST['folder']);

// Extract POST array
extract($_POST);

// Handle file uploads and folder creations
switch($_GET['switch'])
{
    case 'file':
        
        // Check for file error
        if ( $_FILES["myFile"]["error"] <> 0 ) { $errorMessage = 'Error code ' . $_FILES["myFile"]["error"] . '. File "<b>' . @$myFileName . '</b>" is invalid.'; }
        
        // Check for empty selection
        if( empty($_FILES["myFile"]["name"]) ) { $errorMessage = 'No file was selected.'; }
        
        // Check for file size (file)
        if ( $_FILES["myFile"]["size"] > 52428800 ) { $errorMessage = 'Error. File "<b>' . $myFileName . '</b>" is too large. Max file size is 50MB'; }
        
        // If there's an error, display error message
        if ( isset($errorMessage) )
        {
            // Terminate script         !!!!! consider styling this !!!!!
            die("<br><br><br><div style='text-align:center'>$errorMessage Please <a href='$backURL'>try again</a>.</div>");
        }
        
        // Get filename and extract extension
        $myFileName = $_FILES["myFile"]["name"] ;
        
        // Parse POSTed folder variable into path (replace all ">" characters with "/")
        $parsedFolder = str_replace(' > ','/',$_POST['folder']);
        
        // Set target path
        $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/" . $parsedFolder . "/" . $myFileName ;
        while ( file_exists($targetPath) )
        {
            // If filename already exists, increment it
            assignUniqueFilename($myFileName) ;
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/" . $parsedFolder . "/" . $myFileName ;
        }
        
        // Generate $uploadDatetime and $uploadedBy
        date_default_timezone_set("America/New_York");
        $uploadDatetime = date("Y-m-d H:i:s");
        $uploadedBy = $_SESSION['username'];
        
        // Run query to update file database record
        $query = "INSERT INTO uploads SET folder='$folder', filename='$myFileName', notes='$notes', uploadedBy='$uploadedBy', uploadDatetime='$uploadDatetime', isFolder=0;";
        $result = mysqli_query($cxn, $query) or die("Database error. Please <a href='$backURL'>try again</a>.</div>");
        
        // Move file
        move_uploaded_file($_FILES["myFile"]["tmp_name"], $targetPath) ;
        
        break ;

    case 'folder':
        
        // Parse new folder path (replace all ">" characters with "/")
        $parsedFolder = str_replace(' > ','/',$_POST['folder']);
        $folderPath = $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/" . $parsedFolder . "/" . $_POST['folderName'] ;
        
        // Create folder if it doesn't exist
        if ( ! is_dir($folderPath))
        {
            mkdir($folderPath);
        }
        
        // Generate $uploadDatetime and $uploadedBy
        date_default_timezone_set("America/New_York");
        $uploadDatetime = date("Y-m-d H:i:s");
        $uploadedBy = $_SESSION['username'];
        
        // Create folder record
        $query = "INSERT INTO uploads SET folder='$folder', filename='$folderName', notes='$notes', uploadedBy='$uploadedBy', uploadDatetime='$uploadDatetime', isFolder=1;";
        $result = mysqli_query($cxn, $query) or die("failure");
        break ;

    default:
        
        // Display error message
        Echo "Error. Please contact Avi Kenny for support." ;
}

// Redirect to backURL
Header("Location:$backURL");



function assignUniqueFilename(&$myFileName)
// Takes a filename string of one of the following forms, assigns it an "incremented" filename
// Example:
//              "myFile.jpg" --> "myFile (002).jpg"
//              "myFile (123).jpg" --> "myFile (124).jpg"
//              
// Note: $myFileName is passed by reference, not by value
{
    // Get file extension
    $myExt = end(explode(".", $myFileName)) ;
    $strLength = strlen($myFileName) ;
    $extLength = 1 + strlen($myExt) ;
    
    // Get filename (without extension)
    $myFileWithoutExt = substr($myFileName, 0, $strLength - $extLength) ;
    $parenthClose = substr($myFileWithoutExt,-1) ;
    $parenthOpen = substr(substr($myFileWithoutExt,-5),0,1) ;
    $parenthNum = substr(substr($myFileWithoutExt,-4),0,3) ;
    
    // Get filename (without incremental number)
    $myFileWithoutNum = substr($myFileWithoutExt, 0, $strLength - ($extLength + 5)) ;

    // Test to see if filename ends in "(###)"
    if ( $parenthOpen == "(" && $parenthClose == ")" )
        {
            // If last five characters follow pattern "(###)", increment number
            $newNum = sprintf('%03d', $parenthNum + 1) ;
            $myFileName = $myFileWithoutNum . "(" . $newNum . ")." . $myExt ;
        }
        else
        {
            // Else append "(002)" to filename
            $myFileName = $myFileWithoutExt . " (002)." . $myExt ;
        }
}

?>