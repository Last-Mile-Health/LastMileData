<script>
<?php

    // Note: this code is adapted from frag_indicatorReport.php

    // Initiate/configure CURL session
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

    // Set instance IDs of all indicator instances used in the report (in either data tables or charts)
    $instIDString = "336,337,338,339,340,375,341,342,343,344,377,379,381,383,385,376,378,380,382,384,345,"
            . "386,2,346,348,350,352,354,71,347,349,351,353,415,414,413,412,387,388,231,233,235,297,296,"
            . "364,365,366,367,368,369,370,371,372,373,374,389,390,391,392,393,394,395,396,397,398,399,"
            . "400,401,402,403,404,405,406,407,408,355,356,357,358,56,359,360,361,362,57,409,410,411,363";

    // Echo JSON (indicator instance metadata)
    $url2 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/indicatorInstances/$instIDString";
    curl_setopt($ch,CURLOPT_URL,$url2);
    $json2 = curl_exec($ch);

    // Echo JSON (indicator instance data)
    $url3 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/instanceValues/$instIDString";
    curl_setopt($ch,CURLOPT_URL,$url3);
    $json3 = curl_exec($ch);

    // Close CURL session and echo JSON
    curl_close($ch);
    echo "var indicatorInstances = $json2;". "\n\n";
    echo "var instanceValues = $json3;". "\n\n";
    
?>

    // Declare ED Model
    var edModel = {
        indicators: [
            {
                name: 'Number of CHAs and CHSSs deployed through the NCHA program',
                definition: 'Total number of CHAs and CHSSs deployed across all 15 counties through the NCHA program',
                dataSource: 'Liberia iHRIS database',
                groups: [
                    { name: 'CHAs', instIDs: [336,337,338,339,340,375] },
                    { name: 'CHSSs', instIDs: [341,342] },
                    { name: 'People Served', instIDs: [343,344] }
                ]
            },
            {
                name: 'Number of community health assistants (CHAs) deployed',
                definition: 'Total number of CHAs deployed within LMH counties',
                dataSource: 'LMH employment trackers',
                groups: [
                    { name: 'Grand Gedeh', instIDs: [377,379,381,383,385] },
                    { name: 'Rivercess', instIDs: [376,378,380,382,384] }
                ]
            },
            {
                name: 'Number of community health supervisors deployed',
                definition: 'Total number of CHWLs and CCSs deployed within LMH counties',
                dataSource: 'LMH employment trackers',
                groups: [
                    { name: '', instIDs: [345,386,2] }
                ]
            },
            {
                name: 'Number of people served',
                definition: 'Number of people in target population living in a community that is served by an active CHA, based on LMH registration data',
                dataSource: 'LMH registration data',
                groups: [
                    { name: 'Grand Gedeh', instIDs: [346,348,350,352,354] },
                    { name: 'Rivercess', instIDs: [71,347,349,351,353] }
                ]
            },
            {
                name: 'County coverage',
                definition: 'Percentage of county population >5km of health facility that has access to a CHA',
                dataSource: 'GIS mapping data',
                groups: [
                    { name: '', instIDs: [415,414] }
                ]
            },
            {
                name: 'Correct Treatment Rate ',
                definition: 'Percentage of patient audits for which correct treatment was given',
                dataSource: 'CCS supervision trackers (NOT YET AVAILABLE DUE TO DATA QUALITY ISSUES)',
                groups: [
                    { name: '', instIDs: [413,412] }
                ]
            },
            {
                name: 'Monthly supervision rate',
                definition: 'Average number of supervision visits per CHW ([Total number of supervision visits] / [number of active CHWs])',
                dataSource: 'Monthly service report',
                groups: [
                    { name: '', instIDs: [387,388] }
                ]
            },
            {
                name: 'Number of supervision checklists correctly filled and reported by CHSS',
                definition: 'TBD',
                dataSource: 'TBD',
                groups: [
                    { name: '', instIDs: [] }
                ]
            },
            {
                name: 'Motorcycle uptime',
                definition: 'A motorbike is "down" when it cannot be used for its desired purpose for 1 or more hours. If the motorbike is down for 1 or more hours, the whole day counts as a motorbike down day. Down days are tracked only at the motorbike\'s base location, even when the motorbike is down in another location.',
                dataSource: 'Operations team records',
                groups: [
                    { name: '', instIDs: [231,233,235] }
                ]
            },
            {
                name: 'Percent of CHWs with all essential commodities in stock',
                definition: 'Percent of CHWs with all essential commodities in stock on day of restock visit',
                dataSource: 'ODK restock form',
                groups: [
                    { name: '', instIDs: [297,296] }
                ]
            },
            {
                name: 'Average time from job posting to HR initial shortlist delivered to hiring manager',
                definition: 'Total number of days between job posting and initial shortlist from HR delivered to Hiring Manager / Total number of vacancies',
                dataSource: 'Director of HR, HR records',
                groups: [
                    { name: '', instIDs: [364,365] }
                ]
            },
            {
                name: 'Total number of LMH staff',
                definition: 'Number of full-time staff on LMH payroll',
                dataSource: 'Director of HR, HR records',
                groups: [
                    { name: '', instIDs: [366,367,368,369,370,371,372,373,374] }
                ]
            },
            {
                name: 'Percent completion of planned quarterly activities for annual targets',
                definition: 'Percent completion of projected quarterly deadlines for activities required to successfully achieve annual targets',
                dataSource: 'Quarterly progress report',
                groups: [
                    { name: '', instIDs: [389,390,391,392,393,394,395,396] }
                ]
            },
            {
                name: 'Monthly variance in Budget-to-Actual',
                definition: 'Total variance between budget and actual expenditure',
                dataSource: 'Monthly financial reports',
                groups: [
                    { name: '', instIDs: [397,398,399,400,401,402,403,404,405] }
                ]
            },
            {
                name: 'Working capital ratio',
                definition: 'Working capital ratio determines how long LMH could sustain its level of spending using its net available assets. We include in working capital unrestricted and temporarily restricted net assets, and exclude permanently restricted net assets. Dividing these net available assets by LMH\'s total expenses for the most recent fiscal year, yields the working capital ratio',
                dataSource: 'Monthly financial reports',
                groups: [
                    { name: '', instIDs: [406,407,408] }
                ]
            },
            {
                name: 'Total funds received',
                definition: 'Total funds received on a cash basis',
                dataSource: 'P&D Revenue Roadmap report',
                groups: [
                    { name: '', instIDs: [355,356,357,358,56] }
                ]
            },
            {
                name: 'Total funds received & committed against 3-year target',
                definition: 'Total money received on a cash basis and committed in writing by the donor for receipt in current FY',
                dataSource: 'P&D Revenue Roadmap report',
                groups: [
                    { name: '', instIDs: [359,360,361,362,57] }
                ]
            },
            {
                name: 'Percentage of operating reserve raised',
                definition: 'Total dollars in operating reserve/operating reserve target',
                dataSource: 'Monthly financial report',
                groups: [
                    { name: '', instIDs: [409,410,411] }
                ]
            },
            {
                name: 'Cost to raise a dollar',
                definition: 'Total fundraising costs / Net revenue',
                dataSource: 'Monthly financial report',
                groups: [
                    { name: '', instIDs: [363] }
                ]
            }
        ],
        // !!!!! Eventually, replace this with automated code (like in LMD_dataPortal.js !!!!!
        months: [
            { yearMonth: "2016-7", shortMonth: "Jul '16" },
            { yearMonth: "2016-8", shortMonth: "Aug '16" },
            { yearMonth: "2016-9", shortMonth: "Sep '16" },
            { yearMonth: "2016-10", shortMonth: "Oct '16" },
            { yearMonth: "2016-11", shortMonth: "Nov '16" },
            { yearMonth: "2016-12", shortMonth: "Dec '16" },
            { yearMonth: "2017-1", shortMonth: "Jan '17" },
            { yearMonth: "2017-2", shortMonth: "Feb '17" }
        ]
    };
    
    // Add indicator info
    LMD_dataPortal.setMetadata(indicatorInstances);
    var instanceMetadata = LMD_dataPortal.getMetadata();
    for (var key1 in edModel.indicators) {
        for (var key2 in edModel.indicators[key1].groups) {
            var grp = edModel.indicators[key1].groups[key2];
            var instIDs = grp.instIDs;
            grp.instIDs = [];
            for (var key3 in instIDs) {
                var instID = instIDs[key3];
                grp.instIDs.push({
                    instID: instID,
                    shortName: instanceMetadata[instID].instShortName,
                    months: edModel.months,
                    target: LMD_utilities.format_number(instanceMetadata[instID].target, instanceMetadata[instID].indFormat)
                });
            }
        }
    }

    // Apply knockout bindings
    ko.applyBindings({
        edModel: edModel
    }, $('#reportContent')[0]);
    
    // Bootstrap the page
    LMD_dataPortal.bootstrap(instanceValues, indicatorInstances, null);
    
