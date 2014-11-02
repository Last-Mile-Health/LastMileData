$(document).ready(function(){
    
    // !!!!! merge into deqa.js !!!!!
    
    // Run this script when refreshDataModal is shown
    $('#uploadDataFileModal').on('shown.bs.modal', function(e) {
        
        // upload file; read into object
        uploadedObject = {};    // !!!!!
        
        // Merge uploaded object with parsed localStorage object (form filtering has already been done)
        mergeObjects(uploadedObject,localStorage)
        
        // Manipulate DOM to show success message
        
        // when upload is complete, run the following...
            // Close Modal and Reset DOM
            setTimeout( function() {
                // reset DOM
                // 
                // Close dialog box
                $('.modal').modal('hide');
                
           }, 1000 );
        
    });
    
});


function mergeObjects(obj1, obj2) {
    keys1 = [];
    keys2 = [];
    for (key in obj1) { keys1.push(key); }
    for (key in obj2) { keys2.push(key); }

    obj3 = obj2;
    for (key in obj1) {
        var newKey = key;
        while (keys2.indexOf(newKey)!=-1) {
            newKey = newKey + "a";
//                            newKey = newKey + Math.random();
        }
        obj3[newKey] = obj1[key];
        keys2.push(newKey);
    }

    return obj3;
}
