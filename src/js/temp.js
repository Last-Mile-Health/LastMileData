var myModule = (function(){
    
    var privVar = 4;
    var pubVar = 3;
    
    var pubFunc = function() { alert('I\'m public!'); }
    var privFunc = function() { alert('I\'m private!'); }
    
    return {
        myPubVar: pubVar,
        myPubFunc: pubFunc
    }
    
})();

console.log(myModule.pubVar);
console.log(myModule.myPubVar);
console.log(myModule.PrivVar);
