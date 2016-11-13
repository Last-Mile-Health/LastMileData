
// !!!!! Consolidate all globals into a global object "G" !!!!!

// Declare `map` global; default to view of Rivercess and Grand Gedeh
var map = L.map('map',{
    center: [6.5, -8.9],
    zoom: 7
});


// Start loading OSM base layer
// Uses options from "Leaflet.TileLayer.PouchDBCached" plugin to cache tiles for offline use
var tileLayer = L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/NatGeo_World_Map/MapServer/tile/{z}/{y}/{x}', {
    maxZoom: 18,
//    useCache: true,
//    crossOrigin: true
}).addTo(map);


// Declare `legend` global
var legend = L.control({position: 'bottomright'});


// Contains information about the current indicator
var currInd = {
    
    // Basic info
    indID: null,
    indName: null,
    indFormat: null,
    
    // Reference to the relevant Leaflet layer
    indLayer: null,
    
    // `indColors` controls the colors of indicators on the map based on the data provided
    indColors: {
        scale: {1:{},2:{},3:{},4:{},5:{}},
        setScale: function(array) {
            
            // Sort array
            array.sort(function(a,b){return a-b;});
            for (var i=array.length-1; i>=0; i--) {
                if (array[i]===null) {
                    array.splice(i,1);
                }
            }
            
            // Count number of distinct values in array
            var distinctArray = [];
            for (var i=0; i<array.length; i++) {
                if (distinctArray.indexOf(array[i]) === -1) {
                    distinctArray.push(array[i]);
                }
            }
            
            // If array has 5 or fewer distinct values, use those values directly
            if (distinctArray.length <= 5) {
                this.scale[1].bottom = distinctArray[0];
                this.scale[1].top = distinctArray[0];
                this.scale[2].bottom = distinctArray[1];
                this.scale[2].top = distinctArray[1];
                this.scale[3].bottom = distinctArray[2];
                this.scale[3].top = distinctArray[2];
                this.scale[4].bottom = distinctArray[3];
                this.scale[4].top = distinctArray[3];
                this.scale[5].bottom = distinctArray[4];
                this.scale[5].top = distinctArray[4];
                
            // If array has more than 5 distinct values, use quintiles of distinctArray
            } else {
                this.scale[1].bottom = distinctArray[Math.floor(distinctArray.length*0.0)];
                this.scale[1].top = distinctArray[Math.floor(distinctArray.length*0.2)-1];
                this.scale[2].bottom = distinctArray[Math.floor(distinctArray.length*0.2)];
                this.scale[2].top = distinctArray[Math.floor(distinctArray.length*0.4)-1];
                this.scale[3].bottom = distinctArray[Math.floor(distinctArray.length*0.4)];
                this.scale[3].top = distinctArray[Math.floor(distinctArray.length*0.6)-1];
                this.scale[4].bottom = distinctArray[Math.floor(distinctArray.length*0.6)];
                this.scale[4].top = distinctArray[Math.floor(distinctArray.length*0.8)-1];
                this.scale[5].bottom = distinctArray[Math.floor(distinctArray.length*0.8)];
                this.scale[5].top = distinctArray[Math.floor(distinctArray.length*1.0)-1];
            }
            
            
        },
        // Return color corresponding to value, based on which quintile it is in
        returnColor: function(value) {
            if(value!==null) {
                // !!!!! LP: Make the color scale configurable !!!!!
                return value >= this.scale[5].bottom ? '#4D000F' :
                       value >= this.scale[4].bottom ? '#A80022' :
                       value >= this.scale[3].bottom ? '#F03B20' :
                       value >= this.scale[2].bottom ? '#FD8D3C' :
                       value >= this.scale[1].bottom ? '#FFFFB2' :
                       '#ffffff';
            } else {
                return '#ffffff';
            }
        }
    }
};


