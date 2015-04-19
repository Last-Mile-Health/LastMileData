var LMD_dimpleHelper = (function(){

    // Monthly line graph
    function lineGraph_monthly(params){

        // Create new dimple SVG; set data; create new chart object
        var svg = dimple.newSvg("#" + params.targetDiv, params.size.x, params.size.y);
        var data = params.data;
        var myChart = new dimple.chart(svg, data);

        // Set color scheme
        myChart.defaultColors = [];
        for (i=0; i<params.colors.length; i++) {
            myChart.defaultColors.push(new dimple.color(params.colors[i]));
        }

        // Format chart
        myChart.setBounds(55, 30, params.size.x-75, params.size.y-75);
        var x = myChart.addTimeAxis("x", params.xyVars.x, "%Y-%m-%d", "%b '%y");
        x.timePeriod = d3.time.months;
        x.timeInterval = params.timeInterval;
        var y = myChart.addMeasureAxis("y", params.xyVars.y);
        
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
        myChart.addSeries(params.multLine, dimple.plot.line);
        if (params.legend) {
            myChart.addLegend(65, 10, 510, 20, params.legend);
        }
        myChart.draw();
    }

    // Description
    return {
        lineGraph_monthly: lineGraph_monthly
//        pieGraph_monthly: pieGraph_monthly
    };

})();
