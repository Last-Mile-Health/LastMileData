//var svg = dimple.newSvg("#dataportal_dashboard_numSupervisors", 475, 400);
var svg = dimple.newSvg("#dataportal_dashboard_numSupervisors", 575, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Month":"Sep '12", "Measure":"Supervisors", "Number":0, "Order":1 },
    { "Month":"Oct '12", "Measure":"Supervisors", "Number":2, "Order":2 },
    { "Month":"Nov '12", "Measure":"Supervisors", "Number":2, "Order":3 },
    { "Month":"Dec '12", "Measure":"Supervisors", "Number":2, "Order":4 },
    { "Month":"Jan '13", "Measure":"Supervisors", "Number":2, "Order":5 },
    { "Month":"Feb '13", "Measure":"Supervisors", "Number":2, "Order":6 },
    { "Month":"Mar '13", "Measure":"Supervisors", "Number":2, "Order":7 },
    { "Month":"Apr '13", "Measure":"Supervisors", "Number":2, "Order":8 },
    { "Month":"May '13", "Measure":"Supervisors", "Number":2, "Order":9 },
    { "Month":"Jun '13", "Measure":"Supervisors", "Number":3, "Order":10 },
    { "Month":"Jul '13", "Measure":"Supervisors", "Number":3, "Order":11 },
    { "Month":"Aug '13", "Measure":"Supervisors", "Number":3, "Order":12 },
    { "Month":"Sep '13", "Measure":"Supervisors", "Number":3, "Order":13 },
    { "Month":"Oct '13", "Measure":"Supervisors", "Number":3, "Order":14 },
    { "Month":"Nov '13", "Measure":"Supervisors", "Number":3, "Order":15 },
    { "Month":"Dec '13", "Measure":"Supervisors", "Number":4, "Order":16 },
    { "Month":"Jan '14", "Measure":"Supervisors", "Number":4, "Order":17 },
    { "Month":"Feb '14", "Measure":"Supervisors", "Number":4, "Order":18 },
    { "Month":"Mar '14", "Measure":"Supervisors", "Number":4, "Order":19 },
    { "Month":"Apr '14", "Measure":"Supervisors", "Number":4, "Order":20 },
    { "Month":"May '14", "Measure":"Supervisors", "Number":4, "Order":21 },
    { "Month":"Jun '14", "Measure":"Supervisors", "Number":4, "Order":22 },
    { "Month":"Jul '14", "Measure":"Supervisors", "Number":4, "Order":23 },
    { "Month":"Aug '14", "Measure":"Supervisors", "Number":4, "Order":24 },
    { "Month":"Sep '14", "Measure":"Supervisors", "Number":4, "Order":25 },
    { "Month":"Oct '14", "Measure":"Supervisors", "Number":4, "Order":26 },
    { "Month":"Nov '14", "Measure":"Supervisors", "Number":4, "Order":27 },
    { "Month":"Dec '14", "Measure":"Supervisors", "Number":5, "Order":28 },
    { "Month":"Jan '15", "Measure":"Supervisors", "Number":5, "Order":29 },
];


var myChart = new dimple.chart(svg, data);

// Set color scheme: orange
myChart.defaultColors = [
    new dimple.color("#F79646")
];

myChart.setBounds(40, 30, 430, 305);
var x = myChart.addCategoryAxis("x", "Month");
x.addOrderRule("Order");
myChart.addMeasureAxis("y", "Number");
myChart.addSeries("Measure", dimple.plot.line);
myChart.draw();
