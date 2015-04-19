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
    // For meAccessList, ... !!!!!
    // For deqaAccessList, ... !!!!!
    function setPermissions(userType){
        switch (userType) {
            case 'admin':
                dpAccessList = ['header_overview','header_monthlyDashboards','header_kpia','header_liberiaStats','header_meTools','header_staging'];
                meAccessList = ['header_programForms','header_strategy','header_reports','header_mHealthTraining','header_xForms'];
                deqaAccessList = [''];
                break;
            case 'deqa':
            case 'user':
                dpAccessList = ['header_overview','header_monthlyDashboards','header_kpia','header_liberiaStats','header_meTools'];
                meAccessList = ['header_programForms','header_strategy','header_reports','header_mHealthTraining','header_xForms'];
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
