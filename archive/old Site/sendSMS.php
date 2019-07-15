<?php require_once('../Connections/connUKTIsms.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  $_SESSION['MM_ProfileID'] = NULL;
	$_SESSION['MM_Company'] = NULL;
	$_SESSION['MM_Contact'] = NULL;
	$_SESSION['MM_Logo'] = NULL;
	$_SESSION['MM_Address'] = NULL;
	$_SESSION['MM_CSVUID'] = NULL;
	$_SESSION['MM_SMSCol'] = NULL;
	$_SESSION['MM_CSVNoCols'] = NULL;
	$_SESSION['MM_DBTable'] = NULL;
	$_SESSION['csvColHeaders'] = NULL;
	$_SESSION['compCampaign'] = NULL;
	$_SESSION['importScript'] = NULL;
	$_SESSION['smsTesting'] = NULL;
	
	$_SESSION['clientID'] = NULL;
	$_SESSION['clientPass'] = NULL;
	$_SESSION['originator'] = NULL;
	$_SESSION['sesImp'] = NULL;
    unset($_SESSION['MM_Username']);
    unset($_SESSION['MM_UserGroup']);
    unset($_SESSION['PrevUrl']);
    unset($_SESSION['MM_ProfileID']);
	unset($_SESSION['MM_Company']);
	unset($_SESSION['MM_Contact']);
	unset($_SESSION['MM_Logo']);
	unset($_SESSION['MM_Address']);
	unset($_SESSION['MM_CSVUID']);
	unset($_SESSION['MM_SMSCol']);
	unset($_SESSION['MM_CSVNoCols']);
	unset($_SESSION['MM_DBTable']);
	unset($_SESSION['csvColHeaders']);
	unset($_SESSION['compCampaign']);
	unset($_SESSION['importScript']);
	unset($_SESSION['smsTesting']);
	unset($_SESSION['clientID']);
	unset($_SESSION['clientPass']);
	unset($_SESSION['originator']);
	unset($_SESSION['sesImp']);
	
  $logoutGoTo = "index.php?err=2";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if ((isset($_GET['uid'])) && ($_GET['uid'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tblattendees WHERE attRef=%s",
                       GetSQLValueString($_GET['uid'], "text"));

  mysql_select_db($database_connUKTIsms, $connUKTIsms);
  $Result1 = mysql_query($deleteSQL, $connUKTIsms) or die(mysql_error());

  //$deleteGoTo = "review.php";
 // if (isset($_SERVER['QUERY_STRING'])) {
 //   $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
 //   $deleteGoTo .= $_SERVER['QUERY_STRING'];
 // }
 // header(sprintf("Location: %s", $deleteGoTo));
}

buildStats();

$messageID = session_id().$_SESSION['sesImp'];


$colname_rsContacts = "-1";
if (isset($_SESSION['MM_ProfileID'])) {
  $colname_rsContacts = (get_magic_quotes_gpc()) ? $_SESSION['MM_ProfileID'] : addslashes($_SESSION['MM_ProfileID']);
}
mysql_select_db($database_connUKTIsms, $connUKTIsms);
$query_rsContacts = sprintf("SELECT * FROM tblattendees WHERE profileID = %s", GetSQLValueString($colname_rsContacts, "int"));
$rsContacts = mysql_query($query_rsContacts, $connUKTIsms) or die(mysql_error());
$row_rsContacts = mysql_fetch_assoc($rsContacts);
$totalRows_rsContacts = mysql_num_rows($rsContacts);

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
$query_rsGetSMSList = sprintf("SELECT smsMobile FROM tblattendees WHERE profileID = %s and attRef in (select uniqueID from tblsmslist)", GetSQLValueString($_SESSION['MM_ProfileID'], "int"));
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
	
require_once('nusoap.php');	

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

//$nusoapclient = new nusoapclient('http://ws.textanywhere.net/ta_SMS.asmx?wsdl');
//$response = $nusoapclient->call('SendSMSEx',$fields,'http://ws.textanywhere.net/TA_WS','http://ws.textanywhere.net/TA_WS/SendSMSEx');

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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Event Technologies :: SMS Tool :: Send SMS Message</title>
<link href="styles/bracken.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript" src="scripts/date.js"></script>
<script language="javascript" type="text/javascript">
<!--
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
-->
</script><script language=JavaScript><!--
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
//--></script>
</head>

<body class="background">
<table width="760" border="0" align="center" cellpadding="0" cellspacing="0" class="tabBack">
  <tr>
    <td class="tl"></td>
    <td></td>
    <td class="tr"></td>
  </tr>
  <tr>
    <td></td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="49%" rowspan="2"><a href="menu.php"><img src="images/etLogo.jpg" alt="Event Technologies - click here to return to the home page" border="0" /></a></td>
        <td width="51%" align="center" class="title">CLIENT SMS FACILITY </td>
      </tr>
      <tr>
        <td align="center" class="date"><img src="images/<?php echo $_SESSION['MM_Logo']; ?>" alt="Logo" /><br/>
          </td>
      </tr>
      <tr>
        <td height="15" class="date"><script language="JavaScript" type="text/javascript" src="scripts/datedisplay.js"></script></td>
        <td height="15" align="center" class="date"><?php echo $_SESSION['compCampaign']; ?></td>
      </tr>
    </table></td>
    <td></td>
  </tr>
  <tr>
    <td class="bl"></td>
    <td></td>
    <td class="br"></td>
  </tr>
</table>
<!-- #BeginLibraryItem "/Library/Menu.lbi" --><table width="760px" cellpadding="0" cellspacing="0" align="center"><tr><td align="center">
<div id="nav-menu">
<ul>
<li> <a href="upload.php">Upload Data</a></li>
<li> <a href="review.php">Review Data</a></li>
<li> <a href="createSMSlist.php">Create SMS List</a></li>
<li> <a href="sendSMS.php">Send SMS</a></li>
</ul>
</div>
</td></tr></table><!-- #EndLibraryItem --><strong></strong>
<table width="760" border="0" align="center" cellpadding="0" cellspacing="0" class="tabBack">
  <tr><td height="5" colspan="3" class="background" ></td>
  </tr>
  <tr>
    <td class="tl"></td>
    <td ></td>
    <td class="tr"></td>
  </tr>
  <tr>
    <td></td>
	<td valign="top" class="texxtCopy"><p><strong>Send Out SMS Message to List (<?php echo $messageID; ?>)</strong></p>
      <?php 
	  switch ($GLOBALS['errorCheck'])  {
	  
	  // All systems go display the send form
	  case 1:
	  ?>
		  <p>Enter the message to be sent to your SMS send list. <?php if ($row_rsSMSList['numList']>1){ echo"There are ".$row_rsSMSList['numList']." contacts in the SMS send list";} else { echo "There is ".$row_rsSMSList['numList']." contact in the SMS send list";}?></p>
      
	  <form action="sendSMS.php" method="post" name="sendtextform" onsubmit="return checkSMS();">
	    <p>
	      <textarea name="message" class="smsTextArea" id="message"  onChange="checkmsg(this)" onFocus="checkmsg(this)" onKeyDown="checkmsg(this)" ></textarea>
</p>
	    <p>	      
	      Number of characters remaining:
	      <input name="CNT" type="text" class="formfield" id="CNT" value="160" size="3" maxlength="3">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="submit" name="Submit" value="Send SMS Message to your Send List" />
	    </p>
      </form>
	  
	   
	  <?php 
	  break;
	  
	  case 2:
	  
	  ?>
	  
	  <p class="STOP"><img src="images/warning.jpg" alt="Please make an SMS list" width="85" height="76" align="middle" /> You do not have any credits in your account please contact support!  </p>
	  <?php 
	  break;
	  
	  case 3:
	  
	  ?>
	  
	  <p class="STOP"><img src="images/warning.jpg" alt="Please make an SMS list" width="85" height="76" align="middle" /> </p>
	  <p class="STOP">You have insufficient credits to send to your list, please remove some contacts or contact support to top up your credits! </p>
	  <?php 
	  break;
	  
	  case 4:
	  
	  ?>
	  
	  <p class="STOP"><img src="images/warning.jpg" alt="Please make an SMS list" width="85" height="76" align="middle" /> You have not created an SMS list to send to!</p>
	    <?php 
		break;
		} ?>
      </p>
	  <?php if (isset($statusOutput) && ($statusOutput!="")) { ?>
	  <p><?php 
	  echo $statusOutput;
	  ?>
	  </p>
	  <?php } ?>
	  <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td colspan="3" align="center" class="tableTitle">Database Summary </td>
      </tr>
      <tr>
        <td align="center">Database Contacts </td>
        <td align="center">SMS Credits </td>
        <td align="center">[ <a href="sentMessages.php">Sent Messages</a> ] </td>
      </tr>
      <tr>
        <td align="center"><?php echo $GLOBALS['dbContacts']; ?></td>
        <td align="center"><?php echo $GLOBALS['smsCredits']; ?></td>
        <td align="center"><?php echo $GLOBALS['sentMessages']; ?></td>
      </tr>
    </table></td>
    <td ></td>
  </tr>
  <tr>
    <td class="bl"></td>
	<td></td>
    <td class="br"></td>
  </tr>
</table>

<table width="760" border="0" align="center" cellpadding="0" cellspacing="0" class="tabBack">
<tr><td height="5" colspan="3" class="background" ></td>
  <tr>
    <td class="tl"></td>
    <td rowspan="2" align="center" class="loginInfo">Logged in as: <?php echo $_SESSION['MM_Contact']; ?> of <?php echo $_SESSION['MM_Company']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ <a href="menu.php">HOME</a> ] &nbsp;&nbsp;[ <a href="<?php echo $logoutAction ?>">LOG OUT</a> ]  &nbsp;&nbsp;[&nbsp;STATUS:
      <?php 
	switch ($_SESSION['smsTesting']) {
case 1:
    echo "Testing Mode";
    break;
case 2:
    echo "Live Light";
    break;
case 4:
    echo "Live Bulk";
    break;
}
	
	 ?>
] </td>
    <td class="tr"></td>
  </tr>
  
  <tr>
    <td class="bl"></td>
    <td class="br"></td>
  </tr>
</table>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-3517413-4";
urchinTracker();
</script>
</body>
</html>
<?php


mysql_free_result($rsContacts);

mysql_free_result($rsSMSList);

function buildStats() {
$connUKTIsms = mysql_pconnect("localhost", "uktisms", "76-3NwTx/4");

$pid_rsDatabaseStats = "1";
if (isset($_SESSION['MM_ProfileID'])) {
  $pid_rsDatabaseStats = (get_magic_quotes_gpc()) ? $_SESSION['MM_ProfileID'] : addslashes($_SESSION['MM_ProfileID']);
}
$dbT_rsDatabaseStats = "-1";
if (isset($_SESSION['MM_DBTable'])) {
  $dbT_rsDatabaseStats = (get_magic_quotes_gpc()) ? $_SESSION['MM_DBTable'] : addslashes($_SESSION['MM_DBTable']);
}
mysql_select_db("uktisms", $connUKTIsms);
$query_rsDatabaseStats = sprintf("select smsCredits, smsTesting, csvUIDcol, csvSMScol, csvNoCols, (select count(a.profileID) from %s as a where a.profileID = cp.profileID) as dbContacts, (select count(id) from tblsentmessages as sm where sm.profileID=cp.profileID and sm.smsTest<>1) as sentMessages from tblclientprofile as cp where cp.profileID=%s;", $dbT_rsDatabaseStats ,GetSQLValueString($pid_rsDatabaseStats, "int"));
$rsDatabaseStats = mysql_query($query_rsDatabaseStats, $connUKTIsms) or die(mysql_error());
$row_rsDatabaseStats = mysql_fetch_assoc($rsDatabaseStats);
$totalRows_rsDatabaseStats = mysql_num_rows($rsDatabaseStats);

$GLOBALS['dbContacts'] = $row_rsDatabaseStats['dbContacts'];
$GLOBALS['smsCredits'] = $row_rsDatabaseStats['smsCredits'];
$GLOBALS['sentMessages'] = $row_rsDatabaseStats['sentMessages'];
//mysql_free_result(rsDatabaseStats);

}

function canWeSMS($inlist) {
/*
Return codes for canWeSMS($int)

1: Yes we can SMS
2: No Credits
3: Insufficient credits to send list
4: No list selected

*/


$retError =1; // 1 = Yes we can SMS / 0 = No we can't SMS

if ($GLOBALS['smsCredits']==0) {
	$retError = 2; // Account has ZERO credits
}

if (($GLOBALS['smsCredits']<$inlist) && ($GLOBALS['smsCredits']!=0)) {
	$retError = 3; // Not enough credits to send list
}

if ($inlist==0) {
	$retError = 4; // No list selected
}

return $retError;
}

function retDelStatus($code) {

switch ($code) {

case 1:
	return "<span class=\"GO\">SMS sent</span>";
	break;
case 3:
	return "<span class=\"STOP\">SMS failed!</span>";
	break;
}
}
?>
