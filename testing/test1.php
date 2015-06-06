<!DOCTYPE html>

<html>
    
    <head>
        
        <title>Facility Form (MSH 01) - IPC MESH Tool</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <meta name='robots' content='noindex'>
        <link rel='icon' type='image/png' href='/LastMileData/res/lmd_icon_v20140916.png'>
        <script src="/LastMileData/lib/jquery.min.js"></script>
        <script src="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.js"></script>
        <script src="/LastMileData/lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
        
        <!-- !!!!! RELEVANT START !!!!! -->
        <script src="http://www.alohaeditor.org/download/aloha.min.js"></script>
        <!-- !!!!! RELEVANT END !!!!! -->
        
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.css"  type="text/css" />
        
        <style>
            body { font-size:200%; font-weight:bold; }
            #outer { margin:auto; width:600px; position:relative; top:100px; padding:10px; }
            #inner1 { width:500px; height:100px; background-color:red; margin:10px; padding:10px; }
            #inner2 { width:500px; height:100px; background-color:blue; margin:10px; padding:10px; }
            #logSpace { background-color:#dddddd; width:600px; height:300px; margin:10px; padding:10px; }
        </style>
        
        <script>
        $(document).ready(function(){
            
            $('#edit').click(function(){
                aloha.dom.query('.editable', document).forEach(aloha);
            });
            
            $('#freeze').click(function(){
                aloha.mahalo();
            });
            
            $('#big-bad-bold-button').on('click', aloha.ui.command(aloha.ui.commands.bold));
            
        });
        </script>
        
    </head>
    
    <body>
        
        <button id='edit'>Edit</button>
        <button id='freeze'>Freeze</button>
        <button id="big-bad-bold-button">B</button>
        
        <div contenteditable='true' style='margin:20px; padding:20px; width:500px; height:100px; background-color:lightgreen'>
            Regular contenteditable
        </div>
        <div class='editable' style='margin:20px; padding:20px; width:500px; height:100px; background-color:lightblue'>
            Aloha 1
        </div>
        <div class='editable' style='margin:20px; padding:20px; width:500px; height:100px; background-color:lightblue'>
            Aloha 2
        </div>
        
    </body>
    
</html>
