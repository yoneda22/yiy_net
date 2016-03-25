/**
 * 米壱屋 javascript library (YJL)
 */
/**** VALIDATE ****/
function isEmpty(val) {

   if (val == null) return true;
   if (val == '')   return true;

   return false;
}
function isInRangeLength(val, maxLength) {
    if (val.length <= maxLength) {
       return true;
    } else {
       return false;
    }
}
function isMailAddress(val) {
    return val.match(/^[A-Za-z0-9]+[\w-]+@[\w\.-]+\.\w{2,}$/);
}
function isOnlyHalfWidthAlphaNumeric(val) {
    return val.match(/^[a-zA-Z0-9]+$/);
}
function isMySQLFormatYMD(val) {
    if (!val.match(/^\d{4}-\d{2}-\d{2}$/)) return false;
    var year  = val.substr(0, 4);
    var month = val.substr(5, 2);
    var day   = val.substr(8, 2);

    return isDate(year, month, day);
}
function isDate(year, month, day) {
    var date = new Date(year, month-1, day);
    if (date.getFullYear() == year    &&
        date.getMonth()    == month-1 &&
        date.getDate()     == day) {
        return true;
    } else {
        return false;
    }
}

/**** CSS ****/
function buttonImageMask(selector) {
    var cssObj = {
        opacity: "0.4",
        filter: "alpha(opacity=40)",
    }
    $(selector).css(cssObj);
}
function buttonImageUnMask(selector) {
    var cssObj = {
        opacity: "1.0",
        filter: "alpha(opacity=100)",
    }
    $(selector).css(cssObj);
}
