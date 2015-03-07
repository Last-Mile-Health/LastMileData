// Create data object
var myData = {};

// Data: numFHWs
myData.numFHWs = [
    { "Month":"2012-09-01", "FHWs":0 },
    { "Month":"2012-10-01", "FHWs":10 },
    { "Month":"2012-11-01", "FHWs":10 },
    { "Month":"2012-12-01", "FHWs":10 },
    { "Month":"2013-01-01", "FHWs":10 },
    { "Month":"2013-02-01", "FHWs":10 },
    { "Month":"2013-03-01", "FHWs":10 },
    { "Month":"2013-04-01", "FHWs":10 },
    { "Month":"2013-05-01", "FHWs":10 },
    { "Month":"2013-06-01", "FHWs":26 },
    { "Month":"2013-07-01", "FHWs":26 },
    { "Month":"2013-08-01", "FHWs":26 },
    { "Month":"2013-09-01", "FHWs":26 },
    { "Month":"2013-10-01", "FHWs":26 },
    { "Month":"2013-11-01", "FHWs":26 },
    { "Month":"2013-12-01", "FHWs":42 },
    { "Month":"2014-01-01", "FHWs":42 },
    { "Month":"2014-02-01", "FHWs":42 },
    { "Month":"2014-03-01", "FHWs":42 },
    { "Month":"2014-04-01", "FHWs":42 },
    { "Month":"2014-05-01", "FHWs":42 },
    { "Month":"2014-06-01", "FHWs":42 },
    { "Month":"2014-07-01", "FHWs":42 },
    { "Month":"2014-08-01", "FHWs":42 },
    { "Month":"2014-09-01", "FHWs":42 },
    { "Month":"2014-10-01", "FHWs":42 },
    { "Month":"2014-11-01", "FHWs":42 },
    { "Month":"2014-12-01", "FHWs":55 },
    { "Month":"2015-01-01", "FHWs":55 }
];

// Data: numSupervisors
myData.numSupervisors = [
    { "Month":"2012-09-01", "Supervisors":0 },
    { "Month":"2012-10-01", "Supervisors":2 },
    { "Month":"2012-11-01", "Supervisors":2 },
    { "Month":"2012-12-01", "Supervisors":2 },
    { "Month":"2013-01-01", "Supervisors":2 },
    { "Month":"2013-02-01", "Supervisors":2 },
    { "Month":"2013-03-01", "Supervisors":2 },
    { "Month":"2013-04-01", "Supervisors":2 },
    { "Month":"2013-05-01", "Supervisors":2 },
    { "Month":"2013-06-01", "Supervisors":3 },
    { "Month":"2013-07-01", "Supervisors":3 },
    { "Month":"2013-08-01", "Supervisors":3 },
    { "Month":"2013-09-01", "Supervisors":3 },
    { "Month":"2013-10-01", "Supervisors":3 },
    { "Month":"2013-11-01", "Supervisors":3 },
    { "Month":"2013-12-01", "Supervisors":4 },
    { "Month":"2014-01-01", "Supervisors":4 },
    { "Month":"2014-02-01", "Supervisors":4 },
    { "Month":"2014-03-01", "Supervisors":4 },
    { "Month":"2014-04-01", "Supervisors":4 },
    { "Month":"2014-05-01", "Supervisors":4 },
    { "Month":"2014-06-01", "Supervisors":4 },
    { "Month":"2014-07-01", "Supervisors":4 },
    { "Month":"2014-08-01", "Supervisors":4 },
    { "Month":"2014-09-01", "Supervisors":4 },
    { "Month":"2014-10-01", "Supervisors":4 },
    { "Month":"2014-11-01", "Supervisors":4 },
    { "Month":"2014-12-01", "Supervisors":5 },
    { "Month":"2015-01-01", "Supervisors":5 }
];

// Data: numPeopleServed
myData.numPeopleServed = [
    { "Month":"2012-09-01", "People":0 },
    { "Month":"2012-10-01", "People":1300 },
    { "Month":"2012-11-01", "People":1300 },
    { "Month":"2012-12-01", "People":1292 },
    { "Month":"2013-01-01", "People":1308 },
    { "Month":"2013-02-01", "People":1321 },
    { "Month":"2013-03-01", "People":1372 },
    { "Month":"2013-04-01", "People":1377 },
    { "Month":"2013-05-01", "People":1419 },
    { "Month":"2013-06-01", "People":1434 },
    { "Month":"2013-07-01", "People":5169 },
    { "Month":"2013-08-01", "People":5302 },
    { "Month":"2013-09-01", "People":5357 },
    { "Month":"2013-10-01", "People":5383 },
    { "Month":"2013-11-01", "People":5430 },
    { "Month":"2013-12-01", "People":5449 },
    { "Month":"2014-01-01", "People":7532 },
    { "Month":"2014-02-01", "People":9280 },
    { "Month":"2014-03-01", "People":9378 },
    { "Month":"2014-04-01", "People":9534 },
    { "Month":"2014-05-01", "People":9639 },
    { "Month":"2014-06-01", "People":9971 },
    { "Month":"2014-07-01", "People":10212 },
    { "Month":"2014-08-01", "People":10396 },
    { "Month":"2014-09-01", "People":10621 },
    { "Month":"2014-10-01", "People":11027 },
    { "Month":"2014-11-01", "People":13868 },
    { "Month":"2014-12-01", "People":14217 },
    { "Month":"2015-01-01", "People":14298 }
];

