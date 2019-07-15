<?php require_once('../Connections/connUKTIsms.php'); ?>
<?php require_once('scripts/common.php'); ?>

<?php

$pid_rsMessages = "-1";
if (isset($_SESSION['MM_ProfileID'])) {
  $pid_rsMessages = (get_magic_quotes_gpc()) ? $_SESSION['MM_ProfileID'] : addslashes($_SESSION['MM_ProfileID']);
}
mysql_select_db($database_connUKTIsms, $connUKTIsms);
$query_rsMessages = sprintf("SELECT distinct(tblsentmessages.ClientRef), message, tblsentmessages.dt, tblsentmessages.smsTest FROM tblsentmessages WHERE tblsentmessages.profileID = %s", GetSQLValueString($pid_rsMessages, "int"));
$rsMessages = mysql_query($query_rsMessages, $connUKTIsms) or die(mysql_error());
$row_rsMessages = mysql_fetch_assoc($rsMessages);
$totalRows_rsMessages = mysql_num_rows($rsMessages);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Event Technologies :: SMS Tool :: View list of Sent Messages</title>
<link href="styles/bracken.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript" src="scripts/date.js"></script>
<script type="text/javascript" language="JavaScript" src="scripts/incPureUpload.js">
</script>
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
	<td class="texxtCopy"><p><strong>Sent Messages </strong></p>
      <p>Below are a list of messages previously sent messagesl. To get a delivery status report for a specific message click on the MessageID. </p>
      <?php if ($totalRows_rsMessages > 0) { // Show if recordset not empty ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td width="300"><strong>Message</strong></td>
            <td><strong>MessgeID</strong></td>
            <td width="40" align="center" valign="middle">&nbsp;</td>
            <td><strong>Date / Time </strong></td>
          </tr>
          <?php do { ?>
          <tr>
            <td width="300"><?php echo $row_rsMessages['message']; ?></td>
            <td>[ <a href="statusCheck.php?mid=<?php echo $row_rsMessages['ClientRef']; ?>"><?php echo $row_rsMessages['ClientRef']; ?></a> ] </td>
            <td width="40" align="center" valign="middle"><img src="images/<?php echo $row_rsMessages['smsTest']; ?>.jpg" alt="Message Status" width="40" height="15" /></td>
            <td><?php echo $row_rsMessages['dt']; ?></td>
          </tr>
          <?php } while ($row_rsMessages = mysql_fetch_assoc($rsMessages)); ?>
</table>
        <?php } // Show if recordset not empty ?></p>
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
            <td align="center"><?php echo $row_rsDatabaseStats['dbContacts']; ?></td>
            <td align="center"><?php echo $row_rsDatabaseStats['smsCredits']; ?></td>
            <td align="center"><?php echo $row_rsDatabaseStats['sentMessages']; ?></td>
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
    <td rowspan="2" align="center" class="loginInfo">Logged in as: <?php echo $_SESSION['MM_Contact']; ?> of <?php echo $_SESSION['MM_Company']; ?> &nbsp;&nbsp; [ <a href="adduser.php">ADD USER</a> ]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ <a href="menu.php">HOME</a> ] &nbsp;&nbsp;[ <a href="<?php echo $logoutAction ?>">LOG OUT</a> ]  &nbsp;&nbsp;[&nbsp;STATUS:
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
mysql_free_result($rsDatabaseStats);

mysql_free_result($rsMessages);


?>
