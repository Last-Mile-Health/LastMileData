$(document).ready(function(){
    
    DataPortal_GLOBALS = {
        // This variable is set to true if any data changes occur (currently only used by "admin_editData.php" fragment)
        // !!!!! Expand to other pages !!!!!
        anyChanges: false
    };

    // Apply access control rules
    var filteredSidebar = model_sidebar;
    for (var i=filteredSidebar.length-1; i>=0; i--) {

        var tabs = filteredSidebar[i].tabs;
        
        // Loop BACKWARDS through tabs ot enable splicing
        for (var j=tabs.length-1; j>=0; j--) {
            
            var spliceTab = true;
            
            // Test all userGroups to see if there are no matches
            for (var key in sessionStorage.userGroups.split(',')) {
                var userGroups = sessionStorage.userGroups.split(',')[key];
                if (tabs[j].permissions.indexOf(userGroups) !== -1) {
                    spliceTab = false;
                }
            }
            
            // If there are no group matches, splice out the tab
            if (spliceTab) {
                tabs.splice(j,1);
            }
        }
        
        // If there are no tabs within a tab-group, don't display the tab-group
        if ( filteredSidebar[i].tabs.length===0 ) {
            filteredSidebar.splice(i,1);
        }
    }

    // Initialize knockout.js; bind sidebar model to accordion DIV
    ko.applyBindings({groups: filteredSidebar}, $('#sidebarDIV')[0]);

    // Handle sidebar clicks
    $('.dp_frag, .dp_iframe, .dp_markdown').click(function(ev){

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
            history.pushState(null, null, '#' + elemID); // "location.hash" not used because it causes a bug that moves the main DIV up 50px when the hash is changed

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
            // !!!!! Modularize this code !!!!!
            var reportName = $(this).find('a span').text();
            $.ajax({
                type: "POST",
                url: "/LastMileData/php/scripts/ajaxSendQuery.php",
                data: { 'queryString':"INSERT INTO lastmile_dataportal.tbl_usage SET `reportName`='" + LMD_utilities.addSlashes(reportName) + "', `linkURL`='" + LMD_utilities.addSlashes(linkURL) + "', `username`='" + sessionStorage.username + "', `accessDate`='" + LMD_utilities.mysql_date() + "', `accessTime`='" + LMD_utilities.mysql_time() + "';" },
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
                            // Display error message
                            $('#mainContainer').html("<h1>Error.</h1><h3>Please check your internet connection and try again later.</h3>");
                        } else {
                            // Fire "DP_loaded" event
                            $(window).trigger('DP_loaded');
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
                            
                            // Fire "DP_loaded" event
                            $(window).trigger('DP_loaded');
                            
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
                                    data: { 'queryString':"INSERT INTO lastmile_dataportal.tbl_usage SET `reportName`='Link click', `linkURL`='" + linkURL + "', `username`='" + sessionStorage.username + "', `accessDate`='" + LMD_utilities.mysql_date() + "', `accessTime`='" + LMD_utilities.mysql_time() + "'" },
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
            
            // If user is on the overview page, start the Shepherd tour; otherwise, destroy the tour
            if ( $(this).attr('data-link') === 'Overview' ) {
                LMD_shepherd.start();
            } else if (ev.hasOwnProperty('originalEvent')) {
                LMD_shepherd.destroy();
            }
            
        }
    });

    // Fade out whitespaceContainer when iFrame is done loading
    document.getElementById("dashboard_iframe").onload = function() {
        var myHash = location.hash;
        location.href = "#"; location.href = "#spacer"; location.href = myHash; // to account for a scrolling bug
        $('#whitespaceContainer').slideUp(1000);
        $(window).trigger('DP_loaded');
    };

    // If "DataPortal_GLOBALS.anyChanges" has been set to true, warn user before he/she leaves page
    // Note: with Chrome 51+, a custom message cannot be passed to the user (see here: https://www.chromestatus.com/feature/5349061406228480)
    window.onbeforeunload = function() {
        if(DataPortal_GLOBALS.anyChanges) {
            return 1;
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
