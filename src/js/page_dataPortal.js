$(document).ready(function(){

    DataPortal_GLOBALS = {
        // This variable is set to true if any data changes occur (currently only used by "admin_editData.php" fragment)
        anyChanges: false
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

    // Initialize knockout.js; bind sidebar model to accordion DIV
    ko.applyBindings({groups: filteredSidebar}, $('#sidebarDIV')[0]);

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

            // Reset anyChanges flag
            DataPortal_GLOBALS.anyChanges = false;

            // Set hash fragment (element ID)
            var elemID = $(this).attr('id');
            location.hash = '#' + elemID;

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
                // Markdown
                var linkType =  "markdown";
                var linkURL = '/LastMileData/php/scripts/LMD_REST.php/markdown/' + $(this).attr('data-link');
            }
            
            // Send usage data point to database (tracks sidebar link clicks)
            var reportName = $(this).find('a span').text();
            $.ajax({
                type: "POST",
                url: "/LastMileData/php/scripts/ajaxSendQuery.php",
                data: {
                    'queryString':"INSERT INTO lastmile_dataportal.tbl_usage SET `reportName`='" + reportName + "', `linkURL`='" + linkURL + "', `username`='" + sessionStorage.username + "', `accessDate`='" + LMD_utilities.mysql_date() + "', `accessTime`='" + LMD_utilities.mysql_time() + "'",
                    'rKey':1,
                    'transaction': 1
                },
                dataType: "json"
            });

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
                    alert(linkURL);
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
                            var html = converter.makeHtml(JSON.parse(responseText).mdText);
                            $('#mainContainer').html(html);
                            
                            // Make links open in new tabs/windows
                            $('#mainContainer a').attr('target','_blank');

                            // Send usage data point to database (tracks link clicks)
                            $('#mainContainer a').click(function(){
                                var linkURL = $(this).attr('href');
                                $.ajax({
                                    type: "POST",
                                    url: "/LastMileData/php/scripts/ajaxSendQuery.php",
                                    data: {
                                        'queryString':"INSERT INTO lastmile_dataportal.tbl_usage SET `reportName`='Link click', `linkURL`='" + linkURL + "', `username`='" + sessionStorage.username + "', `accessDate`='" + LMD_utilities.mysql_date() + "', `accessTime`='" + LMD_utilities.mysql_time() + "'",
                                        'rKey':1,
                                        'transaction': 1
                                    },
                                    dataType: "json"
                                });
                            });

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

    // Fade out whitespaceContainer when iFrame is done loading
    document.getElementById("dashboard_iframe").onload = function() {
        location.href = "#"; location.href = "#spacer"; // to account for a scrolling bug
        $('#whitespaceContainer').slideUp(1000);
    };

    // If "DataPortal_GLOBALS.anyChanges" has been set to true, warn user before he/she leaves page
    window.onbeforeunload = function() {
        if(DataPortal_GLOBALS.anyChanges) {
            return "You have unsaved changes to data. If you leave or reload this page, all changes will be lost.";
        }
    };

    // jQueryUI Accordion on sidebar
    $("#sidebarDIV").accordion({
        header: "h3",
        heightStyle: "content",
        collapsible: true,
        active: false
    });

    // Routing for initial page load
    // If there's a hash fragment in the URL, redirect user to the appropriate page (if he/she has permission)
    // If there's no hash fragment OR user doesn't have appropriate permissions, redirect to the "overview" page
    if ($(window.location.hash).length > 0) {
            $(window.location.hash).parent().prev().click();
        $(window.location.hash).click();
    } else {
        $('.dp_markdown').first().addClass('dp-active');
        $('#id_1').click();
        $('#id_2').click();
    }
    $('#dashboard_iframe').hide();
    $('#dp_sidebar, #mainContainer').fadeIn(1000);

});
