var svg = dimple.newSvg("#dashboard_12", 590, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Month":"Sep '12", "Measure":"IMCI", "Access":.41, "Order":1 },
    { "Month":"Dec '12", "Measure":"IMCI", "Access":.41, "Order":2 },
    { "Month":"Mar '13", "Measure":"IMCI", "Access":.5, "Order":3 },
    { "Month":"Jun '13", "Measure":"IMCI", "Access":.5, "Order":4 },
    { "Month":"Sep '13", "Measure":"IMCI", "Access":.74, "Order":5 },
    { "Month":"Dec '13", "Measure":"IMCI", "Access":.74, "Order":6 },
    { "Month":"Mar '14", "Measure":"IMCI", "Access":1, "Order":7 },
    { "Month":"Jun '14", "Measure":"IMCI", "Access":1, "Order":8 },
    { "Month":"Sep '14", "Measure":"IMCI", "Access":1, "Order":9 },
    { "Month":"Dec '14", "Measure":"IMCI", "Access":1, "Order":10 },
    { "Month":"Sep '12", "Measure":"Big Belly", "Access":.41, "Order":1 },
    { "Month":"Dec '12", "Measure":"Big Belly", "Access":.5, "Order":2 },
    { "Month":"Mar '13", "Measure":"Big Belly", "Access":.5, "Order":3 },
    { "Month":"Jun '13", "Measure":"Big Belly", "Access":.5, "Order":4 },
    { "Month":"Sep '13", "Measure":"Big Belly", "Access":.5, "Order":5 },
    { "Month":"Dec '13", "Measure":"Big Belly", "Access":.74, "Order":6 },
    { "Month":"Mar '14", "Measure":"Big Belly", "Access":.74, "Order":7 },
    { "Month":"Jun '14", "Measure":"Big Belly", "Access":.74, "Order":8 },
    { "Month":"Sep '14", "Measure":"Big Belly", "Access":.74, "Order":9 },
    { "Month":"Dec '14", "Measure":"Big Belly", "Access":.74, "Order":10 },
    { "Month":"Sep '12", "Measure":"Infant", "Access":.41, "Order":1 },
    { "Month":"Dec '12", "Measure":"Infant", "Access":.41, "Order":2 },
    { "Month":"Mar '13", "Measure":"Infant", "Access":.41, "Order":3 },
    { "Month":"Jun '13", "Measure":"Infant", "Access":.5, "Order":4 },
    { "Month":"Sep '13", "Measure":"Infant", "Access":.5, "Order":5 },
    { "Month":"Dec '13", "Measure":"Infant", "Access":.74, "Order":6 },
    { "Month":"Mar '14", "Measure":"Infant", "Access":.74, "Order":7 },
    { "Month":"Jun '14", "Measure":"Infant", "Access":.74, "Order":8 },
    { "Month":"Sep '14", "Measure":"Infant", "Access":.74, "Order":9 },
    { "Month":"Dec '14", "Measure":"Infant", "Access":.74, "Order":10 },
    { "Month":"Sep '12", "Measure":"FP", "Access":.41, "Order":1 },
    { "Month":"Dec '12", "Measure":"FP", "Access":.41, "Order":2 },
    { "Month":"Mar '13", "Measure":"FP", "Access":.41, "Order":3 },
    { "Month":"Jun '13", "Measure":"FP", "Access":.41, "Order":4 },
    { "Month":"Sep '13", "Measure":"FP", "Access":.5, "Order":5 },
    { "Month":"Dec '13", "Measure":"FP", "Access":.5, "Order":6 },
    { "Month":"Mar '14", "Measure":"FP", "Access":.5, "Order":7 },
    { "Month":"Jun '14", "Measure":"FP", "Access":.5, "Order":8 },
    { "Month":"Sep '14", "Measure":"FP", "Access":0, "Order":9 },
    { "Month":"Dec '14", "Measure":"FP", "Access":0, "Order":10 },
    { "Month":"Sep '12", "Measure":"NCD", "Access":0, "Order":1 },
    { "Month":"Dec '12", "Measure":"NCD", "Access":0, "Order":2 },
    { "Month":"Mar '13", "Measure":"NCD", "Access":0, "Order":3 },
    { "Month":"Jun '13", "Measure":"NCD", "Access":0, "Order":4 },
    { "Month":"Sep '13", "Measure":"NCD", "Access":0, "Order":5 },
    { "Month":"Dec '13", "Measure":"NCD", "Access":.09, "Order":6 },
    { "Month":"Mar '14", "Measure":"NCD", "Access":.09, "Order":7 },
    { "Month":"Jun '14", "Measure":"NCD", "Access":0, "Order":8 },
    { "Month":"Sep '14", "Measure":"NCD", "Access":0, "Order":9 },
    { "Month":"Dec '14", "Measure":"NCD", "Access":0, "Order":10 }
];

var myChart = new dimple.chart(svg, data);

// Set color scheme: green
myChart.defaultColors = [
    new dimple.color("#9BBB59"),
    new dimple.color("#4BACC6"),
    new dimple.color("#F79646"),
    new dimple.color("#C0504D"),
    new dimple.color("#8064A2")
];

myChart.setBounds(60, 30, 505, 305);
var x = myChart.addCategoryAxis("x", "Month");
x.addOrderRule("Order");
var y = myChart.addMeasureAxis("y", "Access");
y.tickFormat = "%";
myChart.addSeries("Measure", dimple.plot.line);
myChart.addLegend(60, 10, 500, 20, "right");
myChart.draw();
