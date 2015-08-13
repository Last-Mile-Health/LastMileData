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
        <!--<script src="/reusables/lib/underscore.min.js"></script>-->
        <!--<script src="/reusables/lib/backbone.min.js"></script>-->
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
                    start: function(event, ui) {
                        ui.item.moveStartIndex = ui.item.index();
                        ui.item.moveStartDiv = $(event.target)[0].id;
                    },
                    beforeStop: function(event, ui){
                        ui.item.moveEndIndex = ui.helper.index();
                    },
                    stop: function(event, ui) {

    //                    ui.item.moveEndIndex = ui.item.index();

    //                    $('.outerTab').sortable("cancel");
    //                    $('.outerTab').sortable("destroy");

                        // Get start/end DIV indexes
                        for(var i=0; i<model_sidebar.length; i++) {
                            if (model_sidebar[i].id === ui.item.moveStartDiv) {
                                var modelStartDivIndex = i;
                            } else if (model_sidebar[i].id === ui.item.moveEndDiv) {
                                var modelEndDivIndex = i;
                            }
                        }
                        if (modelEndDivIndex === undefined) {
                            var modelEndDivIndex = modelStartDivIndex;
                        }

    //                    console.log(model_sidebar);

                        // Cancel the DOM manipulation
                        // NOTE:    There are many bugs in this behavior associated with different combinations of "moves".
                        //          The only way I've found to get around these is to reset the sortable, destroy it, and reset the sortable again
                        $('.outerTab').sortable();
                        $('.outerTab').sortable("cancel");
                        $('.outerTab').sortable("destroy");

                        // Use rivets to remove the item from its original position and set it in its new position
                        var reference = model_sidebar[modelStartDivIndex].tabs[ui.item.moveStartIndex-1];
                        var item = JSON.parse(JSON.stringify(reference));
                        model_sidebar[modelStartDivIndex].tabs.splice(ui.item.moveStartIndex-1,1);
                        model_sidebar[modelEndDivIndex].tabs.splice(ui.item.moveEndIndex-1, 0, item);
                        activateSortable();

    //                    // !!!!! Rebuild model here (as in "save") !!!!!
    //                    // !!!!! Rebuild model here (as in "save") !!!!!
    //                    // !!!!! Rebuild model here (as in "save") !!!!!
    //                     
    //                    var oldPositions = {
    //                        outer: {},
    //                        inner: {}
    //                    };
    //
    //                    // Populate oldPositions object
    //                    for (var i=0; i<model_sidebar.length; i++) {
    //
    //                        // Populate outer ID map
    //                        var outerID = model_sidebar[i].id;
    //                        oldPositions.outer[outerID] = i;
    //
    //                        // Populate inner ID map
    //                        for (var j=0; j<model_sidebar[i].tabs.length; j++) {
    //                            var innerID = model_sidebar[i].tabs[j].id;
    //                            oldPositions.inner[innerID] = {
    //                                outer: i,
    //                                inner: j
    //                            };
    //                        }
    //
    //                    }
    //
    //                    var newSidebarModel = [];
    //
    //                    // Set order of outer tabs
    //                    $('#sidebarDIV > div').each(function(){
    //
    //                        // Create deep copy of outer tab; clear inner tabs array; push to newSidebarModel
    //                        var outerID = $(this)[0].id;
    //                        var reference = model_sidebar[oldPositions.outer[outerID]];
    //                        var copy = JSON.parse(JSON.stringify(reference));
    //                        copy.tabs = [];
    //                        newSidebarModel.push(copy);
    //
    //                        // Add inner tabs (in proper order) to newSidebarModel
    //                        $(this).find('.innerTab').each(function(){
    //                            var innerID = $(this)[0].id;
    //
    //                            // Get inner tab; create deep copy
    //                            var reference = model_sidebar[oldPositions.inner[innerID].outer].tabs[oldPositions.inner[innerID].inner];
    //                            var copy = JSON.parse(JSON.stringify(reference));
    //                            newSidebarModel[newSidebarModel.length-1].tabs.push(copy);
    //                        });
    //
    //                    });
    //                    model_sidebar = JSON.parse(JSON.stringify(newSidebarModel));
    //                    console.log('model_sidebar[0].tabs[0].name');
    //                    console.log(model_sidebar[0].tabs[0].name);
    //                    console.log('newSidebarModel[0].tabs[0].name');
    //                    console.log(newSidebarModel[0].tabs[0].name);
    ////                    sidebar_model = newSidebarModel;
    //                
    //                    // !!!!! Rebuild model here (as in "save") !!!!!
    //                    // !!!!! Rebuild model here (as in "save") !!!!!
    //                    // !!!!! Rebuild model here (as in "save") !!!!!

                    },
                    over: function(event, ui) {
                        ui.item.moveEndDiv = event.target.id;
                    },
    //                change: function(event, ui) {
    //                    ui.item.moveEndIndex = ui.helper.index();
    //                },
    //                handle: '.moveInner',
    //                placeholder: '?'
                    connectWith: '.outerTab'
                });
            
            
            }
            
            
            $('#btn_displayOrder').click(function(){
                var x = $("#sidebarDIV").sortable("toArray");
                console.log(x);
                $('#displayOrder').val(x.toString());
            });
            
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
            
            // !!!!! IDs need to be automatically generated !!!!!
            // !!!!! Remove position attributes; position is implicitly defined by array order !!!!!
            
            // Create object that holds maps from IDs to the original positions, for both outer and inner tabs
