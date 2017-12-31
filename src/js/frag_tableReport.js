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
                    query: "SELECT health_facility AS `Health Facility`, CONCAT(COALESCE(chss,'Unassigned'),' (',chss_id,')') AS `CHSS`, CONCAT(cha,' (',cha_id,')') AS `CHA`, community_list AS `Communities`, community_id_list AS `Community IDs` FROM lastmile_cha.view_base_cha where county='Grand Gedeh';",
                    defaultOrder: [[0, "asc"],[1, "asc"],[2, "asc"]]
                }, {
                    title: "CHA Listing: Rivercess",
                    selectName: "Rivercess",
                    query: "SELECT health_facility AS `Health Facility`, CONCAT(COALESCE(chss,'Unassigned'),' (',chss_id,')') AS `CHSS`, CONCAT(cha,' (',cha_id,')') AS `CHA`, community_list AS `Communities`, community_id_list AS `Community IDs` FROM lastmile_cha.view_base_cha where county='Rivercess';",
                    defaultOrder: [[0, "asc"],[1, "asc"],[2, "asc"]]
                }, {
                    title: "CHA Listing: Grand Bassa",
                    selectName: "Grand Bassa",
                    query: "SELECT health_facility AS `Health Facility`, CONCAT(COALESCE(chss,'Unassigned'),' (',chss_id,')') AS `CHSS`, CONCAT(cha,' (',cha_id,')') AS `CHA`, community_list AS `Communities`, community_id_list AS `Community IDs` FROM lastmile_cha.view_base_cha where county='Grand Bassa';",
                    defaultOrder: [[0, "asc"],[1, "asc"],[2, "asc"]]
                }
            ];
            break;

        case '2':
            options = [
                {
                    title: "CHSS Listing: Grand Gedeh",
                    selectName: "Grand Gedeh",
                    query: "SELECT health_facility AS `Health Facility`, CONCAT(COALESCE(chss,'Unassigned'),' (',chss_id,')') AS `CHSS`, COUNT(cha) AS `# of CHAs` FROM lastmile_cha.view_base_cha where county='Grand Gedeh' and chss_id IS NOT NULL GROUP BY chss_id",
                    defaultOrder: [[0, "asc"],[1, "asc"]]
                }, {
                    title: "CHSS Listing: Rivercess",
                    selectName: "Rivercess",
                    query: "SELECT health_facility AS `Health Facility`, CONCAT(COALESCE(chss,'Unassigned'),' (',chss_id,')') AS `CHSS`, COUNT(cha) AS `# of CHAs` FROM lastmile_cha.view_base_cha where county='Rivercess' and chss_id IS NOT NULL GROUP BY chss_id",
                    defaultOrder: [[0, "asc"],[1, "asc"]]
                }, {
                    title: "CHSS Listing: Grand Bassa",
                    selectName: "Grand Bassa",
                    query: "SELECT health_facility AS `Health Facility`, CONCAT(COALESCE(chss,'Unassigned'),' (',chss_id,')') AS `CHSS`, COUNT(cha) AS `# of CHAs` FROM lastmile_cha.view_base_cha where county='Grand Bassa' and chss_id IS NOT NULL GROUP BY chss_id",
                    defaultOrder: [[0, "asc"],[1, "asc"]]
               }
            ];
            break;

        case '3':
            options = [
                {
                    title: "CHSS Tool Completion: monthly",
                    headerNote: "Note: Only active CHSSs are displayed.",
                    selectName: "Monthly",
                    query: "SELECT chss AS `CHSS`, chss_id AS `CHSS ID#`, `year` AS `Year`, `month` AS `Month`, num_supervision_visit_logs AS `# supervision visit logs`, "
                            + "num_vaccine_trackers AS `# vaccine trackers`, num_chss_msrs AS `# CHSS MSRs`, num_cha_msrs AS `# CHA MSRs` FROM lastmile_report.view_base_chss_tool_completion;",
                    defaultOrder: [[2, "desc"],[3, "desc"],[0, "asc"]]
                }, {
                    title: "CHSS Tool Completion: last 3 months",
                    headerNote: "Note: Only active CHSSs are displayed.",
                    selectName: "Last 3 months",
                    query: "SELECT chss AS `CHSS`, chss_id AS `CHSS ID#`, SUM(num_supervision_visit_logs) AS `# supervision visit logs`, "
                            + "SUM(num_vaccine_trackers) AS `# vaccine trackers`, SUM(num_chss_msrs) AS `# CHSS MSRs`, SUM(num_cha_msrs) AS `# CHA MSRs` "
                            + "FROM lastmile_report.view_base_chss_tool_completion WHERE (month(now())+(year(now())*12))-(`month`+(`year`*12))<=3 GROUP BY `chss_id`;",
                    defaultOrder: [[0, "asc"]]
                }, {
                    title: "CHSS Tool Completion: last 6 months",
                    headerNote: "Note: Only active CHSSs are displayed.",
                    selectName: "Last 6 months",
                    query: "SELECT chss AS `CHSS`, chss_id AS `CHSS ID#`, SUM(num_supervision_visit_logs) AS `# supervision visit logs`, "
                            + "SUM(num_vaccine_trackers) AS `# vaccine trackers`, SUM(num_chss_msrs) AS `# CHSS MSRs`, SUM(num_cha_msrs) AS `# CHA MSRs` "
                            + "FROM lastmile_report.view_base_chss_tool_completion WHERE (month(now())+(year(now())*12))-(`month`+(`year`*12))<=6 GROUP BY `chss_id`;",
                    defaultOrder: [[0, "asc"]]
                }
            ];
            break;

        case '4':
            options = [
                {
                    title: "Death Listing",
                    query: "SELECT * FROM lastmile_report.view_death_listing;",
                    defaultOrder: [[0, "desc"],[1, "desc"],[2, "asc"],[3, "asc"]]
                }
            ];
            break;

        case '5':
            options = [
                {
                    title: "Data Entry Details",
                    query: "SELECT * FROM lastmile_report.view_data_entry;",
                    defaultOrder: [[2, "desc"],[3, "desc"],[1, "asc"],[0, "asc"]]
                }
            ];
            break;

        case '6':
            options = [
                {
                    title: "ODK Data Upload Details",
                    query: "SELECT * FROM lastmile_report.view_odk_uploads;",
                    defaultOrder: [[1, "desc"],[4, "asc"],[2, "desc"]]
                }
            ];
            break;

        case '7':
            options = [
                {
                    title: "Indicator Reference",
                    query: "SELECT ind_id AS `ID#`, ind_category AS `Category`, ind_name AS `Indicator name`, ind_definition AS `Definition`, ind_source AS `Data source` "
                            + "FROM lastmile_dataportal.tbl_indicators where archived=0 AND ind_name NOT LIKE 'Numerator%' AND ind_name NOT LIKE 'Denominator%';",
                    defaultOrder: [[0, "asc"],[1, "asc"]]
                }
            ];
            break;

        case '8':
            options = [
                {
                    title: "",
                    query: "",
                    defaultOrder: []
                }
            ];
            break;

    }
    
    // Initial AJAX call
    updateTable(0);

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
                        scrollY: '42vh',
                        dom: '<fltip>',
                        order: options[selectIndex].defaultOrder,
                        lengthMenu: [[10, 25, 100, -1], [10, 25, 100, "all"]]
//                        scrollX: '100%',
//                        scrollCollapse: true,
                    });

                    // Set title, headerNote, and select value
                    $('#tableTitle').text(options[selectIndex].title);
                    $('#headerNote').text(options[selectIndex].headerNote);
                    $('#tableSelector').val(options[selectIndex].selectName);

                    // Hide AJAX loading GIF; redraw table
                    $('#ajaxLoader').slideUp(500);
                    setTimeout(function(){
                        DT.draw();
                    },2000);

            },
            error: function() {
                console.log('error :/')
            }
        });

    }

});
