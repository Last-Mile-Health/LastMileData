// Module:          LMD_formValidate
// Author:          Avi Kenny
// Last update:     2015-01-19
// Dependencies:    jQuery

$(document).ready(function(){
    
    LMD_formValidate = (function(){
        
        // PUBLIC: Validate a set of elements
        // $inputs is a jQuery selection
        // !!!!! is errorFields getting pushed two twice if the same field has multiple errors ?????
        // !!!!! This currently only handles input elements; add validation rules for checkboxes ?????
        function validate($inputs) {
            
            var result = "";
            var errorMessages = [];
            var errorFields = [];
            var disallowed = /[`~#\$%\^&\*\+;\\\|<>]+/;
            
            $($inputs).each(function() {
                
                // Get key/value pair
                myField = $(this).attr('name');
                myValue = $(this).val();
                
                // Test: disallowed characters: # & + ; ^ * |
                if ( (disallowed.test(myValue))===true ) {
                    errorFields.push(myField);
                    errorMessages.push('Field "' + myField + '" cannot contain the following characters: `~#$%^&*+;\|<>');
                }
                
                // Test: field is required
                if ( $(this).attr('data-lmd-valid-required')==='' && myValue=="" ) {
                    errorFields.push(myField);
                    errorMessages.push('Field "' + myField + '" is required.');
                }
                
                // Test: is a (decimal) number
                if ( $(this).attr('data-lmd-valid-dec')==='' && isNaN(myValue) ) {
                    errorFields.push(myField);
                    errorMessages.push('Field "' + myField + '" must be a number');
                }
                
                // Test: is an integer
                if ( $(this).attr('data-lmd-valid-int')==='' && ( isNaN(myValue) || myValue!=Math.floor(myValue) ) ) {
                    errorFields.push(myField);
                    errorMessages.push('Field "' + myField + '" must be an integer');
                }
                
                // Test: is a value-restricted (decimal) number
                myMin = Number($(this).attr('data-lmd-valid-decMin'));
                myMax = Number($(this).attr('data-lmd-valid-decMax'));
                if ( myMin && myValue!="" ) {
                    
                    if ( myMax && ( myValue<myMin || myValue>myMax || isNaN(myValue) ) ) {
                        errorFields.push(myField);
                        errorMessages.push('Field "' + myField + '" must be a number between ' + myMin + ' and ' + myMax);
                    }
                    else if ( myValue<myMin || isNaN(myValue) ) {
                        errorFields.push(myField);
                        errorMessages.push('Field "' + myField + '" must be a number greater than or equal to ' + myMin);
                    }
                }
                else if ( myMax && myValue!="" ) {
                    if ( myValue>myMax || isNaN(myValue) ) {
                        errorFields.push(myField);
                        errorMessages.push('Field "' + myField + '" must be a number less than or equal to ' + myMax);
                    }
                }
                
                // Test: is a value-restricted integer
                // Owen Note:  For now, minimum integer value will be 0.  We can recode this later if we want minimuns
                // to be less than zero.
                myMin = Number($(this).attr('data-lmd-valid-intMin'));
                myMax = Number($(this).attr('data-lmd-valid-intMax'));
                
                if ( ( myMin >= 0 ) && ( myValue != "" ) ) {
                    if ( myMax && ( myValue<myMin || myValue>myMax || isNaN(myValue) || myValue!=Math.floor(myValue) ) ) {
                        errorFields.push(myField);
                        errorMessages.push('Field "' + myField + '" must be an integer between ' + myMin + ' and ' + myMax);
                    }
                    else if ( myValue<myMin || isNaN(myValue) || myValue!=Math.floor(myValue) ) {
                        errorFields.push(myField);
                        errorMessages.push('Field "' + myField + '" must be an integer greater than or equal to ' + myMin);
                    }
                }
                else if ( myMax && myValue!="" ) {
                    if ( myValue>myMax || isNaN(myValue) || myValue!=Math.floor(myValue) ) {
                        errorFields.push(myField);
                        errorMessages.push('Field "' + myField + '" must be an integer less than or equal to ' + myMax);
                    }
                }
                
                // Test: regex
                // !!!!! Note: this may have problems if the regexp contains a single-quote (') or double-quote (") character !!!!!
                // 
                // Owen Note: I added a conditional test to see if the myValue string is empty; otherwise, it is always treated 
                // as mandatory field.  This is probably a hack.
                
                if ( myValue!="" ) {
                    
                    if ($(this).attr('data-lmd-valid-regex')) {
                    
                        myRegEx = new RegExp($(this).attr('data-lmd-valid-regex'));
                    
                        if (!myRegEx.test(myValue)) {
                            
                            errorFields.push(myField);
                            
                            if ($(this).attr('data-lmd-valid-errormessage')) {
                                errorMessages.push('Field "' + myField + '" ' + $(this).attr('data-lmd-valid-errormessage'));
                            }
                            else {  
                                errorMessages.push('Field "' + myField + '" must conform to the regex pattern: /' + $(this).attr('data-lmd-valid-regex') + '/');
                            }
                        }
                    }
                };
                
                
                // Test: !!!!! build character limit here !!!!!
                if (1===0) {
                    errorFields.push(myField);
                    errorMessages.push('Field "' + myField + '" EM');
                }
                
            });
            
            return {
                result: errorFields.length > 0 ? "fail" : "pass",
                errorMessages: errorMessages,
                errorFields: errorFields
            };
            
        }
        
        return {
            validate:validate
        };
        
    })();
    
});