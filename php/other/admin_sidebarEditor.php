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
        
        <!-- !!!!! WET with page_dataportal.php !!!!! -->
        <!-- queries and echos sidebar_model -->
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
        <script src="/LastMileData/lib/rivets.bundled.min.js"></script>
        <script src="/LastMileData/lib/multiselect.min.js"></script>
        
        <script>
        $(document).ready(function(){
            
            // from page_dataportal.js; configures/implements Rivets.js
            rivets.configure({templateDelimiters: ['{{', '}}']});
            
            
            // Initialize dpObjects object (mechanism for assigning unique IDs to sidebar components)
            var dpObjects = {
                idList: [],
                idHash: function(){
                    var myHash = "";
                    for(var i=0; i<sidebar_model_edit.length; i++){
                        myHash += sidebar_model_edit[i].id;
                        for(var j=0; j<sidebar_model_edit[i].tabs.length; j++){
                            myHash += sidebar_model_edit[i].tabs[j].id;
                        }
                    }
                    return myHash;
                },
                getNewID: function(){
                    var newID = "id_1";
                    while (this.idList.indexOf(newID)!==-1) {
                        var random = Math.floor(Math.random()*(10000))+1;
                        newID = "id_" + random;
                    }
                    this.idList.push(newID);
                    return newID;
                }
            };
            
            
            // Populate dpObjects.idList
            for(var i=0; i<sidebar_model_edit.length; i++){
                dpObjects.idList.push(sidebar_model_edit[i].id);
                for(var j=0; j<sidebar_model_edit[i].tabs.length; j++){
                    dpObjects.idList.push(sidebar_model_edit[i].tabs[j].id);
                }
            }
            
            
            // Set click handlers for ADD / DELETE buttons
            var sidebar_actions = {
                
                // Add a new OUTER tab
                addOuter: function(){
                    sidebar_model_edit.push({
                        id: "1", // !!!!! need way to set IDs !!!!!
                        name: 'New Outer Tab',
                        tabs: [{
                            id: dpObjects.getNewID(),
                            type: 'dp_frag',
                            name: 'New page',
                            link: 'Insert link here',
                            permissions: 'admin'
                        }]
                    });
                },
                
                // Add a new INNER tab
                addInner: function(){
                    // Get ID of containing DIV and corresponding index
                    var id = $(this).parent().parent().attr('id');
                    var index = getIndex(id, sidebar_model_edit);

                    // Push new tab to proper outer tab DIV
                    sidebar_model_edit[index].tabs.push({
                        id: dpObjects.getNewID(),
                        type: 'dp_frag',
                        name: 'New page',
                        link: 'Insert link here',
                        permissions: 'admin'
                    });
                },
                
                // Delete OUTER tab
                deleteOuter: function(){
                    // Get ID of item and corresponding indexes
                    var id = $(this).parent().attr('id');
                    var index = getIndex(id, sidebar_model_edit);
                    
                    // Remove the item
                    sidebar_model_edit.splice(index,1);
                },
                
                // Delete INNER tab
                deleteInner: function(){
                    // Get ID of item and corresponding indexes
                    var id = $(this).parent().attr('id');
                    var index = getIndex(id, sidebar_model_edit);

                    // Remove the item
                    sidebar_model_edit[index.outer].tabs.splice(index.inner,1);
                }
                
            };
            
            
            // Bind #sidebarDIV_edit with Rivets.js
            rivets.bind($('#sidebarDIV_edit'), {sidebar_model_edit: sidebar_model_edit, sidebar_actions: sidebar_actions});
            
            
            // Run "applyJquery" function and changes
            applyJquery();
            
            // Create an event loop to apply the "applyJquery" function on model initialization and whenever model undergoes a "configuration change"
            // model "configuration changes" include (1) addition of an outer/inner tab, (2) deletion of an outer/inner tab, or (3) re-ordering of tabs
            var oldHash = "";
            setInterval(function(){
                var newHash = dpObjects.idHash();
                if(oldHash!==newHash) {
                    applyJquery();
                }
                oldHash = newHash;
            }, 50);
            
            
            // These functions are run whenever model "size" changes (i.e. )
            function applyJquery() {
                // !!!!! permissions change when inner tab is moved !!!!!
                $(".permissions").multiselect({
                    selectedList: 3
                });
            }
            
            
            // jQuery UI sortable: outer tabs
            $('#sidebarDIV_edit').sortable({
                cursor: "move",
                start: function(event, ui) {
                    ui.item.moveStartIndex = ui.item.index();
                },
                stop: function(event, ui) {
                    ui.item.moveEndIndex = ui.item.index();
                    $('#sidebarDIV_edit').sortable("cancel");
//                    $('#sidebarDIV_edit').sortable("destroy");
                    var reference = sidebar_model_edit[ui.item.moveStartIndex];
                    var item = JSON.parse(JSON.stringify(reference));
                    sidebar_model_edit.splice(ui.item.moveStartIndex,1);
                    sidebar_model_edit.splice(ui.item.moveEndIndex, 0, item);
                }
            });
            
            activateSortable();
            
            function activateSortable() {
                
                // jQuery UI sortable: inner tabs
                $('.outerTab').sortable({
                    cursor: 'move',
                    items: '> .innerTab',
                    connectWith: '.outerTab',
                    start: function(event, ui) {
                        // Get start index of item and start outer DIV
                        ui.item.moveStartIndex = ui.item.index();
                        ui.item.moveStartDiv = $(event.target)[0].id;
                    },
                    over: function(event, ui) {
                        // Get end outer DIV
                        ui.item.moveEndDiv = event.target.id;
                    },
                    beforeStop: function(event, ui){
                        // Get end index
                        ui.item.moveEndIndex = ui.helper.index();
                    },
                    stop: function(event, ui) {

                        // Get start/end DIV indexes
                        for(var i=0; i<sidebar_model_edit.length; i++) {
                            if (sidebar_model_edit[i].id === ui.item.moveStartDiv) {
                                var modelStartDivIndex = i;
                            } else if (sidebar_model_edit[i].id === ui.item.moveEndDiv) {
                                var modelEndDivIndex = i;
                            }
                        }
                        // If modelEndDivIndex is undefined, then the inner tab didn't move to a different outer DIV
                        if (modelEndDivIndex === undefined) {
                            var modelEndDivIndex = modelStartDivIndex;
                        }

                        // Cancel the jQueryUI DOM manipulation
                        // NOTE: There are many bugs in this behavior associated with different combinations of "moves". The only way I've found to get around these is to reset the sortable, destroy it, and reset the sortable again
                        $('.outerTab').sortable();
                        $('.outerTab').sortable("cancel");
                        $('.outerTab').sortable("destroy");
                        
                        // Use rivets to remove the item from its original position and set it in its new position
                        // !!!!! all indexes below are behaving oddly; needed to switch from "-1" to "-2" !!!!!
                        var reference = sidebar_model_edit[modelStartDivIndex].tabs[ui.item.moveStartIndex-2];
//                        var reference = sidebar_model_edit[modelStartDivIndex].tabs[ui.item.moveStartIndex-1];
                        var item = JSON.parse(JSON.stringify(reference));
                        sidebar_model_edit[modelStartDivIndex].tabs.splice(ui.item.moveStartIndex-2,1);
//                        sidebar_model_edit[modelStartDivIndex].tabs.splice(ui.item.moveStartIndex-1,1);
                        sidebar_model_edit[modelEndDivIndex].tabs.splice(ui.item.moveEndIndex-2, 0, item);
//                        sidebar_model_edit[modelEndDivIndex].tabs.splice(ui.item.moveEndIndex-1, 0, item);
                        
                        // Reset the sortable
                        activateSortable();
                    }
                });
            }
            
            
            $('#btn_logModel').click(function(){
                console.log(sidebar_model_edit);
            });
            
            
            $('#btn_logStructure').click(function(){
                for(var i=0; i<sidebar_model_edit.length; i++){
                    var string = sidebar_model_edit[i].name + ": ";
                    for(var j=0; j<sidebar_model_edit[i].tabs.length; j++){
                        string += sidebar_model_edit[i].tabs[j].name + ", ";
                    }
                    string = string.slice(0,-2);
                    console.log(string);
                }
                console.log('');
            });
            
            
            $('#btn_save').click(function(){
                // Serialize the model and send it to the server
                var objectData = JSON.stringify(sidebar_model_edit);
                var queryString = "UPDATE lastmile_dataportal.tbl_json_objects SET objectData='" + addSlashes(objectData) + "' WHERE objectName='Data Portal sidebar'";
                var myData = {'queryString': queryString, 'rKey': 1, 'transaction': 0} ;
                $.ajax({
                        type: "POST",
                        url: "/LastMileData/php/scripts/ajaxSendQuery.php",
                        data: myData,
                        dataType: "json",
                        success: function(data) {
                            console.log("success!");
                        },
                        error: function() {
                            console.log("error :/");
                        }
                });
            });
            
        });
        
        // Given the ID of an inner/outer tab, return the current index(es) representing its position
        // Assumes that all IDs are unique, regardless of whether the tab is an "inner" or "outer" tab
        function getIndex(id, sidebar_model_edit) {
            
            var match = 'not found';
            
            // Test outer tabs
            for(var i=0; i<sidebar_model_edit.length; i++) {
                if(sidebar_model_edit[i].id === id) {
                    match = i;
                }
            }
            
            // Test inner tabs
            for(var i=0; i<sidebar_model_edit.length; i++) {
                for(var j=0; j<sidebar_model_edit[i].tabs.length; j++) {
                    if(sidebar_model_edit[i].tabs[j].id === id) {
                        match = {
                            outer: i,
                            inner: j
                        };
                    }
                }
            }
            
            return match;
        }
        
        // WET with indicators.js, deqa.js
        function addSlashes(str) {
            return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
        }
        </script>
    </head>
    <body>
        
        <div>
            <button id="btn_logModel" class="btn btn-success">Log model</button>
            <button id="btn_logStructure" class="btn btn-success">Log structure</button>
            <button id="btn_save" class="btn btn-success">Save model</button>
            <button id="btn_test" class="btn btn-success">TEST</button>
        </div>
        
        <div id="sidebarDIV_edit">
            <div rv-each-sidebar="sidebar_model_edit" class="outerTab" rv-id="sidebar.id">
                <input class="ui-state-default ui-corner-all" rv-value="sidebar.name">
                <button class="deleteOuter btn btn-xs btn-danger" rv-on-click="sidebar_actions.deleteOuter">X</button>
                <div rv-each-tabs="sidebar.tabs" class="innerTab" rv-id="tabs.id">
                    <input class="ui-state-default ui-corner-all" rv-value="tabs.name">
                    <input class="ui-state-default ui-corner-all" rv-value="tabs.link">
                    <select class="ui-state-default ui-corner-all" rv-value="tabs.type">
                        <option value="dp_frag">dp_frag</option>
                        <option value="dp_iframe">dp_iframe</option>
                        <option value="dp_markdown">dp_markdown</option>
                    </select>
                    <!-- !!!!! style="width:150px" !!!!! -->
                    <select multiple class="permissions" rv-value="tabs.permissions"> <!-- !!!!! This should eventually dynamically populate based on a list of user types stored in the database !!!!! -->
                        <option value="superadmin">superadmin</option>
                        <option value="admin">admin</option>
                        <option value="deqa">deqa</option>
                        <option value="user">user</option>
                        <option value="cht_gg">cht_gg</option>
                        <option value="cht_rc">cht_rc</option>
                    </select>
                    <!-- !!!!! use this as a handle -->
                    <!--<button class="moveInner btn btn-xs btn-success">&varr;</button>-->
                    <button class="deleteInner btn btn-xs btn-danger" rv-on-click="sidebar_actions.deleteInner">X</button>
                </div>
                <div class="addInnerDiv"><button class="addInner btn btn-sm btn-success" rv-on-click="sidebar_actions.addInner">+</button></div>
            </div>
        <button id="addOuter" class="btn btn-lg btn-success" rv-on-click="sidebar_actions.addOuter">+</button>
        <br><br>
        </div>
        
    </body>
</html>
