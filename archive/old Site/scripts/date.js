<!-- Hide from older Browsers

// Variable Definition
var now = new Date();
var year = now.getYear();
var month = now.getMonth() +1;
var day = now.getDay() + 1;
var date = ((now.getDate()<10) ? "0" : "") + now.getDate();
var Ext = null

if (day == 1)  Day ="Sunday";
if (day == 2)  Day ="Monday";
if (day == 3)  Day ="Tuesday";
if (day == 4)  Day ="Wednesday";
if (day == 5)  Day ="Thursday";
if (day == 6)  Day ="Friday";
if (day == 7)  Day ="Saturday";

if (month == 1)  Month="January";
if (month == 2)  Month="February";
if (month == 3)  Month="March";
if (month == 4)  Month="April";
if (month == 5)  Month="May";
if (month == 6)  Month="June";
if (month == 7)  Month="July";
if (month == 8)  Month="August";
if (month == 9)  Month="September";
if (month == 10)  Month="October";
if (month == 11)  Month="November";
if (month == 12)  Month="December";

if (date == 1 || date == 21 || date ==31) Ext = "st";
if (date == 2 || date == 22) Ext = "nd";
if (date == 3 || date == 23) Ext = "rd";
if (date > 3 && date < 21 || date > 23 && date < 31)  Ext = "th";

// Display current date in a custom variable
var today = (Day + " " + date );

//Stop hiding from older browsers-->