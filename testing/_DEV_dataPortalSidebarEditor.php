<!DOCTYPE html>
<html>
    <head>
        <title>DEV: Data Portal Sidebar Editor</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="_DEV_dataPortalSidebarEditor.css">
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css"  type="text/css" />
        
        <!-- from page_dataportal.php; queries and echos model_sidebar -->
        <?php $ch = curl_init();curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);$url1 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/json_objects/1";curl_setopt($ch,CURLOPT_URL,$url1);$json1 = curl_exec($ch);curl_close($ch);echo "<script>var model_sidebar = JSON.parse($json1.objectData);</script>"; ?>
        
        <script src="/LastMileData/lib/jquery.min.js"></script>
        <script src="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.js"></script>
        <script src="/LastMileData/lib/rivets.bundled.min.js"></script>
        <script>
        $(document).ready(function(){
            
            // from page_dataportal.js; configures/implements Rivets.js
            rivets.configure({templateDelimiters: ['{{', '}}']});
            
            // Rivets-Backbone adapter
            rivets.adapters[':'] = {
                observe: function(obj, keypath, callback) {
                    obj.on('change:' + keypath, callback);
                },
                unobserve: function(obj, keypath, callback) {
                    obj.off('change:' + keypath, callback);
                },
                get: function(obj, keypath) {
                    return obj.get(keypath);
                },
                set: function(obj, keypath, value) {
                    obj.set(keypath, value);
                }
            };
            
            rivets.bind($('#sidebarDIV'), {model_sidebar: model_sidebar});
            
            // jQuery UI sortable: outer tabs
            $('#sidebarDIV').sortable({
                cursor: "move",
                start: function(event, ui) {
                    ui.item.moveStartIndex = ui.item.index();
                },
                stop: function(event, ui) {
                    ui.item.moveEndIndex = ui.item.index();
                    $('#sidebarDIV').sortable("cancel");
//                    $('#sidebarDIV').sortable("destroy");
                    var reference = model_sidebar[ui.item.moveStartIndex];
                    var item = JSON.parse(JSON.stringify(reference));
                    model_sidebar.splice(ui.item.moveStartIndex,1);
                    model_sidebar.splice(ui.item.moveEndIndex, 0, item);
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
                        for(var i=0; i<model_sidebar.length; i++) {
                            if (model_sidebar[i].id === ui.item.moveStartDiv) {
                                var modelStartDivIndex = i;
                            } else if (model_sidebar[i].id === ui.item.moveEndDiv) {
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
                        var reference = model_sidebar[modelStartDivIndex].tabs[ui.item.moveStartIndex-2];
//                        var reference = model_sidebar[modelStartDivIndex].tabs[ui.item.moveStartIndex-1];
                        var item = JSON.parse(JSON.stringify(reference));
                        model_sidebar[modelStartDivIndex].tabs.splice(ui.item.moveStartIndex-2,1);
//                        model_sidebar[modelStartDivIndex].tabs.splice(ui.item.moveStartIndex-1,1);
                        model_sidebar[modelEndDivIndex].tabs.splice(ui.item.moveEndIndex-2, 0, item);
//                        model_sidebar[modelEndDivIndex].tabs.splice(ui.item.moveEndIndex-1, 0, item);
                        
                        // Reset the sortable
                        activateSortable();
                    }
                });
            }
            
            
            $('#btn_logModel').click(function(){
                console.log(model_sidebar);
            });
            
            $('#btn_logStructure').click(function(){
                for(var i=0; i<model_sidebar.length; i++){
                    var string = model_sidebar[i].name + ": ";
                    for(var j=0; j<model_sidebar[i].tabs.length; j++){
                        string += model_sidebar[i].tabs[j].name + ", ";
                    }
                    string = string.slice(0,-2);
                    console.log(string);
                }
                console.log('');
            });
            
            $('#btn_save').click(function(){
                // Serialize the model and send it to the server
                var objectData = JSON.stringify(model_sidebar);
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
            
            // Add a new inner tab
            $('.addInner').click(function(){
                // Get ID of containing DIV and corresponding index
                var id = $(this).parent().parent().attr('id');
                var index = getIndex(id, model_sidebar);
                
                // Push new tab to proper outer tab DIV
                model_sidebar[index].tabs.push({
                    id: 'tempID', // !!!!! need way to set IDs
                    type: 'frag',
                    name: 'New page',
                    link: 'Insert link here'
                });
                
                // Call this to attach events to new DOM elements
                bindEvents();
            });
            
            // Add a new outer tab
            // !!!!! Causing problems; temporarily hold off !!!!!
//            $('#addOuter').click(function(){
//                model_sidebar.push({
//                    id: 1, // !!!!! need way to set IDs
//                    name: 'New Outer Tab',
//                    tabs: [{
//                        id: 1, // !!!!! need way to set IDs
//                        type: 'frag',
//                        name: 'New page',
//                        link: 'Insert link here'
//                    }]
//                });
//            });
            
            bindEvents();
            
            function bindEvents(){
                
                // Remove previously bound event
                $('.deleteOuter').off();
                $('.deleteInner').off();
                
                // Bind click handler: delete outer tabs
                $('.deleteOuter').click(function(){
                    // Get ID of item and corresponding indexes
                    var id = $(this).parent().attr('id');
                    var index = getIndex(id, model_sidebar);
                    
                    // Remove the item
                    model_sidebar.splice(index,1);
//                    bindEvents();
                });
                
                // Bind click handler: delete inner tabs
                $('.deleteInner').click(function(){
                    // Get ID of item and corresponding indexes
                    var id = $(this).parent().attr('id');
                    var index = getIndex(id, model_sidebar);

                    // Remove the item
                    model_sidebar[index.outer].tabs.splice(index.inner,1);
                });
                
            }
            
            
            
            $('#btn_test').click(function(){
                console.log('test');
            });
            
        });
        
        // Given the ID of an inner/outer tab, return the current index(es) representing its position
        // Assumes that all IDs are unique, regardless of whether the tab is an "inner" or "outer" tab
        function getIndex(id, model_sidebar) {
            
            var match = 'not found';
            
            // Test outer tabs
            for(var i=0; i<model_sidebar.length; i++) {
                if(model_sidebar[i].id === id) {
                    match = i;
                }
            }
            
            // Test inner tabs
            for(var i=0; i<model_sidebar.length; i++) {
                for(var j=0; j<model_sidebar[i].tabs.length; j++) {
                    if(model_sidebar[i].tabs[j].id === id) {
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
        
        <div id="sidebarDIV">
            <div rv-each-sidebar="model_sidebar" class="outerTab" rv-id="sidebar.id">
                <input rv-value="sidebar.name">
                <button class="deleteOuter btn btn-xs btn-danger">X</button>
                <div rv-each-tabs="sidebar.tabs" class="innerTab" rv-id="tabs.id">
                    <input rv-value="tabs.name">
                    <input rv-value="tabs.link">
                    <select rv-value="tabs.type">
                        <option value="dp_frag">dp_frag</option>
                        <option value="dp_iframe">dp_iframe</option>
                        <option value="dp_markdown">dp_markdown</option>
                    </select>
                    <input rv-value="tabs.permissions">
                    <!-- !!!!! use this as a handle -->
                    <!--<button class="moveInner btn btn-xs btn-success">&varr;</button>-->
                    <button class="deleteInner btn btn-xs btn-danger">X</button>
                </div>
                <div class="addInnerDiv"><button class="addInner btn btn-sm btn-success">+</button></div>
            </div>
        </div>
        <!--<button id="addOuter" class="btn btn-lg btn-success">+</button>-->
        
    </body>
</html>
