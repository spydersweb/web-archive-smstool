<?php require_once('commonFunctions.php'); ?>
<?php
// Common Scripts

if (!isset($_SESSION)) {
  session_start();
}

$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";
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

$pid_rsDatabaseStats = "1";
if (isset($_SESSION['MM_ProfileID'])) {
  $pid_rsDatabaseStats = (get_magic_quotes_gpc()) ? $_SESSION['MM_ProfileID'] : addslashes($_SESSION['MM_ProfileID']);
}
$dbT_rsDatabaseStats = "-1";
if (isset($_SESSION['MM_DBTable'])) {
  $dbT_rsDatabaseStats = (get_magic_quotes_gpc()) ? $_SESSION['MM_DBTable'] : addslashes($_SESSION['MM_DBTable']);
}
mysql_select_db($database_connUKTIsms, $connUKTIsms);
$query_rsDatabaseStats = sprintf("select smsCredits, smsTesting, csvUIDcol, csvSMScol, csvNoCols, (select count(a.profileID) from %s as a where a.profileID = cp.profileID) as dbContacts, (select count(id) from tblsentmessages as sm where sm.profileID=cp.profileID and sm.smsTest<>1) as sentMessages from tblclientprofile as cp where cp.profileID=%s;", $dbT_rsDatabaseStats ,GetSQLValueString($pid_rsDatabaseStats, "int"));
$rsDatabaseStats = mysql_query($query_rsDatabaseStats, $connUKTIsms) or die(mysql_error());
$row_rsDatabaseStats = mysql_fetch_assoc($rsDatabaseStats);
$totalRows_rsDatabaseStats = mysql_num_rows($rsDatabaseStats);
?>