// "Availability" object to deal with the fact that not all indicators are available at all levels
// !!!!! Set this manually for now; later, pull from server !!!!!
var availability = {
    county: [18,28,25,29,115,116,137,138,139,140,141,142,146],
    district: [18,28,115,116,137,138,139,140,141,142,146],
    communities_CHW: [18,28,115,116,137,138,139,140,141,142,146]
};


// Set URLs global
// contains URLs of sources for GIS coordinates and data
var urls = {
    coordinates: {
        communities_nearFacility: '../../php/scripts/LMD_REST.php/gis_communities_nearFacility/',
        communities_remote: '../../php/scripts/LMD_REST.php/gis_communities_remote/',
        communities_CHW: '../../php/scripts/LMD_REST.php/gis_communities_CHW/',
        district: '../data/districtCoordinates.geoJSON',
        county: '../data/countyCoordinates.geoJSON',
        healthFacility: '../data/healthFacilityCoordinates.geoJSON'
    },
    data: {
        communities_CHW: '../../php/scripts/LMD_REST.php/gis_community_data/',
        district: '../../php/scripts/LMD_REST.php/gis_district_data/',
        county: '../../php/scripts/LMD_REST.php/gis_county_data/'
    },
    indicators: '../../php/scripts/LMD_REST.php/indicators/'
};


// Declare `geoJSON` global container, which contains `GeoJSON` instances
var geoJSON = {};


// Declare `GeoJSON` object
// Each instance represents a single GeoJSON layer
// Assumes two fields in the "properties" object of each feature: 'id' and 'name'
function GeoJSON(url) {
    var self = this;
    this.type = "FeatureCollection";
    this.crs = {
        "type":"name",
        "properties":{"name":"urn:ogc:def:crs:OGC:1.3:CRS84"}
    };
    this.features = [];
    this.url = url;
    
    // Plot layer on map with specified style/options
    // Second argument denotes whether or not this layer contains indicator data (to be plotted)
    this.plot = function(options, indLayer) {
        this.indLayer = L.geoJson(this,options).addTo(map);
        if (indLayer) {
            currInd.indLayer = this.indLayer; // !!!!! Re-examine the structure here !!!!!
        }
    };
    
    // Plot layer on map with specified style/options
    this.unplot = function() {
        map.removeLayer(this.indLayer);
    };
    
    // Add data to `features.indVal` property of each geoJSON feature
    this.resetData = function(data) {
        for (var i=0; i<this.features.length; i++) {
            var id = this.features[i].properties['id'];
            this.features[i].properties.indVal = (data[id]===undefined ? null : data[id]);
        }
    };
    
    // Load GIS data from URL
    this.loadGISData = function(callback) {
        $.ajax({
            url: this.url,
            success: function(data) {
                data = JSON.parse(data);
                for (var key in data) {
                    
                    // Handle communities (from MySQL)
                    // !!!!! This test should only be made once !!!!!
                    if (data[key].communityID) {
                        if (data[key].Y>-500 && data[key].Y<500 && data[key].X>-500 && data[key].X<500) {
                            self.features.push({
                                "type": "Feature",
                                "geometry": {
                                    "type": "Point",
                                    "coordinates": [Number(data[key].X), Number(data[key].Y)]
                                },
                                "properties": {
                                    "id": data[key]['communityID'],
                                    "name": data[key]['name']
                                }
                            });

                        }
                    
                    // Handle others (from static files)
                    } else {
                        // !!!!! hack (see above: "This test should only be made once") !!!!!
                        if(data[key].type === 'Feature') {
                            self.features.push(data[key]);
                        }
                    }
                }
                
                // Run callback function (if it exists)
                if (callback) {
                    callback();
                }
            },
            error: function() {
                console.log('error :/');
            }
        });
    };
    
}


