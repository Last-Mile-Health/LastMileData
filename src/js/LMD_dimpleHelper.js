var LMD_dimpleHelper = (function(){

    // Monthly line graph
    function createChart(params){

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
            
            // !!!!! temporary hack !!!!!
//            if (params.overrideMin !== "") { x.overrideMin = new Date(params.overrideMin); }
//            if (params.overrideMax !== "") { x.overrideMax = new Date(params.overrideMax); }
            // !!!!! temporary hack !!!!!
            
            var y = myChart.addMeasureAxis("y", params.xyVars.y);
        } else if (params.type==="pie") {
            var x = myChart.addCategoryAxis("x", "Month");
            myChart.addCategoryAxis("y", "");
            myChart.addMeasureAxis("p", "Value");
            var pies = myChart.addSeries(params.cut, dimple.plot.pie); // !!!!! this needs to be variablized !!!!!
            pies.radius = 25;
            myChart.addLegend(140, 10, 330, 20, "right"); // !!!!! this needs to be variablized !!!!!
        }
        
        if (params.type==="line" || params.type==="bar") {
            // Add axis titles
            if (params.axisTitles && params.axisTitles.x) {
                x.title = params.axisTitles.x;
            }
            if (params.axisTitles && params.axisTitles.y) {
                y.title = params.axisTitles.y;
            }

            // Add tick formats
            if (params.tickFormat && params.tickFormat.x) {
                x.tickFormat = params.tickFormat.x;
            }
            if (params.tickFormat && params.tickFormat.y) {
                y.tickFormat = params.tickFormat.y;
            }

            // Add series; add legend; draw chart
            if (params.type==="line") {
                myChart.addSeries(params.cut, dimple.plot.line);
            } else if (params.type==="bar") {
                myChart.addSeries(params.cut, dimple.plot.bar);
            }
            if (params.legend !== "") {
                myChart.addLegend(65, 10, 510, 20, params.legend);
            }
        }
        myChart.draw();
    }

    // Description
    return {
        createChart: createChart
    };

})();
