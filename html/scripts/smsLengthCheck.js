// JavaScript Document

function checkmsg(f) {
        var len = f.value.length
        var cl

        if ((len == 1) && (f.value.substring(0, 1) == " ")) {
                f.value = ""
                len = 0
        }
        if (len > 160) {
                f.value = f.value.substring(0, 160)
                cl = 0
        }
        else {
                cl = 160 - len
        }
        document.forms[0].CNT.value = cl
}

function meslen() {
	var rem; // remaining number available
	var ml;
	rem = parseInt(document.sendtextform.meslength.value);
	ml = parseInt(document.sendtextform.message.value.length);
	document.sendtextform.meslength.value = rem - 1;
}

function checkSMS() {
	var f = document.sendtextform;
	var m = f.message.value;
	if (m) { 
		return true;
 	} else {
		 alert ('Please type a message to send!');
 		return false;
 	}
}