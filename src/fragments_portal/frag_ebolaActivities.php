<script>
<?php
    // Set $indicatorIDs manually (!!!!! for now !!!!!)
    $indIDString = "46,47";
    echo "var indIDString = '$indIDString';". "\n\n";

    // Include file that interacts with LMD_REST.php
    // This file assigns two javascript objects: "data_rawValues" and "data_indicators"
    set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
    require_once("echoIndicatorsAndValues.php");
?>

// Load main script
$.getScript('../js/frag_ebolaActivities.js');

</script>

<h1>Ebola activities</h1>

<div id='dashboardContent'>
    <div class='row' rv-each-report_object="model_ebola">
        <hr style="margin:15px; border:1px solid #eee;">
        <div class='col-md-4'>
            <h3><b>{{index | plusOne}}</b>. {{report_object.roMetadata.indName}}</h3>
            <p><b>Definition</b>: {{report_object.roMetadata.indDefinition}}</p>
            <p rv-if="report_object.roMetadata.indTarget"><b>FY15 Target</b>: {{report_object.roMetadata.indTarget | format report_object.roMetadata.indFormat}}</p>
            <table class='ptg_data'>
                <tr>
                    <th rv-if="report_object.data.multiple">&nbsp;</th>
                    <th rv-each-date="report_object.data.dates">{{date | shortDate}}</th>
                </tr>
                <tr rv-each-values="report_object.data.values">
                    <td rv-if="report_object.data.multiple">{{values.name}}</td>
                    <td rv-each-value="values.values">{{value | format report_object.roMetadata.indFormat}}</td>
                </tr>
            </table>
            <hr class='smallHR'>
            <p rv-if="report_object.roMetadata.indNarrative"><b>Progress-to-goal</b>: {{report_object.roMetadata.indNarrative}}</p>
        </div>
        <div class='col-md-7'>
            <div rv-id="report_object.chartSpecs.div"></div>
        </div>
    </div>
</div>

<h3><b>2</b>. Ebola health worker trainings</h3>
<table class="table table-striped table-hover">
    <tr>
        <th rowspan='2'>Health Worker</th>
        <th colspan='5'>Grand Gedeh</th>
        <th colspan='5'>Rivercess</th>
        <th colspan='5'>Cumulative Total</th>
    </tr>
    <tr>
        <th>Dec '14</th>
        <th>Jan '15</th>
        <th>Feb '15</th>
        <th>Mar '15</th>
        <th>Apr '15</th>
        <th>Dec '14</th>
        <th>Jan '15</th>
        <th>Feb '15</th>
        <th>Mar '15</th>
        <th>Apr '15</th>
        <th>Dec '14</th>
        <th>Jan '15</th>
        <th>Feb '15</th>
        <th>Mar '15</th>
        <th>Apr '15</th>
    </tr>
    <tr>
        <td>CHWs</td>
        <td>55</td>
        <td></td>
        <td></td>
        <td></td>
        <td>13</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>55</td>
        <td>55</td>
        <td>55</td>
        <td>55</td>
        <td>68</td>
    </tr>
    <tr>
        <td>CHW Leaders</td>
        <td>4</td>
        <td></td>
        <td></td>
        <td></td>
        <td>2</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>4</td>
        <td>4</td>
        <td>4</td>
        <td>4</td>
        <td>6</td>
    </tr>
    <tr>
        <td>Community Clinical Supervisors</td>
        <td>2</td>
        <td></td>
        <td></td>
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
        <td>2</td>
    </tr>
    <tr>
        <td>gCHVs</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>77</td>
        <td>36</td>
        <td></td>
        <td></td>
        <td></td>
        <td>77</td>
        <td>113</td>
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
        <td></td>
        <td>60</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>60</td>
        <td>60</td>
    </tr>
    <tr>
        <td>CHSSs</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>10</td>
        <td>8</td>
        <td></td>
        <td></td>
        <td></td>
        <td>10</td>
        <td>18</td>
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
        <td></td>
        <td>222</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>340</td>
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
        <td></td>
        <td>11</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>11</td>
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
        <td></td>
        <td>573</td>
        <td></td>
        <td></td>
        <td>46</td>
        <td>154</td>
        <td>727</td>
        <td>727</td>
        <td>727</td>
    </tr>
    <tr>
        <td>Community members</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>32</td>
        <td>5</td>
        <td></td>
        <td></td>
        <td></td>
        <td>32</td>
        <td>37</td>
        <td>37</td>
        <td>37</td>
        <td>37</td>
    </tr>
    <tr>
        <td colspan='11'><b>TOTAL: </b></td>
        <td><b>577</b></td>
        <td><b>734</b></td>
        <td><b>1,307</b></td>
        <td><b>1,367</b></td>
        <td><b>1,382</b></td>
    </tr>
</table>
<hr>

<h3><b>3</b>. Ebola facility trainings</h3>
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

<h3><b>4</b>. Ebola IPC supplies</h3>
<p>22 tons of IPC supplies delivered to health centers in Grand Gedeh and Rivercess Counties.</p>
<div class="whitespace"></div>
