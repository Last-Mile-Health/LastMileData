<?php

session_start();
if( !isset($_SESSION['username']) ) { Header('Location:/LastMileData/'); }

// Set include path
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );

// Get query string
$currentFolder = @urldecode($_GET['folder']);

// Run queries to select files to populate table
require_once("cxn.php");
$query_folders = "SELECT * FROM uploads WHERE isFolder=1 AND folder = '$currentFolder' ORDER BY filename"; // 
$query_files = "SELECT * FROM uploads WHERE isFolder=0 AND folder = '$currentFolder' ORDER BY filename"; // folder = '$currentFolder'
$result_folders = mysqli_query($cxn, $query_folders) or die("failure");
$result_files = mysqli_query($cxn, $query_files) or die("failure");

?>
<!DOCTYPE html>

<html>
    
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name='robots' content='noindex'>
        <link rel='icon' type='image/png' href='/LastMileData/res/lmd_icon_v20140916.png'>
        <title>Last Mile Data</title>
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css"  type="text/css" />
        <script src="/LastMileData/lib/jquery.min.js"></script>
        <script src="/LastMileData/lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
        <script src="/LastMileData/src/js/loadContents_v20140916.js"></script>
        <script src="/LastMileData/src/js/modalFocus_v20140916.js"></script>
        <script src="/LastMileData/src/js/medocs_v20140916.js"></script>
        
        <!-- !!!!! Refactor into stylesheet !!!!! -->
        <style>
            
            table a {
                font-weight:bold;
            }
            
            .folderLink {
                background-color:#B19131;
            }
            
        </style>
        
    </head>
    
    <body style="display:none">
        
        <div id="load_navbar"></div>
        
        <div class="container">
            <div class="jumbotron">
                <h1>M&E Documents</h1>
                <p>This section of the site contains all key documents relevant to the M&E team, including strategy memos, program forms, survey reports, active/pending publications, team bios, and more.</p>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <h4 style="color:red">
                        <?php echo @$_GET['errorMessage']; ?>
                    </h4>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-9">
                    <h4>
                        <?php
                            // Get current folder and parse into breadcrumbs
                            $folderPieces = split(" > ",$currentFolder);
                            $path = "";
                            $i = 1;
                            foreach ($folderPieces as $value)
                            {
                                $path .= $value . " > ";
                                $currPath = substr($path, 0, -3) ;
                                echo "<a href='/LastMileData/src/pages/page_medocs.php?folder=" . urlencode($currPath) . "'>$value</a>";
                                if ( $i < count($folderPieces) ) { echo " > "; }
                                $i++;
                            }
                        ?>
                    </h4>
                </div>
                <div class="col-md-3" style="text-align:right">
                    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modal_addFile">Add file</a>
                    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modal_addFolder">Add folder</a>
                    <br><br>
                </div>
            </div>
            
            <table class="table table-striped table-bordered">
                <tr>
                    <th>Filename</th>
                    <th>Upload date/time</th>
                    <th>Uploaded by</th>
                    <th>Notes</th>
                    <th>Action</th>
                </tr>
                
                <?php
                
                While ( $row = mysqli_fetch_assoc($result_folders) )
                {
                    extract($row);
                    
                    echo "<tr>" . "\n";
                        echo "<td class='folderRow' colspan='3'><a href='/LastMileData/src/pages/page_medocs.php?folder=" . urlencode($currentFolder . " > " . $filename ) . "'><kbd class='folderLink'>$filename</kbd></a></td>" . "\n";
                        echo "<td class='folderRow'>$notes</td>" . "\n";
                        echo "<td class='folderRow'>" . "\n";
                            echo "<a href='/LastMileData/src/php/fileOps.php?mode=folder&foldername=" . urlencode($currentFolder . " > " . $filename ) . "'><kbd>Download</kbd></a>" . "\n";
//                            echo "<a href='$pk'><kbd>Edit</kbd></a>" . "\n";  // !!!!! build: to edit folder name !!!!!
                            echo "<a onclick='return confirm(\"Are you sure you want to delete this folder?\")' href='/LastMileData/src/php/fileOps.php?mode=delete_folder&filename=" . urlencode($filename) . "&foldername=" . urlencode($currentFolder) . "'><kbd>Delete</kbd></a>" . "\n";  // !!!!! Replace the onlick confirm with a bootstrap dialog !!!!!
                        echo "</td>" . "\n";
                    echo "</tr>" . "\n";
                }
                
                While ( $row = mysqli_fetch_assoc($result_files) )
                {
                    extract($row);
                    
                    echo "<tr>\n";
                        echo "<td>$filename</td>" . "\n";
                        echo "<td>$uploadDatetime</td>" . "\n";
                        echo "<td>$uploadedBy</td>" . "\n";
                        echo "<td>$notes</td>" . "\n";
                        echo "<td>" . "\n";
                            echo "<a href='/LastMileData/src/php/fileOps.php?mode=file&filename=" . urlencode($filename) . "&foldername=" . urlencode($currentFolder) . "'><kbd>Download</kbd></a>" . "\n";
                            echo "<a onclick='return confirm(\"Are you sure you want to delete this file?\")' href='/LastMileData/src/php/fileOps.php?mode=delete_file&filename=" . urlencode($filename) . "&foldername=" . urlencode($currentFolder) . "'><kbd>Delete</kbd></a>" . "\n";    // !!!!! Replace the onlick confirm with a bootstrap dialog !!!!!
                        echo "</td>" . "\n";
                    echo "</tr>" . "\n";
                }
                
                ?>
                
            </table>
        </div>
        
        
        
        <!-- START MODAL "ADD FILE" CODE -->
        <div id="modal_addFile" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h1 class="text-center">Add File</h1>
                    </div>
                    <div class="modal-body">    <!-- replace below script with upload.php -->
                        <form id='myForm' action='/LastMileData/src/php/uploadScript.php?switch=file' method='POST' enctype='multipart/form-data' class="form col-md-12 center-block">
                            <div class="form-group">
                                <input type="file" name="myFile" class="form-control input-lg">
                            </div>
                            <div class="form-group">
                                <input name="notes" type="text" class="form-control input-lg" placeholder="Notes">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success btn-lg btn-block">Submit</button>
                            </div>
                            <input type="hidden" name="folder" value="<?php echo $currentFolder; ?>">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12">
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                        </div>    
                    </div>
                </div>
            </div>
        </div>        
        <!-- END MODAL "ADD FILE" CODE -->
        
        
        
        <!-- START MODAL "ADD FOLDER" CODE -->
        <div id="modal_addFolder" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h1 class="text-center">Add Folder</h1>
                    </div>
                    <div class="modal-body">    <!-- replace below script with upload.php -->
                        <form id='myForm' action='/LastMileData/src/php/uploadScript.php?switch=folder' method='POST' class="form col-md-12 center-block">
                            <div class="form-group">
                                <input id="folderName" name="folderName" type="text" class="form-control input-lg" placeholder="Folder name">
                            </div>
                            <div class="form-group">
                                <input name="notes" type="text" class="form-control input-lg" placeholder="Notes">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success btn-lg btn-block">Submit</button>
                            </div>
                            <input type="hidden" name="folder" value="<?php echo $currentFolder; ?>">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12">
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                        </div>    
                    </div>
                </div>
            </div>
        </div>        
        <!-- END MODAL "ADD FOLDER" CODE -->
        
        
        
    </body>
    
</html>