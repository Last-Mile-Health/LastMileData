<?php
    // Set include path, set require file, define query string
    set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );
    require_once("dataPortal.php");
?>

<script>
    
    LMD_dimpleHelper.lineGraph_monthly({
        targetDiv: "dataportal_ebolaActivities_screenAndEducate",
        data: JSON.parse(<?php echoJSON($cxn, "test_mart", "month", ["ebola_screened","ebola_educated"],["Screened","Educated"]); ?>),
        colors: ["#9BBB59", "#4BACC6", "#F79646", "#C0504D", "#8064A2"],
        timeInterval: 1,
        size: {x:590, y:380},
        xyVars: {x:"Month", y:"Value"}, // !!!!! potentially refactor standard names into LMD_dimpleHelper !!!!!
        axisTitles: {x:"Month", y:"People reached"},
        multLine: "Split", // !!!!! potentially refactor standard names into LMD_dimpleHelper !!!!!
        legend: "right"
    });

</script>

<h1>Ebola activities <span style="font-size:60%">(updated: 4/12/2015)</span></h1>
<hr>

<h3>Screening and education (Konobo)</h3>
<div id="dataportal_ebolaActivities_screenAndEducate"></div>
<hr>

<h3>Ebola health worker trainings</h3>
<table class="table table-striped table-hover">
    <tr>
        <th rowspan='2'>Health Worker</th>
        <th colspan='4'>Grand Gedeh</th>
        <th colspan='4'>Rivercess</th>
        <th colspan='4'>Cumulative Total</th>
    </tr>
    <tr>
        <th>Dec '14</th>
        <th>Jan '15</th>
        <th>Feb '15</th>
        <th>Mar '15</th>
        <th>Dec '14</th>
        <th>Jan '15</th>
        <th>Feb '15</th>
        <th>Mar '15</th>
        <th>Dec '14</th>
        <th>Jan '15</th>
        <th>Feb '15</th>
        <th>Mar '15</th>
    </tr>
    <tr>
        <td>FHWs</td>
        <td>55</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>55</td>
        <td>55</td>
        <td>55</td>
        <td>55</td>
    </tr>
    <tr>
        <td>FHW Leaders</td>
        <td>4</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>4</td>
        <td>4</td>
        <td>4</td>
        <td>4</td>
    </tr>
    <tr>
        <td>Clinical Mentors</td>
        <td>2</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>2</td>
        <td>2</td>
        <td>2</td>
        <td>2</td>
    </tr>
    <tr>
        <td>gCHVs</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>77</td>
        <td>36</td>
        <td></td>
        <td></td>
        <td>77</td>
        <td>113</td>
        <td>113</td>
        <td>113</td>
    </tr>
    <tr>
        <td>TTMs</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>60</td>
        <td></td>
        <td></td>
        <td></td>
        <td>60</td>
    </tr>
    <tr>
        <td>CHSSs</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>10</td>
        <td>8</td>
        <td></td>
        <td></td>
        <td>10</td>
        <td>18</td>
        <td>18</td>
        <td>18</td>
    </tr>
    <tr>
        <td>Facility staff</td>
        <td>118</td>
        <td></td>
        <td></td>
        <td></td>
        <td>222</td>
        <td></td>
        <td></td>
        <td></td>
        <td>340</td>
        <td>340</td>
        <td>340</td>
        <td>340</td>
    </tr>
    <tr>
        <td>CHT staff</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>11</td>
        <td></td>
        <td></td>
        <td></td>
        <td>11</td>
        <td>11</td>
        <td>11</td>
        <td>11</td>
    </tr>
    <tr>
        <td>CHC Members</td>
        <td>46</td>
        <td>108</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>573</td>
        <td></td>
        <td>46</td>
        <td>154</td>
        <td>727</td>
        <td>727</td>
    </tr>
    <tr>
        <td>Community members</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>32</td>
        <td>5</td>
        <td></td>
        <td></td>
        <td>32</td>
        <td>37</td>
        <td>37</td>
        <td>37</td>
    </tr>
    <tr>
        <td colspan='9'><b>TOTAL: </b></td>
        <td><b>577</b></td>
        <td><b>734</b></td>
        <td><b>1,307</b></td>
        <td><b>1,367</b></td>
    </tr>
</table>
<hr>

<h3>Ebola facility trainings</h3>
<table class="table table-striped table-hover">
    <tr>
        <th rowspan='2'>Facility type</th>
        <th colspan='2'>Grand Gedeh</th>
        <th colspan='2'>Rivercess</th>
        <th colspan='2'>Cumulative Total</th>
    </tr>
    <tr>
        <th>Dec '14</th>
        <th>Jan '15</th>
        <th>Dec '14</th>
        <th>Jan '15</th>
        <th>Dec '14</th>
        <th>Jan '15</th>
    </tr>
    <tr>
        <td>Government PHC-1</td>
        <td>16</td>
        <td></td>
        <td>16</td>
        <td></td>
        <td>32</td>
        <td>32</td>
    </tr>
    <tr>
        <td>Government PHC-2</td>
        <td>2</td>
        <td></td>
        <td></td>
        <td></td>
        <td>2</td>
        <td>2</td>
    </tr>
    <tr>
        <td>Government PHC-3</td>
        <td>1</td>
        <td></td>
        <td>1</td>
        <td></td>
        <td>2</td>
        <td>2</td>
    </tr>
    <tr>
        <td>Private clinics</td>
        <td></td>
        <td></td>
        <td>2</td>
        <td></td>
        <td>2</td>
        <td>2</td>
    </tr>
    <tr>
        <td colspan='5'><b>TOTAL: </b></td>
        <td><b>38</b></td>
        <td><b>38</b></td>
    </tr>
</table>
<hr>

<h3>Ebola IPC supplies</h3>
<p>22 tons of IPC supplies delivered to health centers in Grand Gedeh and Rivercess Counties.</p>
<div class="whitespace"></div>
