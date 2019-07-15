<?php
// common functions file


// GetSQLValueString

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

if (!function_exists("isAuthorized")) {
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
}

if (!function_exists("buildStats")) {
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
}
if (!function_exists("formatMobile")) {
	function formatMobile($mob) {
		//$mob = "+4477 967676 46";
		$mob = str_replace(" ","",$mob);
		$pos = strpos($mob,"7");
		$mob = substr($mob, $pos);
		return $mob;
	}
}

if (!function_exists("canWeSMS")) {
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
}

if (!function_exists("retDelStatus")) {
	function retDelStatus($code) {
	
		switch ($code) 
		{
			case 4:
				return "<span class=\"STOP\">Delivery Status Failed!</span>";
				break;
			case 40:
				return "<span class=\"STOP\">MessageID not recognised!</span>";
				break;
			case 41:
				return "<span class=\"AMBER\">Message being processed!</span>";
				break;
			case 43:
				return "<span class=\"STOP\">Message has been rejected</span>";
				break;
			case 45:
				return "<span class=\"GO\">Message has been delivered to the recipient handset!</span>";
				break;
			case 46:
				return "<span class=\"STOP\">Message has failed and will not be retried!</span>";
				break;
			case 47:
				return "<span class=\"AMBER\">Message has been queued, recipient handset may be switched off or out of service area!</span>";
				break;
			case 48:
				return "<span class=\"AMBER\">Message Delivered to network with no reports</span>";
				break;
			case 49:
				return "<span class=\"AMBER\">Message queued on gateway!</span>";
				break;
			case 60:
				return "<span class=\"STOP\">Recipient Number not recognised!</span>";
				break;
			case 61:
				return "<span class=\"STOP\">Recipient number contained in Opt-Out list, message not sent!</span>";
				break;
		}
	}
}
?>