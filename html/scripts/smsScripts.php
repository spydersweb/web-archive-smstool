<?php require_once('commonFunctions.php'); ?>
<?php

buildStats();

$messageID = session_id().$_SESSION['sesImp'];


$pid_rsSMSList = "-1";
if (isset($_SESSION['MM_ProfileID'])) {
  $pid_rsSMSList = (get_magic_quotes_gpc()) ? $_SESSION['MM_ProfileID'] : addslashes($_SESSION['MM_ProfileID']);
}
mysql_select_db($database_connUKTIsms, $connUKTIsms);
$query_rsSMSList = sprintf("SELECT count(tblsmslist.profileID) as numList FROM tblsmslist WHERE tblsmslist.profileID = %s", GetSQLValueString($pid_rsSMSList, "int"));
$rsSMSList = mysql_query($query_rsSMSList, $connUKTIsms) or die(mysql_error());
$row_rsSMSList = mysql_fetch_assoc($rsSMSList);
$totalRows_rsSMSList = mysql_num_rows($rsSMSList);

$GLOBALS['errorCheck'] = canWeSMS($row_rsSMSList['numList']);

if (!isset($_SESSION)) {
  session_start();
}


if (isset($_POST['message']) && ($_POST['message'] !="")) { // Check for the existance of a message to send
/*
// Send SMS Message
// Message constructor


?Client_ID=PU0729377&Client_Pass=smstool&=99999999&=ref&=2&=BrackenPres&=1&=%2b447796767646,%2b447796767646,%2b447796767646,%2b447796767646&Body=Hello%20World&SMS_Type=0&Reply_Type=0
*/

// Import sms numbers into a variable
$DestinationEx = "";
mysql_select_db($database_connUKTIsms, $connUKTIsms);
$query_rsGetSMSList = sprintf("SELECT smsMobile FROM %s WHERE profileID = %s and attRef in (select uniqueID from tblsmslist)",$_SESSION['MM_DBTable'], GetSQLValueString($_SESSION['MM_ProfileID'], "int"));
$rsGetSMSList = mysql_query($query_rsGetSMSList, $connUKTIsms) or die(mysql_error());
$row_rsGetSMSList = mysql_fetch_assoc($rsGetSMSList);
$totalRows_rsGetSMSList = mysql_num_rows($rsGetSMSList);

// Build the array of SMS numbers
$creditsUsed=$GLOBALS['smsCredits'];



do {
	// Build the SMS mobile list for sending
	$DestinationEx .= $row_rsGetSMSList['smsMobile'].",";
	
	// Handle credits if not in testing mode
	if ($_SESSION['smsTesting']!=1) {
	
	// Increment the credit counter when not in Testing Mode
	$creditsUsed--;
	}
	
	} while ($row_rsGetSMSList = mysql_fetch_assoc($rsGetSMSList));
	
	$DestinationEx = trim($DestinationEx,","); // trim the SMS mobile variable
	
	//Update the credits for this clientProfile
	$upCredits = sprintf("Update tblclientprofile set smsCredits=%s where profileID=%s",
		GetSQLValueString($creditsUsed, "int"),
		GetSQLValueString($_SESSION['MM_ProfileID'], "int"));
	
  	mysql_select_db($database_connUKTIsms, $connUKTIsms);
 	$Result1 = mysql_query($upCredits, $connUKTIsms) or die(mysql_error());
	
	

// Set up Variables to make the HTTP POST to TextAnyWhere
$URI = "https://ws.textanywhere.net/HTTPRX/SendSMSEx.aspx";

$fields = array(

	'Client_ID' => $_SESSION['clientID'],
	'Client_Pass' => $_SESSION['clientPass'],
	'Client_Ref' => $messageID,
	'Billing_Ref' => 'BrackenPresentationsLtd',
	'Connection' => $_SESSION['smsTesting'],
	'Originator' => $_SESSION['originator'],
	'OType' => '1',
	'DestinationEx' => $DestinationEx,
	'Body' => $_POST['message'],
	'SMS_Type' => '0', // This value must be set to 0
	'Reply_Type' => '0' // We are not accepting replys from Bracken but future setup will involve organising a Reply Path and viewing sent messages
);

// Fire off the Post to Text AnyWhere and capture the response
$response = http_post_fields($URI, $fields);

// Capture the response and setup the delivery return to the client
$a = explode("+",$response,2);  // split the response at the first + sign this should give us (447796767646:1,+447796767646:1)
$b = explode(",",$a[1]); // split the response down into (SMSMobile:StatusCode)

$counter=0;
$tel = array();
$stat = array();
$statusOutput = "";
$insSQL="";

foreach($b as $v) {
	$tempval = explode(":",trim($v,","));
	$tel[$counter] = $tempval[0];
	$stat[$counter] = $tempval[1];
	$statusOutput .= "SMS Number : ".trim($tel[$counter],"+")." Status : ".retDelStatus($stat[$counter])."<br/>";
	$insSQL .= sprintf("(%s,%s,%s,%s,%s,%s),", 
				GetSQLValueString($_SESSION['MM_ProfileID'], "int"),
				GetSQLValueString($_POST['message'], "text"),
				GetSQLValueString($_SESSION['smsTesting'], "int"),
				GetSQLValueString($messageID, "text"),
				GetSQLValueString(trim($tel[$counter],"+"),"text"),
				GetSQLValueString(date("d/m/y G:i:s"),"text"));
						
	$counter++;
	
} 
	
$insSQL = trim($insSQL,",");

// Insert sent messages into the sent messages table
$insertSQL = "INSERT INTO tblsentmessages (profileID, message, smsTest, ClientRef,smsMobile,dt) VALUES $insSQL";

  mysql_select_db($database_connUKTIsms, $connUKTIsms);
  $Result1 = mysql_query($insertSQL, $connUKTIsms) or die(mysql_error());

//header("Location: sendSMS.php");
buildStats();
$_SESSION['sesImp']++;
}
?>