</script>

<div id="reportContent">
    
    <h1>Executive dashboard</h1>
    <hr>
    
    <div data-bind="foreach: {data:$root.edModel.indicators, as:'ind'}">
        
        <h3><b>Indicator #<span data-bind="text:$index()+1"></span>:</b> <span data-bind="text:ind.name"></span></h3>
        <p><b>Definition:</b> <span data-bind="text:ind.definition"></span></p>
        <p><b>Data source:</b> <span data-bind="text:ind.dataSource"></span></p>
        
        <table class="table table-striped table-hover">
            <tr>
                <th></th>
                <!-- ko foreach:$root.edModel.months -->
                <th data-bind="text:$data.shortMonth" style="text-align:right"></th>
                <!-- /ko -->
                <th style="text-align:right">FY17 target</th>
            </tr>
            
            <!-- ko foreach:ind.groups -->
            
                <tr>
                    <td style="color:#fff; background:#555"><b data-bind="text:$data.name"></b></td>
                    <!-- ko foreach:$root.edModel.months -->
                    <td style="background:#555"></td>
                    <!-- /ko -->
                    <td style="background:#555"></td>
                </tr>

                <!-- ko foreach:$data.instIDs -->
                <tr>
                    <td data-bind="text:$data.shortName"></td>
                    <!-- ko foreach:$data.months -->
                    <td class="instValue" data-bind="attr: {'data-yearmonth':$data.yearMonth, 'data-instid':$parent.instID}" style="text-align:right"></td>
                    <!-- /ko -->
                    <td data-bind="text:$data.target" style="text-align:right"></td>
                </tr>
                <!-- /ko -->
            
            <!-- /ko -->
            
        </table>
        <hr>
        
    </div>
    
</div>
