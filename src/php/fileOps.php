<?php

// Set include path
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );

if ( $_GET['mode'] == 'delete_file' )
{
    // !!!!! wrap this code in a "transaction" !!!!!
    // 
    // Get vars; set filepath
    $fileName = urldecode($_GET['filename']);
    $folderName = str_replace(' > ','/',urldecode($_GET['foldername']));
    $filePath = $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/" . $folderName . "/" . $fileName;
    
    // Delete file
    unlink($filePath);
    
    // Delete MySQL record
    require_once("cxn.php");        // !!!!! modify code below to account for folder name !!!!!
    $fQuery = "DELETE FROM uploads WHERE fileName='$fileName'";
    $result = mysqli_query($cxn,$fQuery) or die("failure") ;
    
    // Redirect
    $backURL = "/LastMileData/src/pages/page_medocs.php?folder=" . urlencode(str_replace('/',' > ',$folderName));
    Header("Location:$backURL");
}

if ( $_GET['mode'] == 'delete_folder' )
{
    // !!!!! wrap this code in a "transaction" !!!!!
    
    // Get vars; set filepath
    $fileName = urldecode($_GET['filename']);
    $folderName = urldecode($_GET['foldername']);
    $parsedFolderName = str_replace(' > ','/',urldecode($_GET['foldername']));
    $dirToDelete = $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/" . $parsedFolderName . "/" . $fileName;
    
    // Remove entire directory and all contents
    recursiveRemoveDirectory($dirToDelete);
    
    // Delete MySQL records
    // !!!!! modify this to delete subfolders and files in subfolders !!!!!
    require_once("cxn.php");
    $fQuery1 = "DELETE FROM uploads WHERE folder LIKE '$folderName > $fileName%'";
    $fQuery2 = "DELETE FROM uploads WHERE folder='$folderName' AND filename='$fileName'";
    $result1 = mysqli_query($cxn,$fQuery1) or die("failure") ;
    $result2 = mysqli_query($cxn,$fQuery2) or die("failure") ;
    
    // DEBUG START
//    $query3 = "INSERT INTO test (col_1, col_2) VALUES ('" . addslashes($fQuery1) . "','" . addslashes($fQuery2) . "');";
//    mysqli_query($cxn,$query3) or die("failure") ;
    // DEBUG END
    
    // Redirect
    $backURL = "/LastMileData/src/pages/page_medocs.php?folder=" . urlencode($folderName);
    Header("Location:$backURL");
}

if ( $_GET['mode'] == 'file' )
{
    // Get vars; set filepath
    $fileName = urldecode($_GET['filename']);
    $folderName = str_replace(' > ','/',urldecode($_GET['foldername']));
    $filePath = $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/" . $folderName . "/" . $fileName;
    
    // !!!!! DEBUG START !!!!!
//    require_once("cxn.php");
//    $query = "INSERT INTO test SET col_1='$filePath';";
//    $result = mysqli_query($cxn, $query) or die("Failure.");
    // !!!!! DEBUG END !!!!!
    
    // Download file
    sendHeaders($filePath);
}

if ( $_GET['mode'] == 'folder' )
{
    // set zip file destination and delete current file
    $destination = $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/zip/LastMileData.zip" ;
    @unlink($destination);
    
    // Set folder path of folder to archive
    $folderName = str_replace(' > ','/',urldecode($_GET['foldername']));
    $folderPath = $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/$folderName";
    
    // Inclide zipEntireFolder class and zip folder
    include('zipEntireFolder.php');
    zipEntireFolder::zipDir($folderPath, $destination);
    
    // Download zip file
    sendHeaders($destination);
}



function sendHeaders($filePath)
{
    $fileName = end(explode("/",$filePath));
    header("Content-Disposition: attachment; filename=\"" . basename($fileName) . "\"");
    header("Content-Type: application/force-download");
    header("Connection: close");
    echo file_get_contents($filePath);
}

function recursiveRemoveDirectory($directory)
{
    foreach(glob("{$directory}/*") as $file)
    {
        if(is_dir($file)) { 
            recursiveRemoveDirectory($file);
        } else {
            unlink($file);
        }
    }
    rmdir($directory);
}

?>