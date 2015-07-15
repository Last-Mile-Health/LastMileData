var LMD_accessControl = (function(){


    // PRIVATE VARS
    var page, userType, dpAccessList, meAccessList, deqaAccessList;

    // PUBLIC: Set user type (i.e. access level)
    function setUserType(myUserType){
        userType = myUserType; // !!!!! currently not used !!!!!
        setPermissions(myUserType);
    }
    
    
    // PRIVATE: Set permissions, based on user type
    // !!!!! these should be queried from a database table !!!!!
    // For dpAccessList, list the IDs of headers or iFrame/fragment links that you want to give users access to
    // For meAccessList, list the IDs of "headers" or "items" to give access to
    // For deqaAccessList, ... !!!!!
    function setPermissions(userType){
        switch (userType) {
            case 'admin':
                dpAccessList = ['header_overview','header_monthlyDashboards','header_kpia','header_liberiaStats','header_meTools','header_staging','header_admin'];
                meAccessList = ['header_programForms','header_strategy','header_reports','header_mHealthTraining','header_xForms','header_litReviews','header_qualityAssessments'];
                deqaAccessList = [''];
                break;
            case 'deqa':
            case 'user':
                dpAccessList = ['header_overview','header_monthlyDashboards','header_kpia','header_liberiaStats','header_meTools'];
                meAccessList = ['header_programForms','header_strategy','header_reports','header_mHealthTraining','header_xForms','header_litReviews','header_qualityAssessments'];
                deqaAccessList = [''];
                break;
            case 'cht_gg':
                dpAccessList = ['item_overview','item_konobo','item_ebolaActivities','item_fhwTraining','header_kpia','header_liberiaStats','item_idNumbers'];
                meAccessList = ['header_programForms','header_mHealthTraining','header_xForms','item_report_kbs2012'];
                deqaAccessList = [''];
                break;
            case 'cht_rc':
                dpAccessList = ['item_overview','item_ebolaActivities','item_fhwTraining','header_liberiaStats','item_idNumbers'];
                meAccessList = ['header_programForms','header_mHealthTraining','header_xForms'];
                deqaAccessList = [''];
                break;
            default:
                dpAccessList = [''];
                meAccessList = [''];
                deqaAccessList = [''];
        }
    }
    

    // PUBLIC: Set page ("Data Portal", "M&E Docs", or "Data Entry / Quality Assurance")
    function setPage(myPage){
        page = myPage;
    }
    

    // PUBLIC: Apply restrictions
    function go(){
        switch (page) {
            
            case 'dataPortal':
                
                $('#myAccordion h3').each(function(){
                    // Run this code if header is NOT in accessList
                    if ( dpAccessList.indexOf($(this).attr('id')) === -1  ) {
                        // Hide header
                        $(this).hide();
                        // Step through LIs and show all that are in accessList; show header if at least one LI is shown
                        $(this).next().children().each(function(){
                            if ( dpAccessList.indexOf($(this).attr('id')) !== -1 ) {
                                $(this).parent().prev().show()
                            } else {
                                $(this).hide();
                            }
                        });
                    }
                });
                break;
                
                
            case 'meDocs':
                
                $('#mainContents h3').each(function(){
                    // Run this code if header is NOT in accessList
                    if ( meAccessList.indexOf($(this).attr('id')) === -1  ) {
                        // Hide header
                        $(this).hide();
                        // Step through LIs and show all that are in accessList; show header if at least one LI is shown
                        $(this).next().children().each(function(){
                            if ( meAccessList.indexOf($(this).attr('id')) !== -1 ) {
                                $(this).parent().prev().show()
                            } else {
                                $(this).hide();
                            }
                        });
                    }
                });
                break;
                
                
            case 'deqa':
                // code
                break;
                
        }
    }
    

    // LMD_accessControl API
    return {
        setPage: setPage,
        setUserType: setUserType,
        go: go
    };
    

})();

var LMD_dataPortal = (function(){


    // PRIVATE VARS
    var indicatorData = {};
    var indicatorMetadata = {};


    // PUBLIC:  Stores indicator data (in "indicatorData" object)
    //          Keys are indicator IDs
    //          Values are objects containing four properties: Date (MySQL format), Month, Year, Value
    function addIndicatorData(indID, month, year, value) {
        var obj = {};
        obj.Date = year + "-" + twoDigits(month) + "-01";
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
