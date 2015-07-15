
var concat1 = 'hey';

concat1 = 123;

if (concat1) {
    console.log('yep!');
}

function myFunc(){
    var variableName = 7;
    return variableName;
}

concat1 = myFunc();


alertUndefined();

function alertUndefined(){
    var myUndefinedVariable;
    alert(myUndefinedVariable.prop);
}