// Declare `ajaxManager` object
// Each instance coordinates AJAX responses when a single response is needed after multiple AJAX calls resolve
// !!!!! Add two more (optional) arguments: timeout and timeoutCallback !!!!!
// !!!!! Consider either (1) refactoring this into a utility object, or (2) using jQuery deferred/promises instead !!!!!
function AjaxManager(target, callback) {
    
    // Constructor
    this.counter = 0;
    this.target = target;
    this.data = {};
    var self = this;
    this.timer = setInterval(function() {
        if (self.counter>=self.target) {
            clearInterval(self.timer);
            callback();
        }
    }, 1000, self);
    
    this.increment = function() {
        this.counter++;
    };
    
    this.stopTimer = function() {
        clearInterval(this.timer);
    };
    
    this.setData = function(key, value) {
        this.data[key] = value;
    };
    
    this.getData = function(key) {
        return this.data[key];
    };
    
};


// Initialize `GeoJSON` objects, within `geoJSON` container object
// Names (community, district, ...) should match options in #toggleMapItems select menu
geoJSON.communities_nearFacility = new GeoJSON(urls.coordinates.communities_nearFacility);
geoJSON.communities_remote = new GeoJSON(urls.coordinates.communities_remote);
geoJSON.ind_communities_CHW = new GeoJSON(urls.coordinates.communities_CHW);
//geoJSON.community = new GeoJSON(urls.coordinates.community);
geoJSON.district = new GeoJSON(urls.coordinates.district);
geoJSON.county = new GeoJSON(urls.coordinates.county);
geoJSON.healthFacility = new GeoJSON(urls.coordinates.healthFacility);


// Load GIS data into geoJSON container object
// When data is done loading, clone objects (via callback) into new objects that will hold indicator data
// !!!!! Freeze buttons on page load; when data are loaded for all three (use an AjaxManager instance), unfreeze buttons !!!!!
geoJSON.communities_nearFacility.loadGISData();
geoJSON.communities_remote.loadGISData();
geoJSON.ind_communities_CHW.loadGISData();
geoJSON.district.loadGISData(function(){
    geoJSON.ind_district = $.extend({}, geoJSON.district);
});
geoJSON.county.loadGISData(function(){
    geoJSON.ind_county = $.extend({}, geoJSON.county);
});
geoJSON.healthFacility.loadGISData();


