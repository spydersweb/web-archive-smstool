<?php require_once('../Connections/connUKTIsms.php'); ?>
<?php require_once('scripts/commonFunctions.php'); ?>
<?php require_once('scripts/common.php'); ?>

<?php


if ((isset($_POST["uname"])) && ($_POST['uname']) && (isset($_POST["pwd"])) && ($_POST['pwd']) && (isset($_POST["profileID"])) && ($_POST['profileID']) && (isset($_POST["contName"])) && ($_POST['contName'])) {


	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
	
	
	  $insertSQL = sprintf("INSERT INTO tblogin (username, password, profileID, compContact) VALUES (%s, %s, %s, %s)",
						   GetSQLValueString($_POST['uname'], "text"),
						   GetSQLValueString($_POST['pwd'], "text"),
						   GetSQLValueString($_POST['profileID'], "int"),
						   GetSQLValueString($_POST['contName'], "text"));
	
	  mysql_select_db($database_connUKTIsms, $connUKTIsms);
	  $Result1 = mysql_query($insertSQL, $connUKTIsms) or die(mysql_error());
	
	  $insertGoTo = "addUser.php";
	  if (isset($_SERVER['QUERY_STRING'])) {
		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
		$insertGoTo .= $_SERVER['QUERY_STRING'];
	  }
	  //header(sprintf("Location: %s", $insertGoTo));
	$error = "The user ".$_POST['contName']." has now been added to the database, with password: ".$_POST['pwd']."!";
}
 	
?>





<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Event Technologies :: SMS Tool :: SMS Tool :: Uploading SMS Data from CSV File</title>
<link href="styles/bracken.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript" src="scripts/date.js"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script type="text/javascript" src="SpryAssets/SpryValidationConfirm.js"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
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
</td></tr></table><!-- #EndLibraryItem --><table width="760" border="0" align="center" cellpadding="0" cellspacing="0" class="tabBack">
  <tr><td height="5" colspan="3" class="background" ></td>
  </tr>
  <tr>
    <td class="tl"></td>
    <td ></td>
    <td class="tr"></td>
  </tr>
  <tr>
    <td></td>
	<td valign="top" class="texxtCopy"><p><strong>ADD A NEW USER FOR <?php echo strtoupper($_SESSION['MM_Company']); ?></strong></p>
      <form method="POST" action="<?php echo $editFormAction; ?>" name="form"><table width="80%" border="0" align="center" cellpadding="5" cellspacing="0">
        <tr>
          <td width="25%">Contact Name</td>
          <td width="75%"><span id="sprytextfield1">
            <input type="text" name="contName" id="contName" value="" />
            <span class="textfieldRequiredMsg">A value is required.</span></span> </td>
        </tr>
        <tr>
          <td>Username</td>
          <td><span id="sprytextfield2">
            <input type="text" name="uname" id="username" value="" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
        </tr>
        <tr>
          <td>Password</td>
          <td><span id="sprytextfield3">
            <input type="password" name="pwd" id="password" value=""/>
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
        </tr>
        <tr>
          <td>Confirm Password</td>
          <td>
          


         <span id="ConfirmWidget">

        
            <input type="password" name="password2" id="password2" value="" />
            <span class="confirmRequiredMsg">A value is required.</span>
            <span class="confirmInvalidMsg">The passwords do not match!</span>

         </span></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>All fields are required!</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input name="addUser" type="submit" id="addUser" value="Add New User" />
            <input name="profileID" type="hidden" id="profileID" value="<?php echo $_SESSION['MM_ProfileID']; ?>" /></td>
        </tr>
        <tr>
          <td colspan="2"><?php if (isset($error) && ($error)) { echo $error; } ?></td>
          </tr>
      </table>
        <input type="hidden" name="MM_insert" value="form" />
      </form>      
      <p>&nbsp;</p>
    </td>
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
    <td rowspan="2" align="center" class="loginInfo">Logged in as: <?php echo $_SESSION['MM_Contact']; ?> of <?php echo $_SESSION['MM_Company']; ?> &nbsp;&nbsp; [ <a href="adduser.php">ADD USER</a> ]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ <a href="menu.php">HOME</a> ] &nbsp;&nbsp;[<a href="<?php echo $logoutAction ?>"> LOG OUT</a> ]  &nbsp;&nbsp;[&nbsp;STATUS:
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




<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["blur"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["blur"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {validateOn:["blur"]});
var sprytextfield4 = new Spry.Widget.ValidationTextField("ConfirmWidget", "none", {validateOn:["blur"]});
var ConfirmWidgetObject = new Spry.Widget.ValidationConfirm("ConfirmWidget", "password", {validateOn:["blur"]});
//-->
</script>
</body>
</html>