// Data: numVillagesServed
myData.numVillagesServed = [
    { "Month":"2012-09-01", "Villages":0 },
    { "Month":"2012-10-01", "Villages":13 },
    { "Month":"2012-11-01", "Villages":13 },
    { "Month":"2012-12-01", "Villages":13 },
    { "Month":"2013-01-01", "Villages":13 },
    { "Month":"2013-02-01", "Villages":13 },
    { "Month":"2013-03-01", "Villages":13 },
    { "Month":"2013-04-01", "Villages":13 },
    { "Month":"2013-05-01", "Villages":13 },
    { "Month":"2013-06-01", "Villages":13 },
    { "Month":"2013-07-01", "Villages":28 },
    { "Month":"2013-08-01", "Villages":28 },
    { "Month":"2013-09-01", "Villages":28 },
    { "Month":"2013-10-01", "Villages":28 },
    { "Month":"2013-11-01", "Villages":28 },
    { "Month":"2013-12-01", "Villages":28 },
    { "Month":"2014-01-01", "Villages":42 },
    { "Month":"2014-02-01", "Villages":42 },
    { "Month":"2014-03-01", "Villages":42 },
    { "Month":"2014-04-01", "Villages":42 },
    { "Month":"2014-05-01", "Villages":42 },
    { "Month":"2014-06-01", "Villages":42 },
    { "Month":"2014-07-01", "Villages":42 },
    { "Month":"2014-08-01", "Villages":42 },
    { "Month":"2014-09-01", "Villages":42 },
    { "Month":"2014-10-01", "Villages":52 },
    { "Month":"2014-11-01", "Villages":52 },
    { "Month":"2014-12-01", "Villages":52 },
    { "Month":"2015-01-01", "Villages":52 }
//    { "Month":"2014-10-01", "Villages":54 },
//    { "Month":"2014-11-01", "Villages":54 },
//    { "Month":"2014-12-01", "Villages":54 },
//    { "Month":"2015-01-01", "Villages":54 }
];

// Data: numHealthWorkersTrainedEbola
myData.numHealthWorkersTrainedEbola = [
    { "Month":"2014-12-01", "Health workers":577 },
    { "Month":"2015-01-01", "Health workers":734 }
];

// Data: numFacilitiesIPC
myData.numFacilitiesIPC = [
    { "Month":"2014-12-01", "Facilities":36 },
    { "Month":"2015-01-01", "Facilities":36 }
];

// Data: numStaffLiberia
myData.numStaffLiberia = [
    { "Month":"2015-01-01", "Staff":143 }
];

// Data: numStaffUS
myData.numStaffUS = [
    { "Month":"2014-12-01", "Staff":12 },
    { "Month":"2015-01-01", "Staff":13 }
];

// Data: fundsReceived
myData.fundsReceived = [
    { "Month":"2014-12-01", "USD":5929485 },
    { "Month":"2015-01-01", "USD":6529994 }
];

// Data: fundsReceivedAndCommitted
myData.fundsReceivedAndCommitted = [
    { "Month":"2014-12-01", "USD":7916371 },
    { "Month":"2015-01-01", "USD":8359411 }
];

// Data: percentOfBudgetRaised
myData.percentOfBudgetRaised = [
    { "Month":"2014-12-01", "Percent":.85 },
    { "Month":"2015-01-01", "Percent":.94 }
];

// Data: fy15Expenses
myData.fy15Expenses = [
    { "Month":"2014-12-01", "USD":2010935 },
    { "Month":"2015-01-01", "USD":2767124 }
];

// Data: fy15Spent
myData.fy15Spent = [
    { "Month":"2014-12-01", "Percent":.29 },
    { "Month":"2015-01-01", "Percent":.40 }
];

// Data: cashOnHand
myData.cashOnHand = [
    { "Month":"2014-12-01", "USD":4305484 },
    { "Month":"2015-01-01", "USD":4623701 }
];

// Data: cashBurnRate
myData.cashBurnRate = [
    { "Month":"2014-12-01", "USD":329000 },
    { "Month":"2015-01-01", "USD":420000 }
];
