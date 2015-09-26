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
    //          Valid formats include 'integer', 'percent', 'decimal', or 'dollars'
    //          "X" is number of decimal places
    function format_number(number, format, X) {
        if (number === '' || number === null || typeof number === 'undefined') {
            return '';
        } else {
            switch(format) {
                case 'integer':
                    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    break;
                case 'percent':
                    return (number*100).toFixed(X) + "%";
                    break;
                case 'decimal':
                    return number.toFixed(X);
                    break;
                case 'dollars':
                    return "$" + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    break;
                default:
                    return number;
            }
        }
    }


    // LMD_utilities API
    return {
        mysql_date: mysql_date,
        mysql_time: mysql_time,
        getUUID: getUUID,
        twoDigits: twoDigits,
        format_number: format_number
    };
    

})();
