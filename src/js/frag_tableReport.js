$(document).ready(function(){
    
    var options = {};
    var model = {};
    var DT;
    var blankTable = $('#tableReport').prop('outerHTML');
    
    // SQL code should be placed here
    // !!!!! Eventually, consider moving this to a database table !!!!!
    switch (sw) {
        
        case '1':
            options = [
                {
                    title: "CHA Listing: Grand Gedeh",
                    selectName: "Grand Gedeh",
                    query: "SELECT health_facility AS `Health Facility`, CONCAT(COALESCE(chss,'Unassigned'),' (',chss_id,')') AS `CHSS`, CONCAT(cha,' (',cha_id,')') AS `CHA`, community_list AS `Communities`, community_id_list AS `Community IDs` FROM lastmile_cha.view_base_cha where county='Grand Gedeh';"
                }, {
                    title: "CHA Listing: Rivercess",
                    selectName: "Rivercess",
                    query: "SELECT health_facility AS `Health Facility`, CONCAT(COALESCE(chss,'Unassigned'),' (',chss_id,')') AS `CHSS`, CONCAT(cha,' (',cha_id,')') AS `CHA`, community_list AS `Communities`, community_id_list AS `Community IDs` FROM lastmile_cha.view_base_cha where county='Rivercess';"
                }, {
                    title: "CHA Listing: Grand Bassa",
                    selectName: "Grand Bassa",
                    query: "SELECT health_facility AS `Health Facility`, CONCAT(COALESCE(chss,'Unassigned'),' (',chss_id,')') AS `CHSS`, CONCAT(cha,' (',cha_id,')') AS `CHA`, community_list AS `Communities`, community_id_list AS `Community IDs` FROM lastmile_cha.view_base_cha where county='Grand Bassa';"
                }
            ];
            break;
        
        case '2':
            options = [
                {
                    title: "CHSS Listing: Grand Gedeh",
                    selectName: "Grand Gedeh",
                    query: "SELECT health_facility AS `Health Facility`, CONCAT(COALESCE(chss,'Unassigned'),' (',chss_id,')') AS `CHSS`, COUNT(cha) AS `# of CHAs` FROM lastmile_cha.view_base_cha where county='Grand Gedeh' and chss_id IS NOT NULL GROUP BY chss_id"
                }, {
                    title: "CHSS Listing: Rivercess",
                    selectName: "Rivercess",
                    query: "SELECT health_facility AS `Health Facility`, CONCAT(COALESCE(chss,'Unassigned'),' (',chss_id,')') AS `CHSS`, COUNT(cha) AS `# of CHAs` FROM lastmile_cha.view_base_cha where county='Rivercess' and chss_id IS NOT NULL GROUP BY chss_id"
                }, {
                    title: "CHSS Listing: Grand Bassa",
                    selectName: "Grand Bassa",
                    query: "SELECT health_facility AS `Health Facility`, CONCAT(COALESCE(chss,'Unassigned'),' (',chss_id,')') AS `CHSS`, COUNT(cha) AS `# of CHAs` FROM lastmile_cha.view_base_cha where county='Grand Bassa' and chss_id IS NOT NULL GROUP BY chss_id"
                }
            ];
            break;
        
        case '3':
            options = [
                {
                    title: "Title: Counties",
                    selectName: "Counties",
                    query: "SELECT county_id AS `County ID`, county AS `County`, meta_insert_date_time AS `Timestamp` FROM lastmile_cha.county;"
                }, {
                    title: "Title: Districts",
                    selectName: "Districts",
                    query: "SELECT district_id AS `District ID`, district AS `District`, meta_insert_date_time AS `Timestamp` FROM lastmile_cha.district;"
                }, {
                    title: "Title: Communities",
                    selectName: "Communities",
                    query: "SELECT community_id AS `Community ID`, community AS `Community`, meta_insert_date_time AS `Timestamp` FROM lastmile_cha.community;"
                }
            ];
            break;
        
        case '4':
            options = {
            };
            break;
        
    }
    
    // Initial AJAX call
    updateTable(0);

    // Compensate for a formatting bug
    $(window).on('DP_up',function(){
        DT.draw();
    });
    
    // Main function that redraws and updates entire table when the select menu changes
    function updateTable(selectIndex) {
        
        // Show AJAX loading GIF; reset table HTML
        $('#ajaxLoader').slideDown(500);
        $('#tableReportContainer').html(blankTable);

        // Send AJAX request #1 (indicator/instance metadata)
        $.ajax({
            type: "POST",
            url: "/LastMileData/php/scripts/LMD_REST.php/query",
            data: {
                'query': options[selectIndex].query
            },
            dataType: "json",
            success: function (data) {
                
                    // Create and bind model
                    model = {
                        fields: ko.observableArray(),
                        data: ko.observableArray(),
                        options: options,
                        actions: {
                            selectChange: function(data,event) {
                                
                                // Get index of selected option
                                var selection = $('#tableSelector').val();
                                var index = 0;
                                for (var key in options) {
                                    if (options[key].selectName === selection) {
                                        break;
                                    } else {
                                        index++;
                                    }
                                }
                                
                                // Update table
                                updateTable(index);
                            }
                        }
                    };
                    ko.applyBindings(model, $('#tableReport')[0]);
                            
                    // Update model fields/data
                    model.fields.removeAll();
                    for (var key in data.fields) {
                        model.fields.push(data.fields[key]);
                    }
                    model.data.removeAll();
                    for (var key in data.data) {
                        model.data.push(data.data[key]);
                    }

                    // Apply DataTable function
                    DT = $('.table').DataTable({
                        scrollY: '50vh',
                        dom: '<fltip>',
                        lengthMenu: [[10, 25, 100, -1], [10, 25, 100, "all"]]
//                        scrollX: '100%',
//                        scrollCollapse: true,
                    });

                    // Set title and select value
                    $('#tableTitle').text(options[selectIndex].title);
                    $('#tableSelector').val(options[selectIndex].selectName);
                    
                    // Hide AJAX loading GIF
                    $('#ajaxLoader').slideUp(500);
                
            },
            error: function() {
                console.log('error :/')
            }
        });

    }

});
