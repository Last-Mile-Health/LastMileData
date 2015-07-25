$(document).ready(function(){
    // Apply access control settings
    // Set permissions in LMD_accessControl.js file
    LMD_accessControl.setPage('meDocs');
    LMD_accessControl.setUserType(usertype);
    LMD_accessControl.go();
});
