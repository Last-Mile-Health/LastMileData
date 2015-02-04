var svg = dimple.newSvg("#dashboard_12", 590, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Month":"Sep '12", "Measure":"IMCI", "Access":.40, "Order":1 },
    { "Month":"Oct '12", "Measure":"IMCI", "Access":.40, "Order":2 },
    { "Month":"Nov '12", "Measure":"IMCI", "Access":.40, "Order":3 },
    { "Month":"Dec '12", "Measure":"IMCI", "Access":.40, "Order":4 },
    { "Month":"Jan '13", "Measure":"IMCI", "Access":.48, "Order":5 },
    { "Month":"Feb '13", "Measure":"IMCI", "Access":.48, "Order":6 },
    { "Month":"Mar '13", "Measure":"IMCI", "Access":.48, "Order":7 },
    { "Month":"Apr '13", "Measure":"IMCI", "Access":.48, "Order":8 },
    { "Month":"May '13", "Measure":"IMCI", "Access":.48, "Order":9 },
    { "Month":"Jun '13", "Measure":"IMCI", "Access":.48, "Order":10 },
    { "Month":"Jul '13", "Measure":"IMCI", "Access":.75, "Order":11 },
    { "Month":"Aug '13", "Measure":"IMCI", "Access":.75, "Order":12 },
    { "Month":"Sep '13", "Measure":"IMCI", "Access":.75, "Order":13 },
    { "Month":"Oct '13", "Measure":"IMCI", "Access":.75, "Order":14 },
    { "Month":"Nov '13", "Measure":"IMCI", "Access":.75, "Order":15 },
    { "Month":"Dec '13", "Measure":"IMCI", "Access":.75, "Order":16 },
    { "Month":"Jan '14", "Measure":"IMCI", "Access":1, "Order":17 },
    { "Month":"Feb '14", "Measure":"IMCI", "Access":1, "Order":18 },
    { "Month":"Mar '14", "Measure":"IMCI", "Access":1, "Order":19 },
    { "Month":"Apr '14", "Measure":"IMCI", "Access":1, "Order":20 },
    { "Month":"May '14", "Measure":"IMCI", "Access":1, "Order":21 },
    { "Month":"Jun '14", "Measure":"IMCI", "Access":1, "Order":22 },
    { "Month":"Jul '14", "Measure":"IMCI", "Access":1, "Order":23 },
    { "Month":"Aug '14", "Measure":"IMCI", "Access":1, "Order":24 },
    { "Month":"Sep '14", "Measure":"IMCI", "Access":1, "Order":25 },
    { "Month":"Oct '14", "Measure":"IMCI", "Access":1, "Order":26 },
    { "Month":"Nov '14", "Measure":"IMCI", "Access":1, "Order":27 },
    { "Month":"Dec '14", "Measure":"IMCI", "Access":1, "Order":28 },
    { "Month":"Jan '15", "Measure":"IMCI", "Access":1, "Order":29 },
    { "Month":"Sep '12", "Measure":"Pregnancy/Infant Tracking", "Access":0, "Order":1 },
    { "Month":"Oct '12", "Measure":"Pregnancy/Infant Tracking", "Access":0, "Order":2 },
    { "Month":"Nov '12", "Measure":"Pregnancy/Infant Tracking", "Access":.14, "Order":3 },
    { "Month":"Dec '12", "Measure":"Pregnancy/Infant Tracking", "Access":.14, "Order":4 },
    { "Month":"Jan '13", "Measure":"Pregnancy/Infant Tracking", "Access":.14, "Order":5 },
    { "Month":"Feb '13", "Measure":"Pregnancy/Infant Tracking", "Access":.14, "Order":6 },
    { "Month":"Mar '13", "Measure":"Pregnancy/Infant Tracking", "Access":.14, "Order":7 },
    { "Month":"Apr '13", "Measure":"Pregnancy/Infant Tracking", "Access":.14, "Order":8 },
    { "Month":"May '13", "Measure":"Pregnancy/Infant Tracking", "Access":.14, "Order":9 },
    { "Month":"Jun '13", "Measure":"Pregnancy/Infant Tracking", "Access":.14, "Order":10 },
    { "Month":"Jul '13", "Measure":"Pregnancy/Infant Tracking", "Access":.14, "Order":11 },
    { "Month":"Aug '13", "Measure":"Pregnancy/Infant Tracking", "Access":.14, "Order":12 },
    { "Month":"Sep '13", "Measure":"Pregnancy/Infant Tracking", "Access":.14, "Order":13 },
    { "Month":"Oct '13", "Measure":"Pregnancy/Infant Tracking", "Access":.14, "Order":14 },
    { "Month":"Nov '13", "Measure":"Pregnancy/Infant Tracking", "Access":.14, "Order":15 },
    { "Month":"Dec '13", "Measure":"Pregnancy/Infant Tracking", "Access":.58, "Order":16 },
    { "Month":"Jan '14", "Measure":"Pregnancy/Infant Tracking", "Access":.58, "Order":17 },
    { "Month":"Feb '14", "Measure":"Pregnancy/Infant Tracking", "Access":.58, "Order":18 },
    { "Month":"Mar '14", "Measure":"Pregnancy/Infant Tracking", "Access":.58, "Order":19 },
    { "Month":"Apr '14", "Measure":"Pregnancy/Infant Tracking", "Access":.58, "Order":20 },
    { "Month":"May '14", "Measure":"Pregnancy/Infant Tracking", "Access":.58, "Order":21 },
    { "Month":"Jun '14", "Measure":"Pregnancy/Infant Tracking", "Access":.58, "Order":22 },
    { "Month":"Jul '14", "Measure":"Pregnancy/Infant Tracking", "Access":.58, "Order":23 },
    { "Month":"Aug '14", "Measure":"Pregnancy/Infant Tracking", "Access":.58, "Order":24 },
    { "Month":"Sep '14", "Measure":"Pregnancy/Infant Tracking", "Access":.58, "Order":25 },
    { "Month":"Oct '14", "Measure":"Pregnancy/Infant Tracking", "Access":.58, "Order":26 },
    { "Month":"Nov '14", "Measure":"Pregnancy/Infant Tracking", "Access":.58, "Order":27 },
    { "Month":"Dec '14", "Measure":"Pregnancy/Infant Tracking", "Access":.58, "Order":28 },
    { "Month":"Jan '15", "Measure":"Pregnancy/Infant Tracking", "Access":.58, "Order":29 },
    { "Month":"Sep '12", "Measure":"Family Planning", "Access":.40, "Order":1 },
    { "Month":"Oct '12", "Measure":"Family Planning", "Access":.40, "Order":2 },
    { "Month":"Nov '12", "Measure":"Family Planning", "Access":.40, "Order":3 },
    { "Month":"Dec '12", "Measure":"Family Planning", "Access":.40, "Order":4 },
    { "Month":"Jan '13", "Measure":"Family Planning", "Access":.40, "Order":5 },
    { "Month":"Feb '13", "Measure":"Family Planning", "Access":.40, "Order":6 },
    { "Month":"Mar '13", "Measure":"Family Planning", "Access":.40, "Order":7 },
    { "Month":"Apr '13", "Measure":"Family Planning", "Access":.40, "Order":8 },
    { "Month":"May '13", "Measure":"Family Planning", "Access":.48, "Order":9 },
    { "Month":"Jun '13", "Measure":"Family Planning", "Access":.48, "Order":10 },
    { "Month":"Jul '13", "Measure":"Family Planning", "Access":.48, "Order":11 },
    { "Month":"Aug '13", "Measure":"Family Planning", "Access":.48, "Order":12 },
    { "Month":"Sep '13", "Measure":"Family Planning", "Access":.48, "Order":13 },
    { "Month":"Oct '13", "Measure":"Family Planning", "Access":.48, "Order":14 },
    { "Month":"Nov '13", "Measure":"Family Planning", "Access":.48, "Order":15 },
    { "Month":"Dec '13", "Measure":"Family Planning", "Access":.48, "Order":16 },
    { "Month":"Jan '14", "Measure":"Family Planning", "Access":.48, "Order":17 },
    { "Month":"Feb '14", "Measure":"Family Planning", "Access":.48, "Order":18 },
    { "Month":"Mar '14", "Measure":"Family Planning", "Access":.48, "Order":19 },
    { "Month":"Apr '14", "Measure":"Family Planning", "Access":.48, "Order":20 },
    { "Month":"May '14", "Measure":"Family Planning", "Access":.48, "Order":21 },
    { "Month":"Jun '14", "Measure":"Family Planning", "Access":.48, "Order":22 },
    { "Month":"Jul '14", "Measure":"Family Planning", "Access":.48, "Order":23 },
    { "Month":"Aug '14", "Measure":"Family Planning", "Access":.48, "Order":24 },
    { "Month":"Sep '14", "Measure":"Family Planning", "Access":0, "Order":25 },
    { "Month":"Oct '14", "Measure":"Family Planning", "Access":0, "Order":26 },
    { "Month":"Nov '14", "Measure":"Family Planning", "Access":0, "Order":27 },
    { "Month":"Dec '14", "Measure":"Family Planning", "Access":0, "Order":28 },
    { "Month":"Jan '15", "Measure":"Family Planning", "Access":0, "Order":29 }
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
myChart.addLegend(60, 0, 500, 20, "right");
myChart.draw();
