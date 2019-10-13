$(document).ready(function(){
    
    var options = {};
    var model = {};
    var DT;
    var blankTable = $('#tableReport').prop('outerHTML');
    var reportMonth = moment().subtract(1 + ( moment().format('D') < 15 ? 1 : 0 ),'months');
    
    // SQL code should be placed here
    // !!!!! Consider moving this to a database table and passing in the view name as a GET parameter !!!!!
    switch (sw) {

        case '1':
            options = [
                {
                    title: "CHA Listing: Grand Gedeh",
                    selectName: "Grand Gedeh",
                    query: "SELECT health_facility AS `Health Facility`, CONCAT(COALESCE(chss,'Unassigned'),' (',chss_position_id,')') AS `CHSS`, cha AS `CHA`, position_id AS `CHA ID#`, community_list AS `Communities`, community_id_list AS `Community IDs` FROM lastmile_cha.view_base_cha_basic_info where county='Grand Gedeh';",
                    defaultOrder: [[0, "asc"],[1, "asc"],[2, "asc"]]
                }, {
                    title: "CHA Listing: Rivercess",
                    selectName: "Rivercess",
                    query: "SELECT health_facility AS `Health Facility`, CONCAT(COALESCE(chss,'Unassigned'),' (',chss_position_id,')') AS `CHSS`, cha AS `CHA`, position_id AS `CHA ID#`, community_list AS `Communities`, community_id_list AS `Community IDs` FROM lastmile_cha.view_base_cha_basic_info where county='Rivercess';",
                    defaultOrder: [[0, "asc"],[1, "asc"],[2, "asc"]]
                }, {
                    title: "CHA Listing: Grand Bassa",
                    selectName: "Grand Bassa",
                    query: "SELECT health_facility AS `Health Facility`, CONCAT(COALESCE(chss,'Unassigned'),' (',chss_position_id,')') AS `CHSS`, cha AS `CHA`, position_id AS `CHA ID#`, community_list AS `Communities`, community_id_list AS `Community IDs` FROM lastmile_cha.view_base_cha_basic_info where county='Grand Bassa';",
                    defaultOrder: [[0, "asc"],[1, "asc"],[2, "asc"]]
                }
            ];
            break;

        case '2':
            options = [
                {
                    title: "CHSS Listing: Grand Gedeh",
                    selectName: "Grand Gedeh",
                    query: "SELECT health_facility AS `Health Facility`, COALESCE(chss,'Unassigned') AS `CHSS`, chss_position_id AS `CHSS ID#`, COUNT(cha) AS `# of CHAs` FROM lastmile_cha.view_base_cha_basic_info where county='Grand Gedeh' and position_id IS NOT NULL GROUP BY chss_position_id",
                    defaultOrder: [[0, "asc"],[1, "asc"]]
                }, {
                    title: "CHSS Listing: Rivercess",
                    selectName: "Rivercess",
                    query: "SELECT health_facility AS `Health Facility`, COALESCE(chss,'Unassigned') AS `CHSS`, chss_position_id AS `CHSS ID#`, COUNT(cha) AS `# of CHAs` FROM lastmile_cha.view_base_cha_basic_info where county='Rivercess' and position_id IS NOT NULL GROUP BY chss_position_id",
                    defaultOrder: [[0, "asc"],[1, "asc"]]
                }, {
                    title: "CHSS Listing: Grand Bassa",
                    selectName: "Grand Bassa",
                    query: "SELECT health_facility AS `Health Facility`, COALESCE(chss,'Unassigned') AS `CHSS`, chss_position_id AS `CHSS ID#`, COUNT(cha) AS `# of CHAs` FROM lastmile_cha.view_base_cha_basic_info where county='Grand Bassa' and position_id IS NOT NULL GROUP BY chss_position_id",
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
                    query: "SELECT county AS `County`, chss AS `CHSS`, chss_id AS `CHSS ID#`, `year` AS `Year`, `month` AS `Month`, num_supervision_visit_logs AS `# supervision visit logs`, "
                            + "num_chss_msrs AS `# CHSS MSRs`, num_cha_msrs AS `# CHA MSRs`, num_restock_forms AS `# restock forms`, num_cha AS `# CHAs` FROM lastmile_report.view_base_chss_tool_completion;",
                    defaultOrder: [[3, "desc"],[4, "desc"],[0, "asc"],[1, "asc"]]
                }, {
                    title: "CHSS Tool Completion: last 3 months",
                    headerNote: "Note: Only active CHSSs are displayed.",
                    selectName: "Last 3 months",
                    query: "SELECT county AS `County`, chss AS `CHSS`, chss_id AS `CHSS ID#`, SUM(num_supervision_visit_logs) AS `# supervision visit logs`, "
                            + "SUM(num_chss_msrs) AS `# CHSS MSRs`, SUM(num_cha_msrs) AS `# CHA MSRs`, SUM(num_restock_forms) AS `# restock forms`, num_cha AS `# CHAs` "
                            + "FROM lastmile_report.view_base_chss_tool_completion WHERE (month(now())+(year(now())*12))-(`month`+(`year`*12))<=3 GROUP BY `chss_id`;",
                    defaultOrder: [[0, "asc"],[1, "asc"]]
                }, {
                    title: "CHSS Tool Completion: last 6 months",
                    headerNote: "Note: Only active CHSSs are displayed.",
                    selectName: "Last 6 months",
                    query: "SELECT county AS `County`, chss AS `CHSS`, chss_id AS `CHSS ID#`, SUM(num_supervision_visit_logs) AS `# supervision visit logs`, "
                            + "SUM(num_chss_msrs) AS `# CHSS MSRs`, SUM(num_cha_msrs) AS `# CHA MSRs`, SUM(num_restock_forms) AS `# restock forms`, num_cha AS `# CHAs` "
                            + "FROM lastmile_report.view_base_chss_tool_completion WHERE (month(now())+(year(now())*12))-(`month`+(`year`*12))<=6 GROUP BY `chss_id`;",
                    defaultOrder: [[0, "asc"],[1, "asc"]]
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
                    title: "Health District report: all counties",
                    selectName: "All counties",
                    query: "SELECT health_district AS `Health District`, ROUND(num_routine_visits/num_catchment_households,1) AS `Routine visits per HH`, " +
                            "ROUND(1000*(num_tx_malaria/num_catchment_people_iccm),1) AS `Malaria cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_diarrhea/num_catchment_people_iccm),1) AS `Diarrhea cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_ari/num_catchment_people_iccm),1) AS `ARI cases Tx per 1,000 people`, " +
                            "ROUND(1000*((num_muac_red+num_muac_yellow+num_muac_green)/num_catchment_people),1) AS `MUAC screens per 1,000 people`, " +
                            "ROUND(1000*(num_pregnant_woman_visits/num_catchment_people),1) AS `Pregnant woman visits per 1,000 people`, " +
                            "CONCAT(ROUND(100*(num_tx_malaria_under24/(num_tx_malaria_under24+num_tx_malaria_over24)),1),'%') AS `% Malaria cases treated within 24 hrs` " +
                            "FROM lastmile_report.mart_view_base_msr_healthdistrict WHERE month_reported=" + reportMonth.format('M') + " AND year_reported=" + reportMonth.format('YYYY') + ";",
                    defaultOrder: [[0, "asc"]]
                }, {
                    title: "Health District report: Grand Gedeh",
                    selectName: "Grand Gedeh",
                    query: "SELECT health_district AS `Health District`, ROUND(num_routine_visits/num_catchment_households,1) AS `Routine visits per HH`, " +
                            "ROUND(1000*(num_tx_malaria/num_catchment_people_iccm),1) AS `Malaria cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_diarrhea/num_catchment_people_iccm),1) AS `Diarrhea cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_ari/num_catchment_people_iccm),1) AS `ARI cases Tx per 1,000 people`, " +
                            "ROUND(1000*((num_muac_red+num_muac_yellow+num_muac_green)/num_catchment_people),1) AS `MUAC screens per 1,000 people`, " +
                            "ROUND(1000*(num_pregnant_woman_visits/num_catchment_people),1) AS `Pregnant woman visits per 1,000 people`, " +
                            "CONCAT(ROUND(100*(num_tx_malaria_under24/(num_tx_malaria_under24+num_tx_malaria_over24)),1),'%') AS `% Malaria cases treated within 24 hrs` " +
                            "FROM lastmile_report.mart_view_base_msr_healthdistrict WHERE county='Grand Gedeh' AND month_reported=" + reportMonth.format('M') + " AND year_reported=" + reportMonth.format('YYYY') + ";",
                    defaultOrder: [[0, "asc"]]
                }, {
                    title: "Health District report: Rivercess",
                    selectName: "Rivercess",
                    query: "SELECT health_district AS `Health District`, ROUND(num_routine_visits/num_catchment_households,1) AS `Routine visits per HH`, " +
                            "ROUND(1000*(num_tx_malaria/num_catchment_people_iccm),1) AS `Malaria cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_diarrhea/num_catchment_people_iccm),1) AS `Diarrhea cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_ari/num_catchment_people_iccm),1) AS `ARI cases Tx per 1,000 people`, " +
                            "ROUND(1000*((num_muac_red+num_muac_yellow+num_muac_green)/num_catchment_people),1) AS `MUAC screens per 1,000 people`, " +
                            "ROUND(1000*(num_pregnant_woman_visits/num_catchment_people),1) AS `Pregnant woman visits per 1,000 people`, " +
                            "CONCAT(ROUND(100*(num_tx_malaria_under24/(num_tx_malaria_under24+num_tx_malaria_over24)),1),'%') AS `% Malaria cases treated within 24 hrs` " +
                            "FROM lastmile_report.mart_view_base_msr_healthdistrict WHERE county='Rivercess' AND month_reported=" + reportMonth.format('M') + " AND year_reported=" + reportMonth.format('YYYY') + ";",
                    defaultOrder: [[0, "asc"]]
                }, {
                    title: "Health District report: Grand Bassa",
                    selectName: "Grand Bassa",
                    query: "SELECT health_district AS `Health District`, ROUND(num_routine_visits/num_catchment_households,1) AS `Routine visits per HH`, " +
                            "ROUND(1000*(num_tx_malaria/num_catchment_people_iccm),1) AS `Malaria cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_diarrhea/num_catchment_people_iccm),1) AS `Diarrhea cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_ari/num_catchment_people_iccm),1) AS `ARI cases Tx per 1,000 people`, " +
                            "ROUND(1000*((num_muac_red+num_muac_yellow+num_muac_green)/num_catchment_people),1) AS `MUAC screens per 1,000 people`, " +
                            "ROUND(1000*(num_pregnant_woman_visits/num_catchment_people),1) AS `Pregnant woman visits per 1,000 people`, " +
                            "CONCAT(ROUND(100*(num_tx_malaria_under24/(num_tx_malaria_under24+num_tx_malaria_over24)),1),'%') AS `% Malaria cases treated within 24 hrs` " +
                            "FROM lastmile_report.mart_view_base_msr_healthdistrict WHERE county='Grand Bassa' AND month_reported=" + reportMonth.format('M') + " AND year_reported=" + reportMonth.format('YYYY') + ";",
                    defaultOrder: [[0, "asc"]]
                }
            ];
            break;

        case '9':
            options = [
                {
                    title: "Health Facility report: all counties",
                    selectName: "All counties",
                    query: "SELECT health_facility AS `Health Facility`, ROUND(num_routine_visits/num_catchment_households,1) AS `Routine visits per HH`, " +
                            "ROUND(1000*(num_tx_malaria/num_catchment_people_iccm),1) AS `Malaria cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_diarrhea/num_catchment_people_iccm),1) AS `Diarrhea cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_ari/num_catchment_people_iccm),1) AS `ARI cases Tx per 1,000 people`, " +
                            "ROUND(1000*((num_muac_red+num_muac_yellow+num_muac_green)/num_catchment_people),1) AS `MUAC screens per 1,000 people`, " +
                            "ROUND(1000*(num_pregnant_woman_visits/num_catchment_people),1) AS `Pregnant woman visits per 1,000 people`, " +
                            "CONCAT(ROUND(100*(num_tx_malaria_under24/(num_tx_malaria_under24+num_tx_malaria_over24)),1),'%') AS `% Malaria cases treated within 24 hrs` " +
                            "FROM lastmile_report.mart_view_base_msr_facility WHERE month_reported=" + reportMonth.format('M') + " AND year_reported=" + reportMonth.format('YYYY') + ";",
                    defaultOrder: [[0, "asc"]]
                }, {
                    title: "Health Facility report: Grand Gedeh",
                    selectName: "Grand Gedeh",
                    query: "SELECT health_facility AS `Health Facility`, ROUND(num_routine_visits/num_catchment_households,1) AS `Routine visits per HH`, " +
                            "ROUND(1000*(num_tx_malaria/num_catchment_people_iccm),1) AS `Malaria cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_diarrhea/num_catchment_people_iccm),1) AS `Diarrhea cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_ari/num_catchment_people_iccm),1) AS `ARI cases Tx per 1,000 people`, " +
                            "ROUND(1000*((num_muac_red+num_muac_yellow+num_muac_green)/num_catchment_people),1) AS `MUAC screens per 1,000 people`, " +
                            "ROUND(1000*(num_pregnant_woman_visits/num_catchment_people),1) AS `Pregnant woman visits per 1,000 people`, " +
                            "CONCAT(ROUND(100*(num_tx_malaria_under24/(num_tx_malaria_under24+num_tx_malaria_over24)),1),'%') AS `% Malaria cases treated within 24 hrs` " +
                            "FROM lastmile_report.mart_view_base_msr_facility WHERE county='Grand Gedeh' AND month_reported=" + reportMonth.format('M') + " AND year_reported=" + reportMonth.format('YYYY') + ";",
                    defaultOrder: [[0, "asc"]]
                }, {
                    title: "Health Facility report: Rivercess",
                    selectName: "Rivercess",
                    query: "SELECT health_facility AS `Health Facility`, ROUND(num_routine_visits/num_catchment_households,1) AS `Routine visits per HH`, " +
                            "ROUND(1000*(num_tx_malaria/num_catchment_people_iccm),1) AS `Malaria cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_diarrhea/num_catchment_people_iccm),1) AS `Diarrhea cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_ari/num_catchment_people_iccm),1) AS `ARI cases Tx per 1,000 people`, " +
                            "ROUND(1000*((num_muac_red+num_muac_yellow+num_muac_green)/num_catchment_people),1) AS `MUAC screens per 1,000 people`, " +
                            "ROUND(1000*(num_pregnant_woman_visits/num_catchment_people),1) AS `Pregnant woman visits per 1,000 people`, " +
                            "CONCAT(ROUND(100*(num_tx_malaria_under24/(num_tx_malaria_under24+num_tx_malaria_over24)),1),'%') AS `% Malaria cases treated within 24 hrs` " +
                            "FROM lastmile_report.mart_view_base_msr_facility WHERE county='Rivercess' AND month_reported=" + reportMonth.format('M') + " AND year_reported=" + reportMonth.format('YYYY') + ";",
                    defaultOrder: [[0, "asc"]]
                }, {
                    title: "Health Facility report: Grand Bassa",
                    selectName: "Grand Bassa",
                    query: "SELECT health_facility AS `Health Facility`, ROUND(num_routine_visits/num_catchment_households,1) AS `Routine visits per HH`, " +
                            "ROUND(1000*(num_tx_malaria/num_catchment_people_iccm),1) AS `Malaria cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_diarrhea/num_catchment_people_iccm),1) AS `Diarrhea cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_ari/num_catchment_people_iccm),1) AS `ARI cases Tx per 1,000 people`, " +
                            "ROUND(1000*((num_muac_red+num_muac_yellow+num_muac_green)/num_catchment_people),1) AS `MUAC screens per 1,000 people`, " +
                            "ROUND(1000*(num_pregnant_woman_visits/num_catchment_people),1) AS `Pregnant woman visits per 1,000 people`, " +
                            "CONCAT(ROUND(100*(num_tx_malaria_under24/(num_tx_malaria_under24+num_tx_malaria_over24)),1),'%') AS `% Malaria cases treated within 24 hrs` " +
                            "FROM lastmile_report.mart_view_base_msr_facility WHERE county='Grand Bassa' AND month_reported=" + reportMonth.format('M') + " AND year_reported=" + reportMonth.format('YYYY') + ";",
                    defaultOrder: [[0, "asc"]]
                }
            ];
            break;

        case '10':
            options = [
                {
                    title: "CHSS report: all counties",
                    selectName: "All counties",
                    query: "SELECT chss_name AS `CHSS`, chss_id AS `CHSS ID#`, ROUND(num_routine_visits/num_catchment_households,1) AS `Routine visits per HH`, " +
                            "ROUND(1000*(num_tx_malaria/num_catchment_people_iccm),1) AS `Malaria cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_diarrhea/num_catchment_people_iccm),1) AS `Diarrhea cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_ari/num_catchment_people_iccm),1) AS `ARI cases Tx per 1,000 people`, " +
                            "ROUND(1000*((num_muac_red+num_muac_yellow+num_muac_green)/num_catchment_people),1) AS `MUAC screens per 1,000 people`, " +
                            "ROUND(1000*(num_pregnant_woman_visits/num_catchment_people),1) AS `Pregnant woman visits per 1,000 people`, " +
                            "CONCAT(ROUND(100*(num_tx_malaria_under24/(num_tx_malaria_under24+num_tx_malaria_over24)),1),'%') AS `% Malaria cases treated within 24 hrs` " +
                            "FROM lastmile_report.mart_view_base_msr_chss WHERE month_reported=" + reportMonth.format('M') + " AND year_reported=" + reportMonth.format('YYYY') + " AND chss_id IS NOT NULL;",
                    defaultOrder: [[0, "asc"]]
                }, {
                    title: "CHSS report: Grand Gedeh",
                    selectName: "Grand Gedeh",
                    query: "SELECT chss_name AS `CHSS`, chss_id AS `CHSS ID#`, ROUND(num_routine_visits/num_catchment_households,1) AS `Routine visits per HH`, " +
                            "ROUND(1000*(num_tx_malaria/num_catchment_people_iccm),1) AS `Malaria cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_diarrhea/num_catchment_people_iccm),1) AS `Diarrhea cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_ari/num_catchment_people_iccm),1) AS `ARI cases Tx per 1,000 people`, " +
                            "ROUND(1000*((num_muac_red+num_muac_yellow+num_muac_green)/num_catchment_people),1) AS `MUAC screens per 1,000 people`, " +
                            "ROUND(1000*(num_pregnant_woman_visits/num_catchment_people),1) AS `Pregnant woman visits per 1,000 people`, " +
                            "CONCAT(ROUND(100*(num_tx_malaria_under24/(num_tx_malaria_under24+num_tx_malaria_over24)),1),'%') AS `% Malaria cases treated within 24 hrs` " +
                            "FROM lastmile_report.mart_view_base_msr_chss WHERE county='Grand Gedeh' AND month_reported=" + reportMonth.format('M') + " AND year_reported=" + reportMonth.format('YYYY') + " AND chss_id IS NOT NULL;",
                    defaultOrder: [[0, "asc"]]
                }, {
                    title: "CHSS report: Rivercess",
                    selectName: "Rivercess",
                    query: "SELECT chss_name AS `CHSS`, chss_id AS `CHSS ID#`, ROUND(num_routine_visits/num_catchment_households,1) AS `Routine visits per HH`, " +
                            "ROUND(1000*(num_tx_malaria/num_catchment_people_iccm),1) AS `Malaria cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_diarrhea/num_catchment_people_iccm),1) AS `Diarrhea cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_ari/num_catchment_people_iccm),1) AS `ARI cases Tx per 1,000 people`, " +
                            "ROUND(1000*((num_muac_red+num_muac_yellow+num_muac_green)/num_catchment_people),1) AS `MUAC screens per 1,000 people`, " +
                            "ROUND(1000*(num_pregnant_woman_visits/num_catchment_people),1) AS `Pregnant woman visits per 1,000 people`, " +
                            "CONCAT(ROUND(100*(num_tx_malaria_under24/(num_tx_malaria_under24+num_tx_malaria_over24)),1),'%') AS `% Malaria cases treated within 24 hrs` " +
                            "FROM lastmile_report.mart_view_base_msr_chss WHERE county='Rivercess' AND month_reported=" + reportMonth.format('M') + " AND year_reported=" + reportMonth.format('YYYY') + " AND chss_id IS NOT NULL;",
                    defaultOrder: [[0, "asc"]]
                }, {
                    title: "CHSS report: Grand Bassa",
                    selectName: "Grand Bassa",
                    query: "SELECT chss_name AS `CHSS`, chss_id AS `CHSS ID#`, ROUND(num_routine_visits/num_catchment_households,1) AS `Routine visits per HH`, " +
                            "ROUND(1000*(num_tx_malaria/num_catchment_people_iccm),1) AS `Malaria cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_diarrhea/num_catchment_people_iccm),1) AS `Diarrhea cases Tx per 1,000 people`, " +
                            "ROUND(1000*(num_tx_ari/num_catchment_people_iccm),1) AS `ARI cases Tx per 1,000 people`, " +
                            "ROUND(1000*((num_muac_red+num_muac_yellow+num_muac_green)/num_catchment_people),1) AS `MUAC screens per 1,000 people`, " +
                            "ROUND(1000*(num_pregnant_woman_visits/num_catchment_people),1) AS `Pregnant woman visits per 1,000 people`, " +
                            "CONCAT(ROUND(100*(num_tx_malaria_under24/(num_tx_malaria_under24+num_tx_malaria_over24)),1),'%') AS `% Malaria cases treated within 24 hrs` " +
                            "FROM lastmile_report.mart_view_base_msr_chss WHERE county='Grand Bassa' AND month_reported=" + reportMonth.format('M') + " AND year_reported=" + reportMonth.format('YYYY') + " AND chss_id IS NOT NULL;",
                    defaultOrder: [[0, "asc"]]
                }
            ];
            break;

        case '11':
            options = [
                {
                    title: "# logins, by user (last 30 days)",
                    selectName: "# logins, by user (last 30 days)",
                    query: "SELECT username AS `User`, COUNT(1) AS `# logins` FROM lastmile_dataportal.tbl_utility_logins WHERE DATEDIFF(NOW(),login_time)<=30 GROUP BY username;",
                    defaultOrder: [[1, "desc"]]
                }, {
                    title: "# logins, by user (last 90 days)",
                    selectName: "# logins, by user (last 90 days)",
                    query: "SELECT username AS `User`, COUNT(1) AS `# logins` FROM lastmile_dataportal.tbl_utility_logins WHERE DATEDIFF(NOW(),login_time)<=90 GROUP BY username;",
                    defaultOrder: [[1, "desc"]]
                }, {
                    title: "# page clicks, by user (last 30 days)",
                    selectName: "# page clicks, by user (last 30 days)",
                    query: "SELECT username AS `User`, COUNT(1) AS `# page clicks` FROM lastmile_dataportal.tbl_usage WHERE DATEDIFF(NOW(),access_date)<=30 GROUP BY username;",
                    defaultOrder: [[1, "desc"]]
                }, {
                    title: "# page clicks, by user (last 90 days)",
                    selectName: "# page clicks, by user (last 90 days)",
                    query: "SELECT username AS `User`, COUNT(1) AS `# page clicks` FROM lastmile_dataportal.tbl_usage WHERE DATEDIFF(NOW(),access_date)<=90 GROUP BY username;",
                    defaultOrder: [[1, "desc"]]
                }, {
                    title: "Most accessed pages (last 30 days)",
                    selectName: "Most accessed pages (last 30 days)",
                    query: "SELECT object AS `Page`, COUNT(1) AS `# page clicks` FROM lastmile_dataportal.tbl_usage WHERE DATEDIFF(NOW(),access_date)<=30 GROUP BY object;",
                    defaultOrder: [[1, "desc"]]
                }, {
                    title: "Most accessed pages (last 90 days)",
                    selectName: "Most accessed pages (last 90 days)",
                    query: "SELECT object AS `Page`, COUNT(1) AS `# page clicks` FROM lastmile_dataportal.tbl_usage WHERE DATEDIFF(NOW(),access_date)<=90 GROUP BY object;",
                    defaultOrder: [[1, "desc"]]
                }
            ];
            break;

        case '12':
            options = [
                {
                    title: "Unserved Communities",
                    headerNote: "Note: Only remote communities are eligible for the CHA program, and so only remote communities are displayed.",
                    query: "SELECT county AS `County`, health_district AS `Health district`, community AS `Community`, health_facility_km AS `Distance to clinic (km)`, " + 
                            "population AS `Population` FROM lastmile_cha.view_base_geo_community WHERE active_cha='N' AND health_facility_proximity='remote';",
                    defaultOrder: [[4, "desc"]]
                }
            ];
            break;

        case '13':
            options = [
                {
                    title: "Database documentation",
                    headerNote: "For use by database administrators.",
                    query: "SELECT * FROM lastmile_dataportal.database_documentation;",
                    defaultOrder: [[0, "asc"]]
                }
            ];
            break;

        case '14':
            options = [

                {
                    title: "CHA Positions: Grand Gedeh",
                    selectName: "Grand Gedeh",
                    query: "SELECT health_facility AS `Facility`, chss_position_id as `CHSS Position ID`, coalesce( chss_person_id_lmh, 'N/A' ) as `CHSS LMH ID`, coalesce( chss, 'Unassigned' ) as `CHSS` , position_id as `CHA Position ID`, coalesce( position_id_lmh, 'N/A' )  as `LMH ID`, coalesce( cha, 'Unassigned' ) as `CHA`, concat( coalesce( community_list, 'Unassigned' ), ' (', coalesce( community_id_list, 'Unassigned'), ')' ) as  `Communities (IDs)` FROM lastmile_cha.view_base_position_cha_basic_info where county='Grand Gedeh';",
                    defaultOrder: [ [0, "asc"],[1, "asc"], [4, "asc"] ]
                }, {
                    title: "CHA Positions: Rivercess",
                    selectName: "Rivercess",
                    query: "SELECT health_facility AS `Facility`, chss_position_id as `CHSS Position ID`, coalesce( chss_person_id_lmh, 'N/A' ) as `CHSS LMH ID`, coalesce( chss, 'Unassigned' ) as `CHSS` , position_id as `CHA Position ID`, coalesce( position_id_lmh, 'N/A' )  as `LMH ID`, coalesce( cha, 'Unassigned' ) as `CHA`, concat( coalesce( community_list, 'Unassigned' ), ' (', coalesce( community_id_list, 'Unassigned'), ')' ) as  `Communities (IDs)` FROM lastmile_cha.view_base_position_cha_basic_info where county='Rivercess';",
                    defaultOrder: [ [0, "asc"],[1, "asc"], [4, "asc"] ]
                }, {
                    title: "CHA Positions: Grand Bassa",
                    selectName: "Grand Bassa",
                    query: "SELECT health_facility AS `Facility`, chss_position_id as `CHSS Position ID`, coalesce( chss_person_id_lmh, 'N/A' ) as `CHSS LMH ID`, coalesce( chss, 'Unassigned' ) as `CHSS` , position_id as `CHA Position ID`, coalesce( position_id_lmh, 'N/A' )  as `LMH ID`, coalesce( cha, 'Unassigned' ) as `CHA`, concat( coalesce( community_list, 'Unassigned' ), ' (', coalesce( community_id_list, 'Unassigned'), ')' ) as  `Communities (IDs)` FROM lastmile_cha.view_base_position_cha_basic_info where county='Grand Bassa';",
                    defaultOrder: [ [0, "asc"],[1, "asc"], [4, "asc"] ]
                }

            ];
            break;

        case '15':
            options = [

                {
                    title: "QAO Positions: Grand Gedeh",
                    selectName: "Grand Gedeh",
                    query: "select * from lastmile_report.view_position_qao_chss where county like 'Grand Gedeh';",
                    defaultOrder: [ [0, "asc"] ]
                }, {
                    title: "QAO Positions: Rivercess",
                    selectName: "Rivercess",
                    query: "select * from lastmile_report.view_position_qao_chss where county like 'Rivercess';",
                    defaultOrder: [ [0, "asc"] ]
                }, {
                    title: "QAO Positions: Grand Bassa",
                    selectName: "Grand Bassa",
                    query: "select * from lastmile_report.view_position_qao_chss where county like 'Grand Bassa';",
                    defaultOrder: [ [0, "asc"] ]
                }

            ];
            break;

        case '16':
            options = [
                {
                    title: "Home births",
                    query: "SELECT * FROM lastmile_report.view_home_birth;",
                    defaultOrder: []
                    // defaultOrder: [[0, "desc"],[1, "desc"],[2, "asc"],[3, "asc"]]
                }
            ];
            break;

        case '17':
            options = [
                {
                    title: "ODK device ID",
                    query: "select * from lastmile_report.view_diagnostic_device_id;",
                    defaultOrder: []
                    // defaultOrder: [[0, "asc"],[1, "asc"]]
                }
            ];
            break;

        case '18':
            options = [

                {
                    title: "HHR 2018 v. 2019: Grand Gedeh",
                    selectName: "Grand Gedeh",
                    query: "select * from lastmile_develop.view_hhr_2018_2019 where county like 'Grand Gedeh';",
                    defaultOrder: [ [0, "asc"] ]
                }, {
                    title: "HHR 2018 v. 2019: Rivercess",
                    selectName: "Rivercess",
                    query: "select * from lastmile_develop.view_hhr_2018_2019 where county like 'Rivercess';",
                    defaultOrder: [ [0, "asc"] ]
                }, {
                    title: "HHR 2018 v. 2019: Grand Bassa",
                    selectName: "Grand Bassa",
                    query: "select * from lastmile_develop.view_hhr_2018_2019 where county like 'Grand Bassa';",
                    defaultOrder: [ [0, "asc"] ]
                }

            ];
            break;

        case '19':
            options = [
                {
                    title: "DE records captured",
                    query: "select `Year`, `Month`, `Form`, `#Records` from lastmile_report.view_diagnostic_de_capture;",
                    defaultOrder: []
                }
            ];
            break;

        case '20':
            options = [
                {
                    title: "ODK records captured",
                    query: "select `Year`, `Month`, `Form`, `#Records` from lastmile_report.view_diagnostic_odk_capture;",
                    defaultOrder: []
                }
            ];
            break;

        case '21':
            options = [
                {
                    title: "",
                    selectName: "",
                    query: "",
                    defaultOrder: [[0, "asc"]]
                }, {
                    title: "",
                    selectName: "",
                    query: "",
                    defaultOrder: [[0, "asc"]]
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
                        dom: '<flti>',
                        order: options[selectIndex].defaultOrder,
                        lengthMenu: [[5, 10, 25, 100, -1], [5, 10, 25, 100, "all"]],
                        scrollX: true
                    });
                    
                    // Activate "fixed columns" plugin
                    new $.fn.dataTable.FixedColumns( DT, {
                        leftColumns:1
                    } );

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
