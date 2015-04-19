<script>
    
    // Asynchronously load data, then generate charts
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
            targetDiv: "dashboard_numSupervisors",
            data: myData.numSupervisors,
            colors: ["#F79646"],
            timeInterval: 3,
            size: {x:505, y:400},
            xyVars: {x:"Month", y:"Supervisors"}
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

        LMD_dimpleHelper.lineGraph_monthly({
            targetDiv: "dashboard_numHealthWorkersTrainedEbola",
            data: myData.numHealthWorkersTrainedEbola,
            colors: ["#F79646"],
            timeInterval: 3,
            size: {x:505, y:400},
            xyVars: {x:"Month", y:"Health workers"}
        });

        LMD_dimpleHelper.lineGraph_monthly({
            targetDiv: "dashboard_numFacilitiesIPC",
            data: myData.numFacilitiesIPC,
            colors: ["#F79646"],
            timeInterval: 1,
            size: {x:505, y:400},
            xyVars: {x:"Month", y:"Facilities"}
        });

        LMD_dimpleHelper.lineGraph_monthly({
            targetDiv: "dashboard_numStaffLiberia",
            data: myData.numStaffLiberia,
            colors: ["#F79646"],
            timeInterval: 1,
            size: {x:505, y:400},
            xyVars: {x:"Month", y:"Staff"}
        });

        LMD_dimpleHelper.lineGraph_monthly({
            targetDiv: "dashboard_numStaffUS",
            data: myData.numStaffUS,
            colors: ["#F79646"],
            timeInterval: 1,
            size: {x:505, y:400},
            xyVars: {x:"Month", y:"Staff"}
        });

        LMD_dimpleHelper.lineGraph_monthly({
            targetDiv: "dashboard_fundsReceived",
            data: myData.fundsReceived,
            colors: ["#F79646"],
            timeInterval: 1,
            size: {x:505, y:400},
            xyVars: {x:"Month", y:"USD"}
        });

        LMD_dimpleHelper.lineGraph_monthly({
            targetDiv: "dashboard_fundsReceivedAndCommitted",
            data: myData.fundsReceivedAndCommitted,
            colors: ["#F79646"],
            timeInterval: 1,
            size: {x:505, y:400},
            xyVars: {x:"Month", y:"USD"}
        });

        LMD_dimpleHelper.lineGraph_monthly({
            targetDiv: "dashboard_percentOfBudgetRaised",
            data: myData.percentOfBudgetRaised,
            colors: ["#F79646"],
            timeInterval: 1,
            size: {x:505, y:400},
            xyVars: {x:"Month", y:"Percent"},
            tickFormat: {y:"%"}
        });

        LMD_dimpleHelper.lineGraph_monthly({
            targetDiv: "dashboard_fy15Expenses",
            data: myData.fy15Expenses,
            colors: ["#F79646"],
            timeInterval: 1,
            size: {x:505, y:400},
            xyVars: {x:"Month", y:"USD"}
        });

        LMD_dimpleHelper.lineGraph_monthly({
            targetDiv: "dashboard_fy15Spent",
            data: myData.fy15Spent,
            colors: ["#F79646"],
            timeInterval: 1,
            size: {x:505, y:400},
            xyVars: {x:"Month", y:"Percent"},
            tickFormat: {y:"%"}
        });

        LMD_dimpleHelper.lineGraph_monthly({
            targetDiv: "dashboard_cashOnHand",
            data: myData.cashOnHand,
            colors: ["#F79646"],
            timeInterval: 1,
            size: {x:505, y:400},
            xyVars: {x:"Month", y:"USD"}
        });

        LMD_dimpleHelper.lineGraph_monthly({
            targetDiv: "dashboard_cashBurnRate",
            data: myData.cashBurnRate,
            colors: ["#F79646"],
            timeInterval: 1,
            size: {x:505, y:400},
            xyVars: {x:"Month", y:"USD"}
        });
        
    });

</script>

<h1>Executive Dashboard <span style="font-size:60%">(updated: 4/12/2015)</span></h1>
<hr>

<!-- START: Dashboard row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>1</b>. Number of FHWs deployed</h3>
        <p><b>Definition</b>: A deployed Frontline Health Worker (FHW) is receiving a monetary incentive, has received LMH training in at least one healthcare module, and is actively visiting patients within his or her community</p>
        <p><b>FY15 Target</b>: 378</p>
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
        <hr class='smallHR'>
        <p><b>Progress-to-goal</b>: OFF TRACK. Project planning process underway to produce revised projections for FY15, CY15, and FY16 by April 1.</p>
    </div>
    <div class="col-md-7">
        <div id="dashboard_numFHWs"></div>
    </div>
</div>
<hr>
<!-- END: Dashboard row -->

