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

        // Create new dimple SVG; set data; create/format new chart object
        var svg = dimple.newSvg("#" + params.targetDiv, params.size.x, params.size.y);
        var myChart = new dimple.chart(svg, params.data);
        myChart.setBounds(55, 30, params.size.x-75, params.size.y-75); // !!!!! need to variablize the first two parameters !!!!!

        // Set default color scheme
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
        
        // Assign specific colors to specific "cuts" (defined by chart labels)
        // !!!!! Eventually, this should be set through some sort of interface !!!!!
        var myArr = [{"Actual":"#9BBB59"},{"Expected":"#F79646"},{"Konobo":"#9BBB59"},{"Gboe-Ploe":"#C0504D"},{"Grand Bassa":"#C0504D"},{"Grand Gedeh":"#F79646"},{"Rivercess":"#4BACC6"},{"Rivercess c1":"#4BACC6"},{"Rivercess c2":"#8064A2"},{"Monrovia":"#202020"}];
        for (el in myArr) {
            if (params.type!=="horizontal bar") {       // !!!!! Hack !!!!!
                var cut = Object.keys(myArr[el])[0];
                var color = myArr[el][cut];
                myChart.assignColor(cut,color);
            }                                           // !!!!! Hack !!!!!
        }

        // Format line charts
        if (params.type==="line") {
            var x = myChart.addTimeAxis("x", params.xyVars.x, "%Y-%m-%d", "%b '%y");
            x.timePeriod = d3.time.months;
            x.timeInterval = params.timeInterval;
            var y = myChart.addMeasureAxis("y", params.xyVars.y);
            y.tickFormat = params.tickFormat
            var mySeries = myChart.addSeries(params.cut, dimple.plot.line);
            mySeries.addOrderRule(params.cut,true);
        }
        
        // Format stacked bar charts
        if (params.type==="stacked bar") {
            var x = myChart.addTimeAxis("x", params.xyVars.x, "%Y-%m-%d", "%b '%y");
            x.timePeriod = d3.time.months;
            x.timeInterval = params.timeInterval;
            var y = myChart.addMeasureAxis("y", params.xyVars.y);
            y.tickFormat = params.tickFormat
            var mySeries = myChart.addSeries(params.cut, dimple.plot.bar);
            y.addOrderRule(params.cut,true);
        }
        
        // Format bar charts (vertical)
        else if (params.type==="bar") {
            var x = myChart.addCategoryAxis("x", "Cut");
            var y = myChart.addMeasureAxis("y", params.xyVars.y);
            y.tickFormat = params.tickFormat
            var mySeries = myChart.addSeries(params.cut, dimple.plot.bar);
            x.addOrderRule(params.cut,true);
        }
        
        // Format bar charts (horizontal)
        else if (params.type==="horizontal bar") {
            var x = myChart.addMeasureAxis("x", params.xyVars.y);
            x.tickFormat = params.tickFormat
            var y = myChart.addCategoryAxis("y", "Cut");
            var mySeries = myChart.addSeries("Cut", dimple.plot.bar);
            y.addOrderRule(params.cut,true);
            myChart.x = myChart.x + 40;         // !!!!! Hack !!!!!
            myChart.width = myChart.width - 40; // !!!!! Hack !!!!!
        }
        
        // Format floating bar charts (horizontal)
        // !!!!! Add vertical floating bar chars !!!!!
        else if (params.type==="horizontal floating bar") {
            var x = myChart.addMeasureAxis("x", params.xyVars.y);
            x.tickFormat = params.tickFormat
            var y = myChart.addCategoryAxis("y", "Cut");
            var mySeries = myChart.addSeries(["Cut","Level"], dimple.plot.bar);
            mySeries.stacked = false;
            y.addOrderRule(params.cut,true);
            myChart.x = myChart.x + 40;         // !!!!! Hack !!!!!
            myChart.width = myChart.width - 40; // !!!!! Hack !!!!!
        }
        
        // Format pie charts (!!!!! check this code; not currently used !!!!!)
        else if (params.type==="pie") {
            var x = myChart.addCategoryAxis("x", params.xyVars.x);
            var y = null;
            myChart.addCategoryAxis("y", "");
            myChart.addMeasureAxis("p", "Value");
            var pies = myChart.addSeries(params.cut, dimple.plot.pie);
            pies.radius = 25;
            myChart.addLegend(140, 10, 330, 20, "right"); // !!!!! these parameters need to be variablized !!!!!
        }
        
        // Add x-axis title
        x.title = params.chart_only_display_last_month ? params.data[0].Month : "Date";

        // Add y-axis min/max
        if (params.axisValues && params.axisValues.min) {
            y.overrideMin = params.axisValues.min;
        }
        if (params.axisValues && params.axisValues.max) {
            y.overrideMax = params.axisValues.max;
        }
        
        // Add x-axis min/max
        // !!!!! TO DO !!!!!

        // Draw legend
        if (params.legend !== "" && params.type!=="bar" && params.type!=="horizontal bar") {
            myChart.addLegend(65, 10, 510, 20, params.legend);
        }

        // Draw chart
        myChart.draw();
        
    }

    // LMD_dimpleHelper API
    return {
        createChart: createChart
    };

})();