//            var oldPositions = {
//                outer: {},
//                inner: {}
//            };
            
//            // Populate oldPositions object
//            for (var i=0; i<model_sidebar.length; i++) {
//                
//                // Populate outer ID map
//                var outerID = model_sidebar[i].id;
//                oldPositions.outer[outerID] = i;
//                
//                // Populate inner ID map
//                for (var j=0; j<model_sidebar[i].tabs.length; j++) {
//                    var innerID = model_sidebar[i].tabs[j].id;
//                    oldPositions.inner[innerID] = {
//                        outer: i,
//                        inner: j
//                    };
//                }
//                
//            }
            
            // !!!!! Test extensively; multiple edits/sends !!!!!
            $('#btn_save').click(function(){
                
//                var newSidebarModel = [];
                
//                // Set order of outer tabs
//                $('#sidebarDIV > div').each(function(){
//                    
//                    // Create deep copy of outer tab; clear inner tabs array; push to newSidebarModel
//                    var outerID = $(this)[0].id;
//                    var reference = model_sidebar[oldPositions.outer[outerID]];
//                    var copy = JSON.parse(JSON.stringify(reference));
//                    copy.tabs = [];
//                    newSidebarModel.push(copy);
//                    
//                    // Add inner tabs (in proper order) to newSidebarModel
//                    $(this).find('.innerTab').each(function(){
//                        var innerID = $(this)[0].id;
//
//                        // Get inner tab; create deep copy
//                        var reference = model_sidebar[oldPositions.inner[innerID].outer].tabs[oldPositions.inner[innerID].inner];
//                        var copy = JSON.parse(JSON.stringify(reference));
//                        newSidebarModel[newSidebarModel.length-1].tabs.push(copy);
//                    });
//                    
//                });
                
                // Create a copy of model_sidebar, serialize, and send to server
                // !!!!! does this need to be a deep copy ????? --> JSON.parse(JSON.stringify(object));
                    
                
////                console.log(newSidebarModel);
//                // Serialize model_sidebar copy and send to server
//                var objectData = JSON.stringify(newSidebarModel);
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
            
            $('.addInner').click(function(){
                model_sidebar[0].tabs.push({
                    id: 1, // !!!!!
                    type: 'frag',
                    name: 'New page',
                    link: 'Insert link here'
                });
            });
            
            $('.deleteInner').click(function(){
                alert('delete');
            });
            
            $('#btn_sort').click(function(){
                model_sidebar.sort(function(a,b){
                    if (a.order > b.order) {
                        return 1;
                    } else if (a.order < b.order) {
                        return -1;
                    } else {
                        return 0;
                    }
                });
            });
            
//            $('#btn_sortable').click(function(){
//                
//                
//                
//                
//                
//            // jQuery UI sortable: inner tabs
//            $('.outerTab').sortable({
//                cursor: 'move',
//                items: '> .innerTab',
//                start: function(event, ui) {
//                    ui.item.moveStartIndex = ui.item.index();
//                    ui.item.moveStartDiv = $(event.target)[0].id;
//                },
//                beforeStop: function(event, ui){
//                    ui.item.moveEndIndex = ui.helper.index();
//                },
//                stop: function(event, ui) {
//                    
////                    ui.item.moveEndIndex = ui.item.index();
//                    
////                    $('.outerTab').sortable("cancel");
////                    $('.outerTab').sortable("destroy");
//                    
//                    // Get start/end DIV indexes
//                    for(var i=0; i<model_sidebar.length; i++) {
//                        if (model_sidebar[i].id === ui.item.moveStartDiv) {
//                            var modelStartDivIndex = i;
//                        } else if (model_sidebar[i].id === ui.item.moveEndDiv) {
//                            var modelEndDivIndex = i;
//                        }
//                    }
//                    if (modelEndDivIndex === undefined) {
//                        var modelEndDivIndex = modelStartDivIndex;
//                    }
//                    
////                    console.log(model_sidebar);
//                    
//                    $('.outerTab').sortable("cancel");
//                    $('.outerTab').sortable("destroy");
//                    
//                    var reference = model_sidebar[modelStartDivIndex].tabs[ui.item.moveStartIndex-1];
//                    var item = JSON.parse(JSON.stringify(reference));
////                    var item = {
////                        id: 1, // !!!!!
////                        type: 'frag',
////                        name: 'New page',
////                        link: 'Insert link here'
////                    };
//                    model_sidebar[modelStartDivIndex].tabs.splice(ui.item.moveStartIndex-1,1);
//                    model_sidebar[modelEndDivIndex].tabs.splice(ui.item.moveEndIndex-1, 0, item);
//
////                    // !!!!! Rebuild model here (as in "save") !!!!!
////                    // !!!!! Rebuild model here (as in "save") !!!!!
////                    // !!!!! Rebuild model here (as in "save") !!!!!
////                     
////                    var oldPositions = {
////                        outer: {},
////                        inner: {}
////                    };
////
////                    // Populate oldPositions object
////                    for (var i=0; i<model_sidebar.length; i++) {
////
////                        // Populate outer ID map
////                        var outerID = model_sidebar[i].id;
////                        oldPositions.outer[outerID] = i;
////
////                        // Populate inner ID map
////                        for (var j=0; j<model_sidebar[i].tabs.length; j++) {
////                            var innerID = model_sidebar[i].tabs[j].id;
////                            oldPositions.inner[innerID] = {
////                                outer: i,
////                                inner: j
////                            };
////                        }
////
////                    }
////
////                    var newSidebarModel = [];
////
////                    // Set order of outer tabs
////                    $('#sidebarDIV > div').each(function(){
////
////                        // Create deep copy of outer tab; clear inner tabs array; push to newSidebarModel
////                        var outerID = $(this)[0].id;
////                        var reference = model_sidebar[oldPositions.outer[outerID]];
////                        var copy = JSON.parse(JSON.stringify(reference));
////                        copy.tabs = [];
////                        newSidebarModel.push(copy);
////
////                        // Add inner tabs (in proper order) to newSidebarModel
////                        $(this).find('.innerTab').each(function(){
////                            var innerID = $(this)[0].id;
////
////                            // Get inner tab; create deep copy
////                            var reference = model_sidebar[oldPositions.inner[innerID].outer].tabs[oldPositions.inner[innerID].inner];
////                            var copy = JSON.parse(JSON.stringify(reference));
////                            newSidebarModel[newSidebarModel.length-1].tabs.push(copy);
////                        });
////
////                    });
////                    model_sidebar = JSON.parse(JSON.stringify(newSidebarModel));
////                    console.log('model_sidebar[0].tabs[0].name');
////                    console.log(model_sidebar[0].tabs[0].name);
////                    console.log('newSidebarModel[0].tabs[0].name');
////                    console.log(newSidebarModel[0].tabs[0].name);
//////                    sidebar_model = newSidebarModel;
////                
////                    // !!!!! Rebuild model here (as in "save") !!!!!
////                    // !!!!! Rebuild model here (as in "save") !!!!!
////                    // !!!!! Rebuild model here (as in "save") !!!!!
//                    
//                },
//                over: function(event, ui) {
//                    ui.item.moveEndDiv = event.target.id;
//                },
////                change: function(event, ui) {
////                    ui.item.moveEndIndex = ui.helper.index();
////                },
////                handle: '.moveInner',
////                placeholder: '?'
//                connectWith: '.outerTab'
//            });
//                
//                
//                
//                
//                
//                
//                
//            });
            
        });
        
        
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
            <button id="btn_sort" class="btn btn-success">Sort model (order)</button>
            <button id="btn_displayOrder" class="btn btn-success">Display sort order</button>
            <button id="btn_sortable" class="btn btn-success">Reset sortable</button>
        </div>
        
        <div><input id="displayOrder"></div>
        
        <div id="sidebarDIV">
            <div rv-each-sidebar="model_sidebar" class="outerTab" rv-id="sidebar.id">
                <input rv-value="sidebar.name">
                <div rv-each-tabs="sidebar.tabs" class="innerTab" rv-id="tabs.id" rv-data-type="tabs.type" rv-data-link="tabs.link">
                    <input rv-value="tabs.name">
                    <input rv-value="tabs.link">
                    <!--<button class="moveInner btn btn-xs btn-success">&varr;</button>-->
                    <button class="deleteInner btn btn-xs btn-danger">X</button>
                </div>
                <button class="addInner btn btn-sm btn-success">+</button>
            </div>
        </div>
        
    </body>
</html>
