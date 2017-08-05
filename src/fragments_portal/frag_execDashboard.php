<script>
<?php

    // Note: this code is adapted from frag_indicatorReport.php

    // Initiate/configure CURL session
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

    // Set instance IDs of all indicator instances used in the report (in either data tables or charts)
    $instIDString = "336,337,338,339,340,375,341,342,343,344,377,379,381,383,385,376,378,380,382,384,420,"
            . "422,423,346,71,415,414,413,412,533,668,231,233,235,297,296,"
            . "364,365,366,367,368,369,370,371,372,373,374,389,390,391,392,393,394,395,396,406,407,408,"
            . "355,356,357,358,56,359,360,361,362,57,409,410,411,363,418,70,419,424,425,426,669,428,487,68";

    // Echo JSON (indicator instance metadata)
    $url2 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/indicatorInstances/1/$instIDString";
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
                definition: 'Total number of CHAs and CHSSs deployed across all 15 counties through the NCHA program. Training numbers refer to the number of CHAs who have completed NCHA training modules. NOTE THAT NUMBERS ARE SELF-REPORTED AND ARE SUBJECT TO FLUCTUATIONS AS THEY ARE VALIDATED. Also note that we have updated monthly numbers such that changes from month to month are reflective of additional CHAs being trained, rather than changes in current understanding.',
                dataSource: 'Current: self-reporting by implementing partners. Future: Liberia iHRIS database',
                groups: [
                    { name: 'CHAs', instIDs: [336,337,338,339,340,375] },
                    { name: 'CHSSs', instIDs: [341,342] },
                    { name: 'People Served', instIDs: [343,344] }
                ]
            },
            {
                name: 'Number of Community Health Assistants (CHAs) deployed',
                definition: 'Total number of CHAs deployed within LMH counties. Training numbers through 2016 include LMH training modules. Training numbers from January 2017 onwards reflect completion of NCHA training modules',
                dataSource: 'LMH employment trackers',
                groups: [
                    { name: 'Grand Gedeh', instIDs: [487,377,379,381,383,385] },
                    { name: 'Rivercess', instIDs: [68,376,378,380,382,384] }
                ]
            },
            {
                name: 'Number of Community Health Services Supervisors deployed',
                definition: 'Total number of Community Health Services Supervisors (CHSSs) deployed within LMH counties.',
                dataSource: 'LMH employment trackers',
                groups: [
                    { name: '', instIDs: [420,422,423] }
                ]
            },
            {
                name: 'Number of people served',
                definition: 'Number of people in target population living in a community that is served by an active CHA, based on LMH registration data.',
                dataSource: 'LMH registration data',
                groups: [
                    { name: 'Grand Gedeh', instIDs: [346,419] },
                    { name: 'Rivercess', instIDs: [71,70] }
                ]
            },
            {
                name: 'County coverage',
                definition: 'Percentage of county population >5km of health facility that has access to a CHA, based on household counts.',
                dataSource: 'GIS mapping data, CHA registration data',
                groups: [
                    { name: '', instIDs: [415,414,425] }
                ]
            },
            {
                name: 'Correct Treatment Rate ',
                definition: 'Percentage of patient audits for which correct treatment was given.',
                dataSource: 'CCS supervision trackers (Data is curently unavailable due to CHSS compliance issues)',
                groups: [
                    { name: '', instIDs: [413,412,426] }
                ]
            },
            {
                name: 'Monthly supervision rate',
                definition: 'Average number of supervision visits by a CHWL/CHSS, per CHA ([Total number of supervision visits] / [number of active CHAs]).',
                dataSource: 'Monthly service report',
                groups: [
                    { name: '', instIDs: [533,668,669] }
                ]
            },
            {
                name: 'Number of supervision checklists correctly filled and reported by CHSS',
                definition: 'TBD. This indicator is pending review by the programs team.',
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
                    { name: '', instIDs: [231,233,235,418] }
                ]
            },
            {
                name: 'Percent of CHAs with all essential commodities in stock',
                definition: 'Percent of CHAs with all essential commodities in stock on day of restock visit',
                dataSource: 'ODK restock form',
                groups: [
                    { name: '', instIDs: [297,296,428] }
                ]
            },
            {
                name: 'Average time from job posting to HR initial shortlist delivered to hiring manager',
                definition: 'Total number of days between job posting and initial shortlist from HR delivered to Hiring Manager / Total number of vacancies.',
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
                definition: 'Percent completion of projected quarterly deadlines for activities required to successfully achieve annual targets.',
                dataSource: 'Quarterly progress report',
                groups: [
                    { name: '', instIDs: [389,390,391,392,393,394,395,396,424] }
                ]
            },
            {
                name: 'Cash reserves ratio',
                definition: 'Average number of months of operating expenses covered by cash on hand.',
                dataSource: 'Monthly financial reports',
                groups: [
                    { name: '', instIDs: [406,407,408] }
                ]
            },
            {
                name: 'Total funds received',
                definition: 'Total funds received on a cash basis.',
                dataSource: 'P&D Revenue Roadmap report',
                groups: [
                    { name: '', instIDs: [355,356,357,358,56] }
                ]
            },
            {
                name: 'Total funds received & committed against 3-year target',
                definition: 'Total money received on a cash basis and committed in writing by the donor for receipt in current FY.',
                dataSource: 'P&D Revenue Roadmap report',
                groups: [
                    { name: '', instIDs: [359,360,361,362,57] }
                ]
            },
            {
                name: 'Percentage of operating reserve raised',
                definition: 'Total dollars in operating reserve/operating reserve target.',
                dataSource: 'Monthly financial report',
                groups: [
                    { name: '', instIDs: [409,410,411] }
                ]
            },
            {
                name: 'Cost to raise a dollar',
                definition: 'Total fundraising costs / Net revenue.',
                dataSource: 'Monthly financial report',
                groups: [
                    { name: '', instIDs: [363] }
                ]
            }
        ],
        // !!!!! Eventually, replace this with automated code (like in LMD_dataPortal.js !!!!!
        months: [
//            { yearMonth: "2016-10", shortMonth: "Oct '16" },
            { yearMonth: "2016-11", shortMonth: "Nov '16" },
            { yearMonth: "2016-12", shortMonth: "Dec '16" },
            { yearMonth: "2017-1", shortMonth: "Jan '17" },
            { yearMonth: "2017-2", shortMonth: "Feb '17" },
            { yearMonth: "2017-3", shortMonth: "Mar '17" },
            { yearMonth: "2017-4", shortMonth: "Apr '17" },
            { yearMonth: "2017-5", shortMonth: "May '17" },
            { yearMonth: "2017-6", shortMonth: "Jun '17" }
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
    
    // !!!!! Custom change: FY17-19 target for indicator #16 !!!!!
    $('#table_16 th:last-child').text('FY17-19 target');

</script>

<div id="reportContent">
    
    <h1>Executive dashboard</h1>
    <hr>
    
    <div data-bind="foreach: {data:$root.edModel.indicators, as:'ind'}">
        
        <h3><b>Indicator #<span data-bind="text:$index()+1"></span>:</b> <span data-bind="text:ind.name"></span></h3>
        <p><b>Definition:</b> <span data-bind="text:ind.definition"></span></p>
        <p><b>Data source:</b> <span data-bind="text:ind.dataSource"></span></p>
        
        <table class="table table-striped table-hover" data-bind="attr: {id:'table_'+($index()+1)}">
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
