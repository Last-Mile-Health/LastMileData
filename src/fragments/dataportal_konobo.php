<script>
    
    // Asynchronously load data, then generate charts
    $.getScript("/LastMileData/src/js/data_konobo.js", function(){
        
        $.getScript("/LastMileData/src/js/data_execDashboard.js", function(){

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "dashboard_numFHWs",
                data: myData.numFHWs,
                colors: ["#F79646"],
                timeInterval: 3,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"FHWs"}
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "dashboard_numPeopleServed",
                data: myData.numPeopleServed,
                colors: ["#F79646"],
                timeInterval: 3,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"People"}
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "dashboard_numVillagesServed",
                data: myData.numVillagesServed,
                colors: ["#F79646"],
                timeInterval: 3,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"Villages"}
            });

//            LMD_dimpleHelper.lineGraph_monthly({
//                targetDiv: "konobo_multLine",
//                data: myData_Konobo.sickChildSource,
//                colors: ["#9BBB59","#4BACC6","#F79646","#C0504D","#8064A2"],
//                timeInterval: 1,
//                size: {x:505, y:400},
//                xyVars: {x:"Month", y:"Percent"},
//                multLine: "Source"
//              });
//
            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "konobo_dataEntryQA",
                data: myData_Konobo.dataEntryQA,
                colors: ["#F79646"],
                timeInterval: 1,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"% QA'd"},
                tickFormat: {y:"%"}
              });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "konobo_numRecordsEntered",
                data: myData_Konobo.numRecordsEntered,
                colors: ["#F79646"],
                timeInterval: 1,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"Records"},
              });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "konobo_sickChildFollowupRate",
                data: myData_Konobo.sickChildFollowupRate,
                colors: ["#F79646"],
                timeInterval: 1,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"Follow Up Rate"},
                tickFormat: {y:"%"}
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "konobo_sickChildrenTreated",
                data: myData_Konobo.sickChildrenTreated,
                colors: ["#F79646"],
                timeInterval: 1,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"Visits"},
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "sickChildrenTreated_condition",
                data: myData_Konobo.sickChildrenTreated_condition,
                colors: ["#9BBB59", "#4BACC6", "#F79646", "#C0504D", "#8064A2"],
                timeInterval: 3,
                size: {x:590, y:380},
                xyVars: {x:"Month", y:"Treated"},
                multLine: "Condition",
                legend: "right"
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "sickChildrenTreated_perPop",
                data: myData_Konobo.sickChildrenTreated_perPop,
                colors: ["#9BBB59", "#4BACC6", "#F79646", "#C0504D", "#8064A2"],
                timeInterval: 3,
                size: {x:590, y:380},
                xyVars: {x:"Month", y:"TreatedPerPop"},
                axisTitles: {y:"Treated per 10K people served"},
                multLine: "Condition",
                legend: "right"
            });
    
// !!!!!
var svg = dimple.newSvg("#konobo_sickChildSource", 545, 300);
var myChart = new dimple.chart(svg, myData_Konobo.sickChildSource);
myChart.setBounds(55, 30, 430, 235)
myChart.addCategoryAxis("x", "Month");
myChart.addCategoryAxis("y", "");
myChart.addMeasureAxis("p", "Percent");
var pies = myChart.addSeries("Source", dimple.plot.pie);
pies.radius = 25;
myChart.addLegend(140, 10, 330, 20, "right");
myChart.draw();
// !!!!!

        });
        
    });

</script>

<h1>Konobo Monthly Report <span style="font-size:60%">(updated: 3/18/2015)</span></h1>
<hr>

<!-- START: Konobo row 1 -->
<div class="row">
    <div class="col-md-4">
        <h3><b>Scale</b>. Number of FHWs deployed</h3>
        <p><b>Definition</b>: A deployed Frontline Health Worker (FHW) is receiving a monetary incentive, has received LMH training in at least one healthcare module, and is actively visiting patients within his or her community</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
            </tr>
            <tr>
                <td>55</td>
                <td>55</td>
                <td>55</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="dashboard_numFHWs"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row 1 -->

<!-- START: Konobo row 2 -->
<div class="row">
    <div class="col-md-4">
        <h3><b>Scale</b>. Number of people served</h3>
        <p><b>Definition</b>: Number of people living in a village that is served by an active FHW, based on LMH registration data</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
            </tr>
            <tr>
                <td>14,217</td>
                <td>14,298</td>
                <td>14,397</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="dashboard_numPeopleServed"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row 2 -->

<!-- START: Konobo row 3 -->
<div class="row">
    <div class="col-md-4">
        <h3><b>Scale</b>. Number of villages served</h3>
        <p><b>Definition</b>: Number of villages (geographically distinct rural communities) that are served by an active FHW</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
            </tr>
            <tr>
                <td>52</td>
                <td>52</td>
                <td>52</td>
                <!--<td>54</td>-->
                <!--<td>54</td>-->
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="dashboard_numVillagesServed"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row 3 -->