// Event handlers
$(document).ready(function(){
    
    
    // Add "info box" (a Leaflet control) that shows info when you hover over a feature
    var info = L.control();
    info.onAdd = function (map) {
        this._div = L.DomUtil.create('div', 'info');
        this.update();
        return this._div;
    };
    info.update = function (props) {
        this._div.innerHTML = '<h4>' + (props ? props.name : 'Liberia') + '</h4>' + 
                (props && props.indVal ? currInd.indName + ': ' : '') + 
                (props ? '<b>' + (props.indVal===null ? 'missing' : LMD_utilities.format_number(props.indVal, currInd.indFormat)) + '</b>' : 'Hover over a location');
    };
    info.addTo(map);


    // Create multiselect for "toggle map items"; set style
    $('#toggleMapItems').multiselect({
        header: false,
        noneSelectedText: "Toggle map items",
        selectedText: "Toggle map items",
        
        // Event handler for check/uncheck events
        click: function(event,ui) {
            var item = ui.value;
            
            // Item was cheked
            if (ui.checked) {
                
                switch(item) {

                    // Communities (remote)
                    case 'communities_remote':
                        var options = {
                            pointToLayer: function(feature,latlng){
                                return new L.CircleMarker(latlng, {
                                    color: 'red',
                                    opacity: 0.7,
                                    radius: 2,
                                    fillOpacity: 0.5
                                });
                            },
                            onEachFeature: function(feature,layer) {
                                onEachFeature(feature,layer,item,false);
                            }
                        };
                        break;

                    // Communities (near-facility)
                    case 'communities_nearFacility':
                        var options = {
                            pointToLayer: function(feature,latlng){
                                return new L.CircleMarker(latlng, {
                                    color: '#3DB931',
                                    opacity: 0.7,
                                    radius: 2,
                                    fillOpacity: 0.5
                                });
                            },
                            onEachFeature: function(feature,layer) {
                                onEachFeature(feature,layer,item,false);
                            }
                        };
                        break;

                    // Counties
                    case 'county':
                        var options = {
                            style: {
                                color: '#562F10',
                                weight: 2,
                                opacity: 1,
                                fill: true,
                                fillOpacity: 0.01,
                                dashArray: "15,5"
                            },
                            onEachFeature: function(feature,layer) {
                                onEachFeature(feature,layer,item,true,{
                                    weight: 3,
                                    dashArray: ''
                                });
                            }
                        };
                        break;

                    // Districts
                    case 'district':
                        var options = {
                            style: {
                                color: '#555',
                                weight: 1,
                                opacity: 1,
                                fill: true,
                                fillOpacity: 0.01,
                                dashArray: "3"
                            },
                            onEachFeature: function(feature,layer) {
                                onEachFeature(feature,layer,item,true,{
                                    weight: 2,
                                    dashArray: ''
                                });
                            }
                        };
                        break;

                    // Health facilities
                    case 'healthFacility':
                        var options = {
                            pointToLayer: function(feature,latlng){
                                return L.marker(latlng,{
                                    icon: L.icon({
                                        iconUrl: '../images/icon_healthFacility.png',
                                        iconSize: [12,12]
                                    })
                                });
                            },
                            onEachFeature: function (feature,layer) {
                                layer.on({
                                    mouseover: function() {
                                        info.update(layer.feature.properties);
                                    },
                                    mouseout: function() {
                                        info.update();
                                    }
                                });
                            }
                        };
                        break;
                }

                geoJSON[item].plot(options, false);
                
            // Item was unchecked
            } else {
                
                // code
                geoJSON[item].unplot();
                
            }
            
        },
        open: function() { console.log('opened'); } // close, beforeopen, beforeclose
    });
    
    
    // Style #toggleMapItems multiselect
    $('#toggleMapItems_ms').addClass('btn btn-primary');
    $('#toggleMapItems_ms, .ui-multiselect-menu').css('width','');
    
    
    // Handle selection of indicator / value combination
    $('.indChange').change(function(){
        
        var select_indicator = $('#select_indicator').val();
        var select_level = $('#select_level').val();
        var select_period = $('#select_period').val();
        
        // After indicator is selected, enable "2. Select level" dropdown and update availability
        $('#select_level').prop('disabled',false);
        $("#select_level option").prop('disabled',false);
        for (var key in availability) {
            if(availability[key].indexOf(Number(select_indicator))===-1) {
                $("#select_level option[value='" + key + "']").prop('disabled',true);
            }
//            console.log(availability[key]); // [1,2,3,5]
//            console.log(key); // district
            
        }
//        console.log($("#select_level option[value='county']").val());
//        console.log(availability['county'].indexOf(Number(select_indicator))!==-1);
//        console.log(availability['district'].indexOf(Number(select_indicator))!==-1);
//        console.log(availability['community'].indexOf(Number(select_indicator))!==-1);
        
        
        // When both "indicator" and "level" are selected, enable "3. Select period" dropdown
        if (select_indicator!=='1. Select indicator' && select_level!=='2. Select level') {
            $('#select_period').prop('disabled',false);
        }
        
        // Once all three dropdowns have been selected, proceed
        if(select_indicator!=='1. Select indicator' && select_level!=='2. Select level' && select_period!=='3. Select period') {
            
            // Disable dropdowns
            $('#select_indicator').prop('disabled',true);
            $('#select_level').prop('disabled',true);
            $('#select_period').prop('disabled',true);
            
            // Add overlay
            $('.leaflet-container').css('opacity','0.3');

            // Set AjaxManager and callback
            var ajaxManager_A = new AjaxManager(2, function() {

                // Parse indicator metadata (data1); update currInd
                var data1 = JSON.parse(ajaxManager_A.getData('data1'));
                currInd.indID = data1.indID;
                currInd.indFormat = data1.indFormat;
                currInd.indName = data1.indName;

                // Parse indicator data (data2)
                var data2 = JSON.parse(ajaxManager_A.getData('data2'));
                var dataObj = {};
                var dataArr = [];
                for (var i=0; i<data2.length; i++) {
                    var key = data2[i]['id'];
                    var value = data2[i]['indVal'];
                    dataObj[key] = value;
                    dataArr.push( value===null ? null : Number(value) ); // !!!!! this will have to be modified once categorical data is added !!!!!
                }

                // Set indColors
                currInd.indColors.setScale(dataArr);

                // Add data to geoJSON object
                var item = 'ind_' + select_level;
                geoJSON[item].resetData(dataObj);

                // Remove existing indicator layer from map
                if (currInd.indLayer) {
                    map.removeLayer(currInd.indLayer);
                }

                // Set up layer styles
                switch(item) {

                    // Communities
                    case 'ind_communities_CHW':

                        // !!!!! Control flow: remove communities if exists; otherwise, check the "communities" box !!!!!

                        var options = {
                            pointToLayer: function(feature,latlng) {
                                return new L.CircleMarker(latlng, {
                                    opacity: 0.7,
                                    radius: 3,
                                    fillOpacity: 0.5
                                });
                            },
                            style: function(feature) {
                                return {
                                    color: currInd.indColors.returnColor(feature.properties.indVal)
                                };
                            },
                            onEachFeature: function(feature,layer) {
                                onEachFeature(feature,layer,item,false);
                            }
                        };
                        break;

                    // Counties and districts
                    default:
                        var options = {
                            style: function(feature) {
                                return {
                                    color: currInd.indColors.returnColor(feature.properties.indVal),
                                    fillColor: currInd.indColors.returnColor(feature.properties.indVal),
                                    weight: 1,
                                    opacity: 0.8,
                                    fill: true,
                                    fillOpacity: currInd.indColors.returnColor(feature.properties.indVal)==='#ffffff' ? 0.1 : 0.8,
                                    dashArray: "3"
                                };
                            },
                            onEachFeature: function(feature,layer) {
                                onEachFeature(feature,layer,item,true,{
                                    color: '#555',
                                    weight: 5,
                                    dashArray: ''
                                });
                            }
                        };
                        break;
                }

                // Plot layer on map
                geoJSON[item].plot(options, true);


                // Update legend
                updateLegend(map, legend);

                // Remove overlay
                $('.leaflet-container').css('opacity','1');

            });


            // Load indicator metadata from server (ajaxManager_A)
            $.ajax({
                url: urls.indicators + select_indicator,
                success: function(data) {
                    ajaxManager_A.setData('data1', data);
                    ajaxManager_A.increment();
                },
                error: function() {
                    alert("The database could not be reached. Please check your internet connection and try again later.");
                    $('.leaflet-container').css('opacity','1');
                }
            });

            // Load indicator data from server (ajaxManager_A)
            $.ajax({
                url: urls.data[select_level] + select_period + '/' + select_indicator,
                success: function(data) {
                    ajaxManager_A.setData('data2', data);
                    ajaxManager_A.increment();
                },
                error: function() {
                    alert("The database could not be reached. Please check your internet connection and try again later.");
                    $('.leaflet-container').css('opacity','1');
                }
            });

        }
    });
    
    
    //
    $('#indReset').click(function(){
        
        // Remove indicator layer
        if (currInd.indLayer) {
            map.removeLayer(currInd.indLayer);
        }
        
        // Reset selects
        $('#select_indicator').val('1. Select indicator');
        $('#select_level').val('2. Select level');
        $('#select_period').val('3. Select period');
        $('#select_indicator').prop('disabled',false);
        $('#select_level').prop('disabled',true);
        $('#select_period').prop('disabled',true);
        
    });
    
    
    // Select zoom
    $('#select_zoom').change(function(){
        switch($(this).val()) {
            
            case 'RC':
                map.setView([5.9, -9.4], 9);
                break;

            case 'GG':
                map.setView([6.0, -8.1], 9);
                break;

            case 'LIB':
                map.setView([6.5, -8.9], 7);
                break;
        }
    });


    // Select zoom
    $('#select_basemap').change(function(){
        
        var urlTemplate = $(this).val();
        tileLayer.setUrl(urlTemplate);
        
    });
    
    
    // Function 
    function onEachFeature(feature, layer, item, clickZoom, hoverStyle) {
        layer.on({
            // Update info box and set style to hoverStyle
            mouseover: function(e) {
                var layer = e.target;
                info.update(layer.feature.properties);
                if (hoverStyle) {
                    layer.setStyle(hoverStyle);
                }
            },
            // Reset info box and style
            mouseout: function(e) {
                info.update();
                geoJSON[item].indLayer.resetStyle(e.target);
            },
            // Zoom map to feature
            click: function(e) {
                if (clickZoom) {
                    map.fitBounds(e.target.getBounds());
                }
            }
        });
    }


    // Resets legend based on current state of `indColors` object
    function updateLegend(map, legend) {

        try {
            legend.removeFrom(map);
        } catch(e) {} finally {

            legend.onAdd = function () {

                var div = L.DomUtil.create('div', 'info legend'),
                    label, from, to, labels = [];

                label = '<i style="background:#FFF"></i> missing';
                labels.push(label);
                for (var i=0; i<5; i++) {
                    from = LMD_utilities.format_number(currInd.indColors.scale[i+1].bottom, currInd.indFormat);
                    to = LMD_utilities.format_number(currInd.indColors.scale[i+1].top, currInd.indFormat);
                        label = '<i style="background:' + currInd.indColors.returnColor(currInd.indColors.scale[i+1].bottom) + '"></i> ' +
                            (from===to ? from : from + '&nbsp;&ndash;&nbsp;' + to);
                    if (from!=='') {
                        labels.push(label);
                    }
                }

                div.innerHTML = labels.join('<br>');
                return div;
            };

            legend.addTo(map);

        }
    }
    
});


