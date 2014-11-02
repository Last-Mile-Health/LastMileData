// Return MySQL-formatted "DATETIME" string of current date/time
function mysql_datetime()
{
    var now = new Date();
    return ( now.getUTCFullYear() + "-" + twoDigits(1 + now.getUTCMonth()) + "-" + twoDigits(now.getUTCDate()) + " " + twoDigits(now.getUTCHours()) + ":" + twoDigits(now.getUTCMinutes()) + ":" + twoDigits(now.getUTCSeconds()) );
}

