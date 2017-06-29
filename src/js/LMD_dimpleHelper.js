// Module:          LMD_dimpleHelper.js
// Author:          Avi Kenny
// Last update:     2016-03-27
// Dependencies:    D3.js, Dimple.js
// Purpose:         Helper API to create charts

var LMD_dimpleHelper = (function(){

    // Monthly line graph
    function createChart(params){
        
        // If chart size is NULL or 0, set defaults
        params.size.x = params.size.x ? params.size.x : 590;
        params.size.y = params.size.y ? params.size.y : 380;

        // Create new dimple SVG; set data; create new chart object
        var svg = dimple.newSvg("#" + params.targetDiv, params.size.x, params.size.y);
        var myChart = new dimple.chart(svg, params.data);

        // Set color scheme
        myChart.defaultColors = [];
        var defaultColorArray = ["#F79646", "#9BBB59", "#4BACC6", "#C0504D", "#8064A2"];
        if (params.colors !== "default") {
            for (var i=0; i<params.colors.length; i++) {
                myChart.defaultColors.push(new dimple.color(params.colors[i]));
            }
        } else {
            for (var i=0; i<defaultColorArray.length; i++) {
                myChart.defaultColors.push(new dimple.color(defaultColorArray[i]));
            }
        }

        // Format chart
        myChart.setBounds(55, 30, params.size.x-75, params.size.y-75);
        if (params.type==="line" || params.type==="bar") {
            var x = myChart.addTimeAxis("x", params.xyVars.x, "%Y-%m-%d", "%b '%y");
            x.timePeriod = d3.time.months;
            x.timeInterval = params.timeInterval;
            
//            !!!!! temporary hack !!!!!
//            if (params.overrideMin !== "") { x.overrideMin = new Date(params.overrideMin); }
//            if (params.overrideMax !== "") { x.overrideMax = new Date(params.overrideMax); }
            
            var y = myChart.addMeasureAxis("y", params.xyVars.y);
        } else if (params.type==="pie") {
            var x = myChart.addCategoryAxis("x", "Month");
            myChart.addCategoryAxis("y", "");
            myChart.addMeasureAxis("p", "Value");
            var pies = myChart.addSeries(params.cut, dimple.plot.pie);
            pies.radius = 25;
            myChart.addLegend(140, 10, 330, 20, "right"); // !!!!! this needs to be variablized !!!!!
        }
        
        if (params.type==="line" || params.type==="bar") {
            
            // Add axis titles
            if (params.axisTitles && params.axisTitles.x) {
                x.title = params.axisTitles.x;
            } else {
                x.title = "";
            }
            if (params.axisTitles && params.axisTitles.y) {
                y.title = params.axisTitles.y;
            } else {
                y.title = "";
            }

            // Add tick formats
            if (params.tickFormat && params.tickFormat.x) {
                x.tickFormat = params.tickFormat.x;
            }
            if (params.tickFormat && params.tickFormat.y) {
                y.tickFormat = params.tickFormat.y;
            }

            // Add y-axis min/max
            if (params.axisValues && params.axisValues.min) {
                y.overrideMin = params.axisValues.min;
            }
            if (params.axisValues && params.axisValues.max) {
                y.overrideMax = params.axisValues.max;
            }

            // Add series; add legend; draw chart
            if (params.type==="line") {
                var mySeries = myChart.addSeries(params.cut, dimple.plot.line);
                
                // Assign specific colors to specific cuts (!!!!! This should be passed in as a parameter, possibly a global set of key-value pairs !!!!!)
                var myArr = [{"Konobo":"#9BBB59"},{"Gboe-Ploe":"#C0504D"},{"Grand Gedeh":"#F79646"},{"Rivercess":"#4F81BD"},{"Rivercess c1":"#4BACC6"},{"Rivercess c2":"#8064A2"},{"Monrovia":"#202020"}];
                for (el in myArr) {
                    var cut = Object.keys(myArr[el])[0];
                    var color = myArr[el][cut];
                    myChart.assignColor(cut,color);
                }
                
                // Order by cut
                mySeries.addOrderRule(params.cut,true);
                
            } else if (params.type==="bar") {
                myChart.addSeries(params.cut, dimple.plot.bar);
            }
            if (params.legend !== "") {
                myChart.addLegend(65, 10, 510, 20, params.legend);
            }
        }
        myChart.draw();
    }

    // LMD_dimpleHelper API
    return {
        createChart: createChart
    };

})();
