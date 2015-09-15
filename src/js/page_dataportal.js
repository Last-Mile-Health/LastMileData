$(document).ready(function(){

    // This variable is set to true if any data changes occur (currently only used by "admin_editData.php" fragment)
    DataPortal_GLOBALS = {
        anyChanges: false
    };

    // Configure rivets.js
    rivets.configure({templateDelimiters: ['{{', '}}']});

    // Rivers formatter: numbers (one-way)
    rivets.formatters.format = function(x, format) {
        if (x !== undefined && x !== null) {
            var type = format.split("-")[0];
            var num = format.split("-")[1] ? format.split("-")[1] : 1;
            switch(type) {
                case 'integer':
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    break;
                case 'percent': // !!!!! Modify for decimal places !!!!!
                    return (x*100).toFixed(1) + "%";
                    break;
                case 'decimal': // Takes "decimal-1", "decimal-2", etc.
                    return x.toFixed(num);
                    break;
                case 'dollars': // !!!!! Modify to go with/without cents by using dollars-0 or dollars-2 !!!!!
                    return "$" + x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    break;
                default:
                    return x;
            }
        }
    };

    // Rivers formatter: adds one; mainly for index)
    rivets.formatters.plusOne = function(x) {
        return x + 1;
    };

    // Rivers formatter: short date (example: Jan '15)
    rivets.formatters.shortDate = function(x) {
        return moment(x).format("MMM 'YY");
    };

    // Apply access control rules
    var filteredSidebar = model_sidebar;
    for (var i=filteredSidebar.length-1; i>=0; i--) {

        var tabs = filteredSidebar[i].tabs;
        for (var j=tabs.length-1; j>=0; j--) {
            if (tabs[j].permissions.indexOf(sessionStorage.usertype) === -1) {
                tabs.splice(j,1);
            }
        }
        if ( filteredSidebar[i].tabs.length===0 ) {
            filteredSidebar.splice(i,1);
        }
    }

    // !!!!! Make specific data portal pages "linkable" (with hash anchors); needs to interface with access control system !!!!!

    // Bind sidebar model to accordion DIV
    rivets.bind($('#sidebarDIV'), {model_sidebar: filteredSidebar});

    // Handle sidebar clicks
    $('.dp_frag, .dp_iframe, .dp_markdown').click(function(){

        // If "DataPortal_GLOBALS.anyChanges" has been set to true, warn user before he/she navigates to another page
        var preventNavigation = false;
        if(DataPortal_GLOBALS.anyChanges) {
            var confirm = window.confirm("You have unsaved changes to data. If you leave this page, all changes will be lost. Are you sure you want to leave this page?");
            if (!confirm) {
                preventNavigation = true;
            }
        }

        // If "DataPortal_GLOBALS.anyChanges" is false or user confirms navigation, proceed
        if (!preventNavigation) {

            DataPortal_GLOBALS.anyChanges = false;

            // Get link URL; set link type
            if ( $(this).hasClass('dp_frag') ) {
                // HTML fragments
                var linkType =  "frag";
                var linkURL = '../fragments_portal/' + $(this).attr('data-link');
            } else if ( $(this).hasClass('dp_iframe') ) {
                // iFrames
                var linkType =  "frame";
                var linkURL = $(this).attr('data-link');
            } else {
                // HTML fragments
                var linkType =  "markdown";
                var linkURL = '../fragments_portal/' + $(this).attr('data-link');
            }

            // Fade out current mainContainer
            $('#whitespaceContainer').slideDown(500, function(){

                $('#mainContainer').scrollTop(0);

                // Handle fragment loads
                if (linkType === "frag") {
                    $('#dashboard_iframe').hide();
                    $('#mainContainer').show();
                    
                    $('#mainContainer').load(linkURL, function(responseText, textStatus, jqXHR){
                        if (textStatus === "error") {
                            $('#mainContainer').html("<h1>Error.</h1><h3>Please check your internet connection and try again later.</h3>");
                        }
                        setTimeout(function(){
                            $('#whitespaceContainer').slideUp(1000);
                        },500);
                    });

                // Handle iframe loads
                } else if (linkType === "frame") {
                    $('#mainContainer').hide();
                    $('#dashboard_iframe').show();
                    $('#dashboard_iframe').prop('src',linkURL);

                // Handle markdown loads
                } else if (linkType === "markdown") {
                    $('#dashboard_iframe').hide();
                    $('#mainContainer').show();
                    
                    $.ajax({
                        url: linkURL,
                        success: function(responseText){
                            // Initialize showdown.js (markdown parser)
                            var converter = new showdown.Converter({
                                tables: true,
                                tasklists: true
                            });
                            
                            // Apply rendered html to mainContainer DIV
                            var html = converter.makeHtml(responseText);
                            $('#mainContainer').html(html);
                            
                            // Add bootstrap table classes
                            $('#mainContainer table').addClass('table table-striped table-hover');
                            
                            setTimeout(function(){
                                $('#whitespaceContainer').slideUp(1000);
                            },500);
                        },
                        error: function(){
                            $('#mainContainer').html("<h1>Error.</h1><h3>Please check your internet connection and try again later.</h3>");
                            setTimeout(function(){
                                $('#whitespaceContainer').slideUp(1000);
                            },500);
                        }
                    });
                }
            });

            // Switch active sidebar element
            $('.dp_frag, .dp_iframe, .dp_markdown').each(function(){
                $(this).removeClass('dp-active');
            });
            $(this).addClass('dp-active');

        }
    });

    // !!!!! this is not robust since (1) the name of the first item may change, and (2) the first file might not be a markdown file !!!!!
    // Fade in overview pane by default
    $('.dp_markdown').first().addClass('dp-active');
    $('#id_2').click();
    $('#dashboard_iframe').hide();
    $('#dp_sidebar, #mainContainer').fadeIn(1000);
    
    // Fade out whitespaceContainer when iFrame is done loading
    document.getElementById("dashboard_iframe").onload = function() {
        location.href = "#"; location.href = "#spacer"; // to account for a scrolling bug
        $('#whitespaceContainer').slideUp(1000);
    };

    // jQueryUI Accordion on sidebar
    $("#sidebarDIV").accordion({
        header: "h3",
        heightStyle: "content",
        collapsible: true
    });

    // If "DataPortal_GLOBALS.anyChanges" has been set to true, warn user before he/she leaves page
    window.onbeforeunload = function() {
        if(DataPortal_GLOBALS.anyChanges) {
            return "You have unsaved changes to data. If you leave or reload this page, all changes will be lost.";
        }
    };

});
