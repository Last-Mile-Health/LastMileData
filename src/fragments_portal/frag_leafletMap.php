<link rel="stylesheet" href="../../lib/leaflet/leaflet.css" />

<?php
    // Echo "data availability" object
    echo "<script>";

    // Initiate/configure CURL session
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

    // Echo JSON (indicators for which GIS-linked data is available)
    $url1 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/gis_data_availability/";
    curl_setopt($ch,CURLOPT_URL,$url1);
    $json1 = curl_exec($ch);

    // Close CURL session and echo JSON
    curl_close($ch);
    echo "var availability_RAW = $json1;". "\n\n";
    echo "</script>";
?>

<script>
    $.getScript('../../lib/leaflet/leaflet.js', function(){
                $.getScript('../js/frag_leafletMap.js');
    });
</script>

<style>
    .leaflet-icon-g0 {
	background: #FFEDA0;
	border: 1px solid #555;
        border-radius: 5px;
    }
    .leaflet-icon-g1 {
	background: #FED976;
	border: 1px solid #555;
        border-radius: 5px;
    }
    .leaflet-icon-g2 {
	background: #FD8D3C;
	border: 1px solid #555;
        border-radius: 5px;
    }
    .leaflet-icon-g3 {
	background: #E31A1C;
	border: 1px solid #555;
        border-radius: 5px;
    }
    .leaflet-icon-g4 {
	background: #800026;
	border: 1px solid #555;
        border-radius: 5px;
    }
    
    #map {
        width: 100%;
        height: 75vh;
    }

    .leaflet-control-attribution {
        display:none;
    }

    .info {
        padding: 6px 8px;
        font: 14px/16px Arial, Helvetica, sans-serif;
        background: white;
        background: rgba(255,255,255,0.8);
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        border-radius: 5px;
    }
    .info h4 {
        margin: 0 0 5px;
        color: #777;
    }

    .legend {
        text-align: left;
        line-height: 18px;
        color: #555;
    }
    .legend i {
        width: 18px;
        height: 18px;
        float: left;
        margin-right: 8px;
        opacity: 0.7;
    }
</style>

<div style="color:#555; font-weight:bold; margin-top:5px; padding-bottom:5px; float:left; border-bottom: 2px solid #555;">
    
    <div style="width:140px; float:left; margin-right:10px; position:relative; top:6px">
        Map functions:
    </div>
    
    <!-- Select map items -->
    <div style="float:left; margin-right:5px">
        <select id="toggleMapItems" multiple="multiple">
            <option value="county">Counties</option>
            <option value="district">Districts</option>
            <option value="communities_remote">Communities (remote)</option>
            <option value="communities_nearFacility">Communities (near-facility)</option>
            <option value="healthFacility">Health facilities</option>
            <!--<option value="road">Roads</option>--> <!-- !!!!! Need to figure out how to load these layers efficiently; perhaps via asynchronous caching !!!!! -->
            <!--<option value="river">Rivers</option>-->
        </select>
    </div>
    
    <!-- Select zoom -->
    <div style="float:left; margin-right:5px">
        <select id="select_zoom" class="form-control">
            <option>Select zoom</option>
            <option value='RC'>Rivercess</option>
            <option value='GG'>Grand Gedeh</option>
            <!--<option value='GB'>Grand Bassa</option>--> <!-- !!!!! add !!!!! -->
            <option value='LIB'>Liberia</option>
        </select>
    </div>
    
    <!-- Select base map -->
    <div style="float:left; margin-right:5px">
        <select id="select_basemap" class="form-control">
            <option>Select base map</option>
            <option value='http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'>Open Street Map</option>
            <option value='http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'>Satellite: ESRI</option>
            <option value='http://server.arcgisonline.com/ArcGIS/rest/services/NatGeo_World_Map/MapServer/tile/{z}/{y}/{x}'>National Geographic</option>
            <option value='http://mt.google.com/vt/lyrs=m&x={x}&y={y}&z={z}'>Google: Streets</option>
            <option value='http://mt.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}'>Google: Hybrid</option>
            <option value='http://mt.google.com/vt/lyrs=s&x={x}&y={y}&z={z}'>Satellite: Google</option>
            <option value='http://mt.google.com/vt/lyrs=p&x={x}&y={y}&z={z}'>Google: Terrain</option>
            <option value='http://{s}.tile.thunderforest.com/landscape/{z}/{x}/{y}.png'>Landscape</option>
            <option value='http://{s}.tile.thunderforest.com/outdoors/{z}/{x}/{y}.png'>Outdoors</option>
            <option value='http://stamen-tiles-a.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.png'>Watercolor</option>
            <option value='http://korona.geog.uni-heidelberg.de/tiles/roads/x={x}&y={y}&z={z}'>Open Map Surfer</option>
            <option value='http://server.arcgisonline.com/ArcGIS/rest/services/Specialty/DeLorme_World_Base_Map/MapServer/tile/{z}/{y}/{x}'>ESRI Delorme</option>
            <option value='http://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}'>ESRI World Topo Map</option>
            <option value='http://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}'>ESRI World Street Map</option>
        </select>
    </div>
    
</div>
    
<div style="color:#555; font-weight:bold; margin-top:5px; margin-bottom:10px; float:left;">
    
    <div style="width:140px; float:left; margin-right:10px; position:relative; top:6px">
        Indicator functions:
    </div>
    
    <!-- Select indicator -->
    <div style="float:left; margin-right:5px">
        <select id="select_indicator" class="indChange form-control">
            <option value='1. Select indicator'>1. Select indicator</option>
            <!-- ko foreach: $root -->
            <option data-bind="text:ind_name, value:ind_id"></option>
            <!-- /ko -->
        </select>
    </div>
    
    <!-- Select indicator "level" -->
    <div style="float:left; margin-right:5px">
        <select id="select_level" class="indChange form-control" disabled>
            <option value='2. Select level'>2. Select level</option>
            <option value='county'>County</option>
            <option value='district'>District</option>
            <option value='communities_CHW'>Community</option>
        </select>
    </div>
    
    <!-- Select indicator "period" -->
    <div style="float:left; margin-right:5px">
        <select id="select_period" class="indChange form-control" disabled>
            <option value='3. Select period'>3. Select period</option>
            <option value='1'>Last month</option>
            <option value='2'>Last 3 months</option>
            <option value='3'>Last 6 months</option>
            <!-- !!!!! this should pull from database periods table !!!!! -->
        </select>
    </div>
    
    <!-- "Reset" button -->
    <div style="float:left; margin-right:5px">
        <button id="indReset" class="btn btn-danger">
            Reset
        </button>
    </div>
    
</div>

<div id="map"></div>
