var LMD_dataPortal = (function(){


    // PRIVATE VARS
    var indicatorData = {};
    var indicatorMetadata = {};


    // PUBLIC:  Stores indicator data (in "indicatorData" object)
    //          Keys are indicator IDs
    //          Values are objects containing four properties: Date (MySQL format), Month, Year, Value
    function addIndicatorData(indID, month, year, value) {
        var obj = {};
        obj.Date = year + "-" + LMD_utilities.twoDigits(month) + "-01";
        obj.Month = Number(month);
        obj.Year = Number(year);
        obj.Value = Number(value);
        indicatorData[indID] = indicatorData[indID] || [];
        indicatorData[indID].push(obj);
    }


    // PUBLIC:  Stores indicator metadata (in "indicatorMetadata" object)
    //          Keys are indicator IDs
    //          Values are objects containing various metadata fields as properties
    function addIndicatorMetadata(indID, metadata) {
        indicatorMetadata[indID] = metadata;
    }


    // PUBLIC:  Returns data array for a single indicator, based on indicator ID
    // Example: getIndicatorData(16) might return the following...
    //              [
    //                  { Date:"2014-12-01", Month:1, Year:2014, Value:33 },
    //                  { Date:"2015-01-01", Month:2, Year:2015, Value:44 },
    //                  { Date:"2015-02-01", Month:3, Year:2015, Value:55 }
    //              ]
    function getIndicatorData(indID) {
        return indicatorData[indID];
    }


    // PUBLIC:  Returns metadata object for a single indicator, based on indicator ID
    // Example: getIndicatorMetadata(16) might return the following...
    //                  { indName:"Number of CHWs", indDefinition:"A deployed CHW is...", ... },
    //
    function getIndicatorMetadata(indID) {
        return indicatorMetadata[indID];
    }


    // PUBLIC:  Sorts indicatorData by date
    function sortByDate() {
        for (var key in indicatorData) {
            indicatorData[key].sort(function(a,b){
                if ( (a.Year*12)+a.Month > (b.Year*12)+b.Month ) {
                    return -1;
                }
                if ( (a.Year*12)+a.Month < (b.Year*12)+b.Month ) {
                    return 1;
                } else {
                    return 0;
                }
            });
        }
    }


    // LMD_dataPortal API
    return {
        addIndicatorData: addIndicatorData,
        addIndicatorMetadata: addIndicatorMetadata,
        getIndicatorData: getIndicatorData,
        getIndicatorMetadata: getIndicatorMetadata,
        sortByDate: sortByDate
    };
    

})();
