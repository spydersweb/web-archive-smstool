<?php require_once('../Connections/connUKTIsms.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "menu.php";
  $MM_redirectLoginFailed = "index.php?err=1";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_connUKTIsms, $connUKTIsms);
  
  $LoginRS__query=sprintf("SELECT * FROM tblclientprofile as p, tblogin as l WHERE p.profileID=l.profileID and l.username=%s AND l.password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $connUKTIsms) or die(mysql_error());
  $row_LoginRS = mysql_fetch_assoc($LoginRS);
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	 
	
	$_SESSION['MM_ProfileID'] = $row_LoginRS['profileID'];
	$_SESSION['MM_Company'] = $row_LoginRS['compName'];
	$_SESSION['MM_Contact'] = $row_LoginRS['compContact'];
	$_SESSION['MM_Logo'] = $row_LoginRS['compLogo'];
	$_SESSION['MM_Address'] = $row_LoginRS['compAddress'];
	$_SESSION['MM_CSVUID'] = $row_LoginRS['csvUIDcol'];
	$_SESSION['MM_SMSCol'] = $row_LoginRS['csvSMScol'];
	$_SESSION['MM_CSVNoCols'] = $row_LoginRS['csvNoCols'];
	$_SESSION['MM_DBTable'] = $row_LoginRS['csvDBtable'];
	$_SESSION['csvColHeaders'] = $row_LoginRS['csvColHeaders'];
	$_SESSION['compCampaign'] = $row_LoginRS['compCampaign'];
	$_SESSION['importScript'] = $row_LoginRS['importScript'];
	$_SESSION['smsTesting'] = $row_LoginRS['smsTesting'];
	
	$_SESSION['clientID'] = "PU0729377";
	$_SESSION['clientPass'] = "smstool";
	$_SESSION['originator'] = "BrackenPres";
	$_SESSION['sesImp'] = 1;

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
  session_destroy();
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Event Technologies :: SMS Tool :: SMS Tool </title>
<link href="styles/bracken.css" rel="stylesheet" type="text/css" />
</head>

<body class="background"><table width="760" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td width="347"><img name="etlogoonBlack" src="images/etlogoonBlack.jpg" width="245" height="96" border="0" id="etlogoonBlack" alt="Event Technologies Logo" /></td>
    <td width="413" align="center" valign="bottom"><img src="images/SMS.jpg" alt="CLIENT SMS TOOL" width="211" height="13" /></td>
  </tr>
  <tr>
    <td height="66" colspan="2" align="center">To use this tool please login using your credentials below. </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
	
	
	
	<?php
	$lf = $loginFormAction;
	$f = "<form ACTION=\"$lf\" METHOD=\"POST\" name=\"login\" id=\"login\"><table width=\"435\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"border\"><tr><td width=\"148\" align=\"right\">Username:</td><td width=\"263\"><input name=\"username\" type=\"text\" id=\"username\" /></td></tr><tr><td align=\"right\">Password:</td><td><input name=\"password\" type=\"password\" id=\"password\" /></td></tr><tr><td>&nbsp;</td><td><input type=\"submit\" name=\"Submit\" value=\"login\" /></td></tr></table></form>";
	?>
	
	<script language="javascript" type="text/javascript">
	<!--
	document.write('<?php echo addslashes($f);?>');
	
	-->
	</script><noscript > Please use a browser that supports javaScript</noscript>
	
	</td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="error"><?php 
	if (isset($_GET["err"])) {
	if ($_GET["err"]==1) { echo "Username / Password was not recognised!";}
	if ($_GET["err"]==2) { echo "You have been successfully logged out!";}
	}?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-3517413-4";
urchinTracker();
</script>
</body>
</html>