<!DOCTYPE html>

<html>
    
    <head>
        
        <title>Form Builder - prototype</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <meta name='robots' content='noindex'>
        <link rel='icon' type='image/png' href='/LastMileData/src/images/lmd_icon_v20140916.png'>
        <script src="/LastMileData/lib/jquery.min.js"></script>
        <script src="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.js"></script>
        <script src="/LastMileData/lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.css"  type="text/css" />
        <link rel='stylesheet' type='text/css' media='all' href='/LastMileData/src/css/fhwForms_v20140916.css'> <!-- Stylesheet specific to forms; must come after bootstrap stylesheet to override -->
        
        <!-- !!!!! TEMP NEW !!!!! -->
        <script src="/LastMileData/lib/jQuery-contextMenu/jquery.contextMenu.js"></script>
        <link rel="stylesheet" href="/LastMileData/lib/jQuery-contextMenu/jquery.contextMenu.css"  type="text/css" />
        <!-- !!!!! TEMP NEW !!!!! -->
        
        <style>
            /* Draggable "screen objects" */
            .dragMe, .dragMe input { cursor:pointer; margin:0px; }
            
            /* Drop area */
            .drop-hover { border: 3px solid #33CC33; }
            
            /* object types */
            [data-fieldType] {
                margin: 8px; width:80px; height:24px; text-align:center;
            }
            [data-fieldType="label"] {
                border:1px dotted grey;
            }
            [data-fieldType="textbox"] {
                color:grey; border:1px solid black;
            }
            
            /* Selection styles */
            .ui-selecting:not(.ui-wrapper):not(.page) { background-color:#A3FF85; }
            .ui-selected:not(.ui-wrapper):not(.page) { background-color:#33CC33; }
            .testSelect2:not(.ui-wrapper):not(.page) { border: 3px solid black }
            
            /* Resizable handles */
            .ui-resizable:hover > .ui-resizable-n { width: 6px; height: 6px; background-color: orange; }
            .ui-resizable:hover > .ui-resizable-s { width: 6px; height: 6px; background-color: orange; }
            .ui-resizable:hover > .ui-resizable-e { width: 6px; height: 6px; background-color: orange; }
            .ui-resizable:hover > .ui-resizable-w { width: 6px; height: 6px; background-color: orange; }
            .ui-resizable-n{ top: -2px; left:50%; }
            .ui-resizable-s{ bottom: -2px; left: 50%; }
            .ui-resizable-e{ right:-2px; top:50%; }
            .ui-resizable-w{ left:-2px; top:50%; }
        </style>
        
        <script>
        $(document).ready(function(){
            
            var gridSize = 8; // !!!!! make this configurable !!!!!
            var resizeHandle = "";
            
            // Holds a selection of "screen objects" (fields, images, etc; within .page)
            var activeElements = {
                
                // Holds the selection (jQuery instance) or a single property (length) to represent empty selection
                $elements: { length:0 },
                
                // Update $elements object with current selection of screen objects
                updateSelection: function(){
                    // no items are selected (other than .page
                    if ( $('.ui-selected:not(.page)').length > 0 ) {
                        this.$elements = $('.ui-selected:not(.page)');
                    }
                    // some items are selected
                    else {
                        this.$elements = { length:0 };
                    }
                },
                
                // Adds screen element with given id to selection
                addToSelection: function(id){
                    $('#' + id).addClass('ui-selected');
                    if (this.$elements.length > 0) {
                        this.$elements = this.$elements.add('#' + id);
                    } else {
                        this.$elements = $('#' + id);
                    }
                },
                
                // Removes the screen object with id='myId' from selection
                removeFromSelection: function(id){
                    if ( $('#' + id).hasClass('ui-selected') ) {
                        this.$elements = this.$elements.not('#' + id);
                        $('#' + id).removeClass('ui-selected');
                    }
                },
                
                // Removes all screen objects from selection
                clearSelection: function(){
                    this.$elements = { length:0 };
                    $('.ui-selected').each(function(){
                        $(this).removeClass('ui-selected');
                    });
                }
                
            };
            
            
            // Holds information about all fields currently on the page
            var allFields = {
                
                idCounter: 1,
                
                fields: {},
                
                addNewField: function addNewField(fieldType){
                    var fieldName = fieldType + "_" + this.idCounter;
                    if (this.fields[fieldName] !== "undefined") {
                        this.fields[fieldName] = {};    // !!!!! store field computed properties here (position, size, etc) !!!!!
                        this.idCounter++;
                    } else {
                        this.idCounter++;
                        addNewField(fieldType);
                    }
                    return fieldName;
                },
                
            }
            
            
            // Original drag elements
            $(".dragMe").draggable({
                grid: [gridSize,gridSize],
                helper: "clone",
                revert: "invalid",
                cancel:""
            });
            
            
            // Droppable
            $(".page").droppable({
                tolerance: "fit",
                hoverClass: "drop-hover",
                drop: function(ev, ui){
                    
                    var $clone = ui.helper.clone();
                    
                    if (!$clone.is('.inside-droppable')) {
                        $(this).append($clone.addClass('inside-droppable'));
                        
                        // Assign unique ID ("field name") to screen object
                        var fieldType = $clone.attr('data-fieldType');
                        $clone.prop('id', allFields.addNewField(fieldType));
                        
                        // Assign data values for size and position
                        $clone.data('st_posTop', ui.offset.top);
                        $clone.data('st_posLeft', ui.offset.left);
                        
                        // Draggable
                        $clone.draggable({
                            grid: [gridSize,gridSize],
                            stack: ".dragMe",
                            cancel:"",
                            tolerance: 'fit',
                            start: function (ev, ui) {
                                // Mark that the current element is the one being handled; add it to selection
                                $(this).addClass('lmd-handle');
                                activeElements.addToSelection($clone.prop('id'));
                            },
                            drag: function (ev, ui) {
                                if (activeElements.$elements.length > 1) {
                                    
                                    var deltaX = ui.position.left - ui.originalPosition.left;
                                    var deltaY = ui.position.top - ui.originalPosition.top;
                                    
                                    activeElements.$elements.each(function(){
                                        $(this).offset({
                                            top: $(this).data('st_posTop') + gridSize + deltaY,
                                            left: $(this).data('st_posLeft') + gridSize + deltaX
                                        });
                                    });
                                    
                                }
                            },
                            stop: function(ev, ui){
                                $(this).removeClass('lmd-handle');
                                
                                // Check to see if all elements were dropped within a page
                                if (!allDivsContained(activeElements.$elements,$('.page'))) {
                                    // !!!!! apply this to all !!!!!
                                    activeElements.$elements.each(function(){
                                        $(this).animate({
                                            top: $(this).data('st_posTop'),
                                            left: $(this).data('st_posLeft')
                                        });
                                    });
                                } else {
                                    activeElements.$elements.each(function(){
                                        $(this).data('st_posTop',$(this).position().top);
                                        $(this).data('st_posLeft',$(this).position().left);
                                    });
                                }
                                
                                // If a single element is selected, remove it from selection
                                // !!!!! this should only go if element wasn't "manually" selected !!!!!
                                if(activeElements.$elements.length === 1) {
                                    activeElements.removeFromSelection($clone.prop('id'));
                                }
                            }
                        });
                        
                        $clone.resizable({
                            handles: "n, s, e, w",
                            minHeight: 24,
                            minWidth: 24,
                            start: function(ev, ui){
                                resizeHandle = $(this).data('ui-resizable').axis;
                                activeElements.addToSelection($clone.prop('id'));
                                
                                if (activeElements.$elements.length > 1) {
                                    // Mark that the current element is the one being handled; add it to selection
                                    $(this).addClass('lmd-handle');
                                }
                            },
                            resize: function(ev, ui){
                                if (activeElements.$elements.length > 1) {
                                    var deltaX = (ui.size.width - ui.originalSize.width);
                                    var deltaY = (ui.size.height - ui.originalSize.height);
                                    activeElements.$elements.each(function(){
                                        $(this).width(ui.originalSize.width + deltaX);
                                        $(this).height(ui.originalSize.height + deltaY);
                                    });
                                }
                            },
                            stop: function(ev, ui){
                                $(this).removeClass('lmd-handle');
                                
                                // If divs are too big, shrink them until everything fits inside the page
                                while (!allDivsContained(activeElements.$elements,$('.page'))) {
                                    activeElements.$elements.each(function(){
                                        if (resizeHandle==='n') {
                                            // code
                                        } else if (resizeHandle==='s') {
                                            $(this).height($(this).height()-gridSize);
                                        } else if (resizeHandle==='e') {
                                            $(this).width($(this).width()-gridSize);
                                        } else if (resizeHandle==='w') {
                                            // code
                                        }
                                    });
                                }
                                
                                // If a single element is selected, remove it from selection
                                // !!!!! this should only go if element wasn't "manually" selected !!!!!
                                if(activeElements.$elements.length === 1) {
                                    activeElements.removeFromSelection($clone.prop('id'));
                                }
                                
                            }
                        });
                        
                        // Add resize handles
                        $clone.append("<div class='ui-resizable-handle ui-resizable-n'></div>");
                        $clone.append("<div class='ui-resizable-handle ui-resizable-s'></div>");
                        $clone.append("<div class='ui-resizable-handle ui-resizable-e'></div>");
                        $clone.append("<div class='ui-resizable-handle ui-resizable-w'></div>");
                        
                    }
                    
                }
            });
            
            
            // Keydown/keyup listeners (X/Y dragging constraints)
            $("body").keydown(function(e) {
                ek = e.keyCode;
                if (ek==88) {
                    // insert DOM flag
                    $('.dragMe').draggable( 'option', 'axis', 'x' );
                }
                if (ek==89) {
                    // insert DOM flag
                    $('.dragMe').draggable( 'option', 'axis', 'y' );
                }
            }).keyup(function(e) {
                // remove DOM flag
                $('.dragMe').draggable( 'option', 'axis', 'false' );
            });
            
            
            // Selectable elements
            $( ".page" ).selectable({
                filter: "div:not(.ui-resizable-handle), input",
                start: function(ev, ui) {
                    // code
                },
                stop: function(ev, ui){
                    activeElements.updateSelection();
                }
            });
            
            
            // After dragging or resizing, check to see if all elements are inside page
            // !!!!! this function should control the green page border !!!!!
            function allDivsContained($elements, $outerDivs){
                
                // Loop through elements; get coordinates and initialize 'contained' variable
                $elements.each(function(){
                    $(this).data('contained', false);
                });
                
                // Loop through outer divs; get coordinates and test containment
                $outerDivs.each(function(){
                    
                    var divTop = $(this).offset().top,
                        divBottom = divTop + $(this).height(),
                        divLeft = $(this).offset().left,
                        divRight = divLeft + $(this).width();
                    
                    $elements.each(function(){
                        
                        var c1 = $(this).position().top > divTop;
                        var c2 = ( $(this).position().top + $(this).height() ) < divBottom;
                        var c3 = $(this).position().left > divLeft;
                        var c4 = ( $(this).position().left + $(this).width() ) < divRight;
                        if ( c1 && c2 && c3 && c4 ) {
                            $(this).data('contained',true);
                        }
                        
                    });
                    
                });
                
                // Test containment of all elements; reset data variables for all elements
                var allElementsAreContained = true;
                $elements.each(function(){
                    if ( $(this).data('contained')===false ) { allElementsAreContained = false; }
                    $(this).data('contained','');
                });
                
                return allElementsAreContained;
            }
            
            
            // !!!!! START: TEST CONTEXT MENU !!!!!
            // http://medialize.github.io/jQuery-contextMenu/demo.html
            // SVGs: http://www.flaticon.com/
            $(function(){
                $.contextMenu({
                    selector: '[data-fieldType]', // !!!!! switch this to any page element BUT show different items based on which screen object was selected ?????
                    callback: function(key, options) {
                        switch (key) {
                            case "addToSelection":
                                activeElements.addToSelection(options.$trigger[0].id);
                                break;
                                
                            case "removeFromSelection":
                                activeElements.removeFromSelection(options.$trigger[0].id);
                                break;
                                
                            case "clearSelection":
                                activeElements.clearSelection();
                                break;
                                
                            default:
                                console.log("default. button clicked: " + key);
                        }
                    },
                    items: {
                        "cut": {name: "Cut", icon: "cut"},
                        "item_2": {name: "Copy", icon: "copy"},
                        "item_3": {name: "Delete", icon: "delete"},
                        "sep1": "-",
                        "item_4": {name: "View properties", icon: "magnify"},
                        "clearSelection": {name: "Clear selection", icon: "gears"},
                        "removeFromSelection": {name: "Remove from selection", icon: "meter"},
                        "addToSelection": {name: "Add to selection", icon: "tools"}
                    }
                });
                
            });
            // !!!!! END: TEST CONTEXT MENU !!!!!
            
            // !!!!! holding down control should disable draggables and resizables (to enable selection/deselection of screen objects)
            
            // !!!!! consider extending $.position to include bottom and right !!!!!
            
        });
        </script>
        
    </head>
    
    <body>
        <div id="container">
            
            <div class="dragMe" data-fieldType="textbox">text input</div>
            <div class="dragMe" data-fieldType="label">label</div>
            
            <div class="page"></div>
            <div class="page"></div>
            
        </div>
        
    </body>
    
</html>