<!-- START: Dashboard row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>2</b>. Number of FHW supervisors</h3>
        <p><b>Definition</b>: Number of active FHW leaders and Clinical Mentors (on payroll)</p>
        <p><b>FY15 Target</b>: 42</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>5</td>
                <td>5</td>
                <td>5</td>
                <td>5</td>
            </tr>
        </table>
        <hr class='smallHR'>
        <p><b>Progress-to-goal</b>: OFF TRACK. Project planning process underway to produce revised projections for FY15, CY15, and FY16 by April 1.</p>
    </div>
    <div class="col-md-7">
        <div id="dashboard_numSupervisors"></div>
    </div>
</div>
<hr>
<!-- END: Dashboard row -->

<!-- START: Dashboard row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>3</b>. Number of people served</h3>
        <p><b>Definition</b>: Number of people living in a village that is served by an active FHW, based on LMH registration data</p>
        <p><b>FY15 Target</b>: 92,919</p>
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
        <hr class='smallHR'>
        <p><b>Progress-to-goal</b>: OFF TRACK. Project planning process underway to produce revised projections for FY15, CY15, and FY16 by April 1.</p>
    </div>
    <div class="col-md-7">
        <div id="dashboard_numPeopleServed"></div>
    </div>
</div>
<hr>
<!-- END: Dashboard row -->

<!-- START: Dashboard row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>4</b>. Number of villages served</h3>
        <p><b>Definition</b>: Number of villages (geographically distinct rural communities) that are served by an active FHW</p>
        <p><b>FY15 Target</b>: 400</p>
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
        <hr class='smallHR'>
        <p><b>Progress-to-goal</b>: OFF TRACK. Project planning process underway to produce revised projections for FY15, CY15, and FY16 by April 1.</p>
    </div>
    <div class="col-md-7">
        <div id="dashboard_numVillagesServed"></div>
    </div>
</div>
<hr>
<!-- END: Dashboard row -->

<!-- START: Dashboard row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>5</b>. Number of health workers trained in Ebola-specific services</h3>
        <p><b>Definition</b>: Cumulative number of health workers/volunteers (FHWs, gCHVs, supervisors, facility staff, CHT staff, community members) who received training in one or more of the following: contact tracing, surveillance, IPC, education, case management</p>
        <p><b>FY15 Target</b>: 518</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>577</td>
                <td>734</td>
                <td>1,307</td>
                <td>1,367</td>
            </tr>
        </table>
        <hr class='smallHR'>
        <p><b>Progress-to-goal</b>: ON TRACK. Target exceeded, due to larger-than-expected number of facility staff, gCHVs, and CHC members who were trained.</p>
    </div>
    <div class="col-md-7">
        <div id="dashboard_numHealthWorkersTrainedEbola"></div>
    </div>
</div>
<hr>
<!-- END: Dashboard row -->

<!-- START: Dashboard row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>6</b>. Number of health centers trained to respond to Ebola</h3>
        <p><b>Definition</b>: Number of government health facilities (PHC-1, PHC-2, or PHC-3) that received an infection prevention and control (IPC) training organized by LMH</p>
        <p><b>FY15 Target</b>: 35 (100%)</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>36</td>
                <td>36</td>
                <td>36</td>
                <td>36</td>
            </tr>
        </table>
        <hr class='smallHR'>
        <p><b>Progress-to-goal</b>: ON TRACK. Target of 100% of health facilities was reached in December.</p>
    </div>
    <div class="col-md-7">
        <div id="dashboard_numFacilitiesIPC"></div>
    </div>
</div>
<hr>
<!-- END: Dashboard row -->

<!-- START: Dashboard row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>7</b>. Total number of staff in Liberia</h3>
        <p><b>Definition</b>: Total number of full-time staff in Liberia (excludes Frontline Health Workers)</p>
        <p><b>FY15 Target</b>: n/a</p>
        <table class='ptg_data'>
            <tr>
                <th>Jan '15</th>
                <th>Feb '15</th>
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>143</td>
                <td>149</td>
                <td>160</td>
            </tr>
        </table>
        <hr class='smallHR'>
        <!--<p><b>Progress-to-goal</b>: Narrative goes here...</p>-->
    </div>
    <div class="col-md-7">
        <div id="dashboard_numStaffLiberia"></div>
    </div>
</div>
<hr>
<!-- END: Dashboard row -->

<!-- START: Dashboard row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>8</b>. Total number of staff in U.S.</h3>
        <p><b>Definition</b>: Total number of full and part-time staff in USA (excludes consultants)</p>
        <p><b>FY15 Target</b>: n/a</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>12</td>
                <td>13</td>
                <td>13</td>
                <td>13</td>
            </tr>
        </table>
        <hr class='smallHR'>
        <!--<p><b>Progress-to-goal</b>: Narrative goes here...</p>-->
    </div>
    <div class="col-md-7">
        <div id="dashboard_numStaffUS"></div>
    </div>
</div>
<hr>
<!-- END: Dashboard row -->