<!-- START: Konobo row 4 -->
<div class="row">
    <div class="col-md-4">
        <h3><b>M&E</b>. # of records entered</h3>
        <p><b>Definition</b>: Number of paper records entered into the database by data clerks</p>
        <table class='ptg_data'>
            <tr>
                <th>Oct '14</th>
                <th>Nov '14</th>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
            </tr>
            <tr>
                <td>1629</td>
                <td>1557</td>
                <td>135</td>
                <td>1080</td>
                <td>1003</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="konobo_numRecordsEntered"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row 4 -->

<!-- START: Konobo row 5 -->
<div class="row">
    <div class="col-md-4">
        <h3><b>M&E</b>. % of records QA'd</h3>
        <p><b>Definition</b>: Percentage of paper records entered by a data clerk that underwent data quality assurance with a supervisor</p>
        <table class='ptg_data'>
            <tr>
                <th>Oct '14</th>
                <th>Nov '14</th>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
            </tr>
            <tr>
                <td>5.6%</td>
                <td>2.9%</td>
                <td>8.9%</td>
                <td>1.1%</td>
                <td>9.6%</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="konobo_dataEntryQA"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row 5 -->

<!-- START: Konobo row 6 -->
<div class="row">
    <div class="col-md-4">
        <h3><b>iCCM</b>. Sick child visit source</h3>
        <p><b>Definition</b>: Percentage of sick child visits that came from each source</p>
        <table class='ptg_data'>
            <tr>
                <th>Source</th>
                <th>Percent (Feb '15)</th>
            </tr>
            <tr><td style='text-align:left'>Parent came to me</td><td>55.5%</td></tr>
            <tr><td style='text-align:left'>Well child visit</td><td>4.6%</td></tr>
            <tr><td style='text-align:left'>Noticed child was sick</td><td>17.7%</td></tr>
            <tr><td style='text-align:left'>Other</td><td>22.3%</td></tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="konobo_sickChildSource"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row 6 -->

<!-- START: Konobo row 7 -->
<div class="row">
    <div class="col-md-4">
        <h3><b>iCCM</b>. Followup Rate</h3>
        <p><b>Definition</b>: % of sick child cases treated within community that received 3 days of follow-up</p>
        <table class='ptg_data'>
            <tr>
                <th>Oct '14</th>
                <th>Nov '14</th>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
            </tr>
            <tr>
                <td>97.5%</td>
                <td>98.6%</td>
                <td>100.0%</td>
                <td>99.6%</td>
                <td>100.0%</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="konobo_sickChildFollowupRate"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row 7 -->

<!-- START: Konobo row 8 -->
<div class="row">
    <div class="col-md-4">
        <h3><b>iCCM</b>. Number of sick children treated</h3>
        <p><b>Definition</b>: Total number of distinct children treated for malaria, diarrhea, or pneumonia</p>
        <table class='ptg_data'>
            <tr>
                <th>Oct '14</th>
                <th>Nov '14</th>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
            </tr>
            <tr>
                <td>430</td>
                <td>204</td>
                <td>264</td>
                <td>252</td>
                <td>255</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="konobo_sickChildrenTreated"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row 8 -->

<!-- START: Konobo row 9 -->
<div class="row">
    <div class="col-md-4">
        <h3><b>iCCM</b>. Number of sick children treated, by condition</h3>
        <p><b>Definition</b>: Total number of children treated for malaria, diarrhea, or pneumonia</p>
        <table class='ptg_data'>
            <tr>
                <th></th>
                <th>Malaria</th>
                <th>Diarrhea</th>
                <th>ARI</th>
            </tr>
            <tr>
                <td>Feb '15</td>
                <td>326</td>
                <td>134</td>
                <td>139</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="sickChildrenTreated_condition"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row 9 -->

<!-- START: Konobo row 10 -->
<div class="row">
    <div class="col-md-4">
        <h3><b>iCCM</b>. Number of sick children treated, per 10,000 population</h3>
        <p><b>Definition</b>: Total number of children treated for malaria, diarrhea, or pneumonia, per 10,000 population served</p>
        <table class='ptg_data'>
            <tr>
                <th></th>
                <th>Malaria</th>
                <th>Diarrhea</th>
                <th>ARI</th>
            </tr>
            <tr>
                <td>Feb '15</td>
                <td>226</td>
                <td>93</td>
                <td>97</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="sickChildrenTreated_perPop"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row 10 -->

<!-- START: Konobo row X -->
<!--<div class="row">
    <div class="col-md-4">
        <h3><b>Scale</b>. Indicator name</h3>
        <p><b>Definition</b>: Indicator definition</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
            </tr>
            <tr>
                <td>52</td>
                <td>52</td>
                <td>52</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="konobo_X"></div>
    </div>
</div>
<hr>-->
<!-- END: Konobo row X -->

<!-- START: Konobo row X -->
<!--<div class="row">
    <div class="col-md-4">
        <h3><b>Scale</b>. Indicator name</h3>
        <p><b>Definition</b>: Indicator definition</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
            </tr>
            <tr>
                <td>52</td>
                <td>52</td>
                <td>52</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="konobo_X"></div>
    </div>
</div>
<hr>-->
<!-- END: Konobo row X -->

