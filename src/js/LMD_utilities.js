// Module:          LMD_utilities.js
// Author:          Avi Kenny
// Last update:     2014-10-11
// Dependencies:    none

var LMD_utilities = (function(){


    // PRIVATE VARS
    var indicatorData = {}; // !!!!! template !!!!!


    // PUBLIC:  Returns MySQL-formatted date
    //          Overload #1: If no arguments are supplied, returns the current MySQL-formatted date
    //          Overload #2: If inputDate is supplied (string that can be parsed by "Date()"), converts to a MySQL-formatted date
    function mysql_date(inputDate) {
        if (arguments.length === 0) {
            var myDate = new Date();
        } else {
            var myDate = new Date(inputDate);
        }
        return myDate.getUTCFullYear() + "-" + twoDigits(1 + myDate.getUTCMonth()) + "-" + twoDigits(myDate.getUTCDate());
    }


    // PUBLIC:  Returns MySQL-formatted time
    //          Overload #1: If no arguments are supplied, returns the current MySQL-formatted time
    //          Overload #2: If inputDate is supplied (string that can be parsed by "Date()"), converts to a MySQL-formatted time
    function mysql_time(inputDate) {
        if (arguments.length === 0) {
            var myDate = new Date();
        } else {
            var myDate = new Date(inputDate);
        }
        return myDate.toTimeString().substring(0,8);
    }


    // PUBLIC:  Returns a "universally-unique identifier" (UUID)
    function getUUID() {
        var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
            return v.toString(16);
        });
        return uuid;
    }


    // PUBLIC:  Pad numbers to two digits
    //          Also a helper function for mysql_date()
    function twoDigits(d) {
        if(0 <= d && d < 10) return "0" + d.toString();
        if(-10 < d && d < 0) return "-0" + (-1*d).toString();
        return d.toString();
    }


    // PUBLIC:  Format numbers
    //          Valid formats include 'integer', 'percent-X', 'decimal-X', or 'dollars' (X is number of decimal places)
    function format_number(number, format) {
        if (number === '' || number === null || typeof number === 'undefined') {
            return '';
        } else {
            var type = format ? format.split("-")[0] : null;
            var X = format ? format.split("-")[1] : 1;
            switch(type) {
                case 'integer':
                    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    break;
                case 'percent':
                    return Number(number*100).toFixed(X) + "%";
                    break;
                case 'decimal':
                    return Number(number).toFixed(X);
                    break;
                case 'dollars':
                    return "$" + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    break;
                default:
                    return number;
            }
        }
    }


    // PUBLIC:  Given a button (passed in via a selector), change its state
    //          Used for ajax requests as an indication to the user of the state of the request
    function ajaxButton($selector, state, buttonText) {
        switch(state) {
            case 'ajaxLoader':
                // Disable the button; load the "AJAX loader" GIF into it
                $selector.prop('disabled','disabled');
                $selector.html("<img src='/LastMileData/build/images/ajax_loader.gif'>");
                break;
            case 'enable':
                // Enable the button; optionally restore the buttonText
                $selector.prop('disabled','');
                if(buttonText) {
                    $selector.html(buttonText);
                }
                break;
            case 'disable':
                // De-activate the button; optionally restore the buttonText
                $selector.prop('disabled','disabled');
                if(buttonText) {
                    $selector.html(buttonText);
                }
                break;
            case 'alertSuccess':
                // Flash a "success" message for two seconds; restore the buttonText
                $selector.html("Success!");
                var color = "white";
                var interval = setInterval(function() {
                    color = (color==="white") ? "yellow" : "white";
                    $selector.css('color',color);
                },100);
                setTimeout(function() {
                    $selector.css('color',"white");
                    $selector.html(buttonText);
                    clearInterval(interval);
                },2000);
                break;
            case 'alertError':
                // Flash an "error" message for two seconds; restore the buttonText
                $selector.html("Error!");
                var color = "white";
                var interval = setInterval(function() {
                    color = (color==="white") ? "red" : "white";
                    $selector.css('color',color);
                },100);
                setTimeout(function() {
                    $selector.css('color',"white");
                    $selector.html(buttonText);
                    clearInterval(interval);
                },2000);
                break;
        }
    }


    // PUBLIC:  Escape quote marks by adding a backslash
    //          Useful for escaping query strings before sending to the server
    //          Note: this will not prevent against SQL injection!
    function addSlashes(string) {
        return (string + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
    }


    // PUBLIC:  Test if the value is numeric
    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }


    // LMD_utilities API
    return {
        mysql_date: mysql_date,
        mysql_time: mysql_time,
        getUUID: getUUID,
        twoDigits: twoDigits,
        format_number: format_number,
        ajaxButton: ajaxButton,
        addSlashes: addSlashes,
        isNumeric: isNumeric
    };
    

})();
