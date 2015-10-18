<!DOCTYPE html>
<html>
    <head>
        <title>DEV: Data Portal Sidebar Editor</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="admin_sidebarEditor.css">
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/lib/multiselect.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.css"  type="text/css" />
        
        <!-- queries and echos sidebar_model_edit -->
        <?php
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            $url1 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/json_objects/1";
            curl_setopt($ch,CURLOPT_URL,$url1);
            $json1 = curl_exec($ch);
            curl_close($ch);
            echo "<script>var sidebar_model_edit = JSON.parse($json1.objectData);</script>";
        ?>
        
        <script src="/LastMileData/lib/jquery.min.js"></script>
        <script src="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.js"></script>
        <script src="/LastMileData/lib/knockout/knockout-3.3.0.js"></script>
        <script src="/LastMileData/lib/knockout/knockout.mapping-latest.js"></script>
        <script src="/LastMileData/lib/multiselect.min.js"></script>
        <script src="/LastMileData/src/js/LMD_utilities.js"></script> <!-- THIS WILL FAIL -->
        <script src="admin_sidebarEditor.js"></script>
    </head>
    <body>
        
        <div>
            <button id="btn_save" class="btn btn-success">Save changes</button>
        </div>
        
        <div id="sidebarDIV_edit">
            <div id="sidebarDIV_edit2" data-bind="foreach:sidebar">
                <div class="outerTab" data-bind="attr: {id:id}"> <!-- rv-each-sidebar="sidebar_model_edit" -->
                    <input class="ui-state-default ui-corner-all" data-bind="value:name">
                    <button class="deleteOuter btn btn-xs btn-danger" data-bind="click:$root.actions.deleteOuter">X</button>
                    <button class="deleteOuter btn btn-xs btn-warning" data-bind="click:$root.actions.moveOuterUp">&#8593;</button>
                    <button class="deleteOuter btn btn-xs btn-warning" data-bind="click:$root.actions.moveOuterDown">&#8595;</button>
                    <div data-bind="foreach: tabs">
                        <div data-bind="attr: {id:id}" class="innerTab">
                            <input class="ui-state-default ui-corner-all" data-bind="value:name">
                            <input class="ui-state-default ui-corner-all" data-bind="value:link">
                            <select class="ui-state-default ui-corner-all" data-bind="value:type">
                                <option value="dp_frag">dp_frag</option>
                                <option value="dp_iframe">dp_iframe</option>
                                <option value="dp_markdown">dp_markdown</option>
                            </select>
                            <!-- !!!!! style="width:150px" !!!!! -->
                            <input class="ui-state-default ui-corner-all" data-bind="value:permissions">
                            <!-- !!!!! This should eventually dynamically populate based on a list of user types stored in the database !!!!! -->
<!--                            <select multiple class="permissions" data-bind="value:permissions">
                                <option value="superadmin">superadmin</option>
                                <option value="admin">admin</option>
                                <option value="deqa">deqa</option>
                                <option value="user">user</option>
                                <option value="cht_gg">cht_gg</option>
                                <option value="cht_rc">cht_rc</option>
                            </select>-->
                            <!-- !!!!! use this as a handle -->
                            <!--<button class="moveInner btn btn-xs btn-success">&varr;</button>-->
                            <button class="deleteInner btn btn-xs btn-danger" data-bind="click:$root.actions.deleteInner">X</button>
                            <button class="deleteOuter btn btn-xs btn-warning" data-bind="click:$root.actions.moveInnerUp">&#8593;</button>
                            <button class="deleteOuter btn btn-xs btn-warning" data-bind="click:$root.actions.moveInnerDown">&#8595;</button>
                        </div>
                    </div>
                    <div class="addInnerDiv"><button class="addInner btn btn-sm btn-success" data-bind="click:$root.actions.addInner">+</button></div>
                </div>
            </div>
        <button id="addOuter" class="btn btn-lg btn-success" data-bind="click:$root.actions.addOuter">+</button>
        <br><br>
        </div>
        
    </body>
</html>
