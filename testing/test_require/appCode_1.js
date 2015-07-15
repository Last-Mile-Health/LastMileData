
require(['require_config'], function() {
    
    console.log('start');
    
    require(['example_objectLiteral'],function(obj){
        console.log('loaded object literal');
        console.log(obj);
    });
    
    require(['example_objectLiteral','example_function'],function(obj, func){
        console.log('loaded both');
        console.log(obj); console.log(func);
    });
    
//    require(['http://localhost/LastMileData/testing/test_require/non-module.js'],function(){
    require(['non-module'],function(){
        console.log('loaded non-module');
    });
    
    // Using an alternate path
    require(['lib/jquery.min'],function(){
        console.log('jQuery loaded from alternate path');
        $(document).ready(function(){
            console.log($('#imaginaryDIV'));
        });
    });
    
    console.log('other code can happen here asynchronously');
    
});

// continue: "Definition Functions with Dependencies"
