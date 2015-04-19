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
                targetDiv: "konobo_facilityDelivery",
                data: myData_Konobo.facilityDelivery,
                colors: ["#F79646"],
                timeInterval: 1,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"Facility Delivery"},
              });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "konobo_anc",
                data: myData_Konobo.ancVisitation,
                colors: ["#9BBB59", "#4BACC6", "#F79646", "#C0504D", "#8064A2"],
                timeInterval: 1,
                size: {x:590, y:380},
                xyVars: {x:"Month", y:"Percent"},
                axisTitles: {y:"Percent of women"},
                multLine: "Rate",
                legend: "right"
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
myChart.setBounds(55, 30, 430, 235);
myChart.addCategoryAxis("x", "Month");
myChart.addCategoryAxis("y", "");
myChart.addMeasureAxis("p", "Value");
var pies = myChart.addSeries("Source", dimple.plot.pie);
pies.radius = 25;
myChart.addLegend(140, 10, 330, 20, "right");
myChart.draw();
// !!!!!

        });
        
    });

</script>

<h1>Konobo Monthly Report <span style="font-size:60%">(updated: 4/12/2015)</span></h1>
<hr>

<!-- START: Konobo row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>Scale</b>. Number of FHWs deployed</h3>
        <p><b>Definition</b>: A deployed Frontline Health Worker (FHW) is receiving a monetary incentive, has received LMH training in at least one healthcare module, and is actively visiting patients within his or her community</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>55</td>
                <td>55</td>
                <td>55</td>
                <td>58</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="dashboard_numFHWs"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row -->

<!-- START: Konobo row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>Scale</b>. Number of people served</h3>
        <p><b>Definition</b>: Number of people living in a village that is served by an active FHW, based on LMH registration data</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>14,217</td>
                <td>14,298</td>
                <td>14,397</td>
                <td>14,517</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="dashboard_numPeopleServed"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row -->

<!-- START: Konobo row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>Scale</b>. Number of villages served</h3>
        <p><b>Definition</b>: Number of villages (geographically distinct rural communities) that are served by an active FHW</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>52</td>
                <td>52</td>
                <td>52</td>
                <td>55</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="dashboard_numVillagesServed"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row -->

<!-- START: Konobo row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>Impact</b>. Facility delivery</h3>
        <p><b>Definition</b>: Percentage of women who delivered in a health facility (out of all women who delivered in the past month)</p>
        <table class='ptg_data'>
            <tr>
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>68.4%</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="konobo_facilityDelivery"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row -->

<!-- START: Konobo row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>Impact</b>. Antenatal care rates</h3>
        <p><b>Definition</b>: Percentage of women who received at least {one, four} ANC visits during the course of their pregnancy (out of all women who delivered in the past month)</p>
        <table class='ptg_data'>
            <tr>
                <th></th>
                <th>ANC-1</th>
                <th>ANC-4</th>
            </tr>
            <tr>
                <td>Mar '15</td>
                <td>91.3%</td>
                <td>43.5%</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="konobo_anc"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row -->

<!-- START: Konobo row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>iCCM</b>. Sick child visit source</h3>
        <p><b>Definition</b>: Percentage of sick child visits that came from each source</p>
        <table class='ptg_data'>
            <tr>
                <th>Source</th>
                <th>Percent (Mar '15)</th>
            </tr>
            <tr><td style='text-align:left'>Parent came to me</td><td>57.9%</td></tr>
            <tr><td style='text-align:left'>Well child visit</td><td>3.6%</td></tr>
            <tr><td style='text-align:left'>Noticed child was sick</td><td>15.8%</td></tr>
            <tr><td style='text-align:left'>Other</td><td>22.7%</td></tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="konobo_sickChildSource"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row -->

<!-- START: Konobo row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>iCCM</b>. Sick child follow-up Rate</h3>
        <p><b>Definition</b>: % of sick child cases treated within community that received 3 days of follow-up</p>
        <table class='ptg_data'>
            <tr>
                <th>Oct '14</th>
                <th>Nov '14</th>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>97.5%</td>
                <td>98.6%</td>
                <td>100.0%</td>
                <td>99.3%</td>
                <td>100.0%</td>
                <td>99.4%</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="konobo_sickChildFollowupRate"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row -->

<!-- START: Konobo row -->
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
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>429</td>
                <td>207</td>
                <td>241</td>
                <td>255</td>
                <td>350</td>
                <td>295</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="konobo_sickChildrenTreated"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row -->

<!-- START: Konobo row -->
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
                <td>Mar '15</td>
                <td>297</td>
                <td>90</td>
                <td>143</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="sickChildrenTreated_condition"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row -->

<!-- START: Konobo row -->
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
                <td>Mar '15</td>
                <td>205</td>
                <td>62</td>
                <td>99</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="sickChildrenTreated_perPop"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row -->

<!-- START: Konobo row -->
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
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>1629</td>
                <td>1557</td>
                <td>135</td>
                <td>1080</td>
                <td>1051</td>
                <td>2372</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="konobo_numRecordsEntered"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row -->

<!-- START: Konobo row -->
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
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>5.6%</td>
                <td>2.9%</td>
                <td>8.9%</td>
                <td>1.1%</td>
                <td>9.5%</td>
                <td>7.3%</td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <div id="konobo_dataEntryQA"></div>
    </div>
</div>
<hr>
<!-- END: Konobo row -->

<!-- START: Konobo row -->
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
<!-- END: Konobo row -->

<!-- START: Konobo row -->
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
<!-- END: Konobo row -->