// !!!!! To recycle !!!!!
//onEachFeature: function (feature, layer) {
//    // !!!!! Unused !!!!!
//    layer.bindPopup(feature.properties.name + ': ' + feature.properties.indVal);
//    
//    layer.on({
//        mouseover: function(e) {
//            var layer = e.target;
//            info.update(layer.feature.properties);
//            layer.setStyle({
//                weight: 3,
//                color: '#666',
//                dashArray: '',
//                fillOpacity: 0.7
//            });
//            // !!!!! this was removed !!!!!
//            if (!L.Browser.ie && !L.Browser.opera) {
//                layer.bringToFront();
//            }
//        },
//        mouseout: function(e) {
//            info.update();
//            currInd.indLayer.resetStyle(e.target);
//        },
//        click: function() {
//            // !!!!! this was removed !!!!!
//            map.fitBounds(e.target.getBounds());
//        }
//    });
//}

//// Counties or districts
//// !!!!! Transfer this code to other event handler !!!!!
//default:
//    var options = {
//        style: function(feature) {
//            return {
//                weight: 1,
//                opacity: 1,
//                color: 'grey',
//                dashArray: '3',
//                fillOpacity: 0.4,
//                fillColor: currInd.indColors.returnColor(feature.properties.indVal)
//            };
//        },
//        onEachFeature: function (feature, layer) {
//            layer.on({
//                mouseover: function(e) {
//                    var layer = e.target;
//                    info.update(layer.feature.properties);
//                    layer.setStyle({
//                        weight: 3,
//                        color: '#666',
//                        dashArray: '',
//                        fillOpacity: 0.7
//                    });
//                    if (!L.Browser.ie && !L.Browser.opera) {
//                        layer.bringToFront();
//                    }
//                },
//                mouseout: function(e) {
//                    info.update();
//                    currInd.indLayer.resetStyle(e.target);
//                },
//                click: function() {
//                    map.fitBounds(e.target.getBounds());
//                }
//            });
//        }
//    };
//    break;