<!-- START: Dashboard row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>9</b>. Total funds received</h3>
        <p><b>Definition</b>: Total money received</p>
        <p><b>FY15 Target</b>: $8,500,000</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>$5,929,485</td>
                <td>$6,529,994</td>
                <td>$7,424,971</td>
                <td>$8,404,584</td>
            </tr>
        </table>
        <hr class='smallHR'>
        <p><b>Progress-to-goal</b>: ON TRACK. LMH is on track to acheive goal. In addtion to the $8.4m in financing that has been received to date, approximately $600,000 in committed financing can be counted as FY15 receivables, with an additional $1.7m in receivables for FY17 & FY18.</p>
    </div>
    <div class="col-md-7">
        <div id="dashboard_fundsReceived"></div>
    </div>
</div>
<hr>
<!-- END: Dashboard row -->

<!-- START: Dashboard row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>10</b>. Total funds received and committed</h3>
        <p><b>Definition</b>: Total money received and committed</p>
        <p><b>FY15 Target</b>: $8,500,000</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>$7,916,371</td>
                <td>$8,359,411</td>
                <td>$8,561,521</td>
                <td>$9,133,923</td>
            </tr>
        </table>
        <hr class='smallHR'>
        <p><b>Progress-to-goal</b>: ON TRACK. LMH has exceeded goal. In addition to the $9.1 million funds received and committed, approximately $200,000 in highly likely philanthropy is currently pipelined.</p>
    </div>
    <div class="col-md-7">
        <div id="dashboard_fundsReceivedAndCommitted"></div>
    </div>
</div>
<hr>
<!-- END: Dashboard row -->

<!-- START: Dashboard row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>11</b>. Percent of FY15 budget raised to date</h3>
        <p><b>Definition</b>: Total percent of FY15 budget raised based on funds received to date</p>
        <p><b>FY15 Target</b>: $6,978,616 (100%)</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>85%</td>
                <td>94%</td>
                <td>106%</td>
                <td>120%</td>
            </tr>
        </table>
        <hr class='smallHR'>
        <p><b>Progress-to-goal</b>: ON TRACK. LMH has achieved this goal.</p>
    </div>
    <div class="col-md-7">
        <div id="dashboard_percentOfBudgetRaised"></div>
    </div>
</div>
<hr>
<!-- END: Dashboard row -->

<!-- START: Dashboard row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>12</b>. Total FY15 expenses</h3>
        <p><b>Definition</b>: Total money spent and committed</p>
        <p><b>FY15 Target</b>: $6,978,616</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>$2,010,935</td>
                <td>$2,767,124</td>
                <td>$3,268,932</td>
                <td>$4,290,787</td>
            </tr>
        </table>
        <hr class='smallHR'>
        <p><b>Progress-to-goal</b>: OFF TRACK. Likely to be under budget by end of FY due to deferred expenses in Q3.</p>
    </div>
    <div class="col-md-7">
        <div id="dashboard_fy15Expenses"></div>
    </div>
</div>
<hr>
<!-- END: Dashboard row -->

<!-- START: Dashboard row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>13</b>. Percent of FY15 budget spent</h3>
        <p><b>Definition</b>: Total percent of FY15 Budget spent to date</p>
        <p><b>FY15 Target</b>: n/a</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>29%</td>
                <td>40%</td>
                <td>49%</td>
                <td>61%</td>
            </tr>
        </table>
        <hr class='smallHR'>
        <p><b>Progress-to-goal</b>: OFF TRACK. Likely to be under budget by end of FY due to defferred expenses in Q3.</p>
    </div>
    <div class="col-md-7">
        <div id="dashboard_fy15Spent"></div>
    </div>
</div>
<hr>
<!-- END: Dashboard row -->

<!-- START: Dashboard row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>14</b>. Cash on hand</h3>
        <p><b>Definition</b>: The amount of money in the form of cash that LMH has on hand after it has covered its costs</p>
        <p><b>FY15 Target</b>: n/a</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Feb '15</th>
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>$4,305,484</td>
                <td>$4,623,701</td>
                <td>$4,791,627</td>
                <td>$4,245,777</td>
            </tr>
        </table>
        <hr class='smallHR'>
        <p><b>Progress-to-goal</b>: Represents 10 months' worth operating cash.</p>
    </div>
    <div class="col-md-7">
        <div id="dashboard_cashOnHand"></div>
    </div>
</div>
<hr>
<!-- END: Dashboard row -->

<!-- START: Dashboard row -->
<div class="row">
    <div class="col-md-4">
        <h3><b>15</b>. Cash burn rate</h3>
        <p><b>Definition</b>: Average monthly cash spent</p>
        <p><b>FY15 Target</b>: n/a</p>
        <p style="color:maroon">(updated quarterly)</p>
        <table class='ptg_data'>
            <tr>
                <th>Dec '14</th>
                <th>Jan '15</th>
                <th>Mar '15</th>
            </tr>
            <tr>
                <td>$329,000</td>
                <td>$420,000</td>
                <td>$667,000</td>
            </tr>
        </table>
        <hr class='smallHR'>
        <p><b>Progress-to-goal</b>: Spike due to quarterly procurements.</p>
    </div>
    <div class="col-md-7">
        <div id="dashboard_cashBurnRate"></div>
    </div>
</div>
<hr>
<!-- END: Dashboard row -->
