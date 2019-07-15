<?php require_once('../Connections/connUKTIsms.php'); ?>
<?php require_once('scripts/common.php'); ?>

<?php
$pid_rsContacts = "-1";
if (isset($_SESSION['MM_ProfileID'])) {
  $pid_rsContacts = (get_magic_quotes_gpc()) ? $_SESSION['MM_ProfileID'] : addslashes($_SESSION['MM_ProfileID']);
}
$id_rsContacts = "-1";
if (isset($_GET['uid'])) {
  $id_rsContacts = (get_magic_quotes_gpc()) ? $_GET['uid'] : addslashes($_GET['uid']);
}
mysql_select_db($database_connUKTIsms, $connUKTIsms);
$query_rsContacts = sprintf("SELECT * FROM tblattendees WHERE tblattendees.attRef = %s",GetSQLValueString($id_rsContacts, "text"));
$rsContacts = mysql_query($query_rsContacts, $connUKTIsms) or die(mysql_error());
$row_rsContacts = mysql_fetch_assoc($rsContacts);
$totalRows_rsContacts = mysql_num_rows($rsContacts);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Event Technologies :: SMS Tool :: Review Individual Contact</title>
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
	<td valign="top" class="texxtCopy"><p><strong>Review the Database Contacts </strong></p>
      <p>Listed below are the current contacts in the database that have been loaded. To return to the database review list <a href="review.php">click here</a> </p>
    </p>
    <table border="0" cellpadding="3" cellspacing="0">
      <tr>
        <td colspan="2" class="tableTitle">Details for <?php echo $row_rsContacts['title']; ?>&nbsp;<?php echo $row_rsContacts['firstname']; ?>&nbsp;<?php echo $row_rsContacts['surname']; ?></td>
        </tr>
      <tr>
        <td width="309">Booking Reference </td>
        <td width="405"><?php echo $row_rsContacts['bkRef']; ?></td>
        </tr>
      
        <tr>
          <td>Attendance Reference </td>
          <td><?php echo $row_rsContacts['attRef']; ?></td>
        </tr>
        <?php if (isset($row_rsContacts['organisation'])) {?>
        <tr>
          <td>Organisation</td>
          <td><?php echo $row_rsContacts['organisation']; ?></td>
        </tr>
		<?php }?>
		
		        <tr>
          <td>Address</td>
          <td><?php echo $row_rsContacts['line1']; ?><br />
            <?php echo $row_rsContacts['line2']; ?><br />
            <?php echo $row_rsContacts['line3']; ?><br />
            <?php echo $row_rsContacts['city']; ?><br />
            <?php echo $row_rsContacts['county']; ?><br />
            <?php echo $row_rsContacts['country']; ?><br />
            <?php echo $row_rsContacts['postcode']; ?></td>
        </tr>
		<?php if (isset($row_rsContacts['ooccupation'])) {?>
        <tr>
          <td>Occupation</td>
          <td><?php echo $row_rsContacts['occupation']; ?></td>
        </tr>
		<?php } ?>
		<?php if (isset($row_rsContacts['telephone'])) {?>
        <tr>
          <td>Telephone</td>
          <td><?php echo $row_rsContacts['telephone']; ?></td>
        </tr>
		<?php } ?>
		<?php if (isset($row_rsContacts['mobile'])) {?>
        <tr>
          <td>Mobile</td>
          <td><?php echo $row_rsContacts['mobile']; ?></td>
        </tr>
		<?php } ?>
		<?php if (isset($row_rsContacts['fax'])) {?>
        <tr>
          <td>Fax</td>
          <td><?php echo $row_rsContacts['fax']; ?></td>
        </tr>
		<?php } ?>
		<?php if (isset($row_rsContacts['email'])) {?>
        <tr>
          <td>Email</td>
          <td><?php echo $row_rsContacts['email']; ?></td>
        </tr><?php } ?>
		<?php if (isset($row_rsContacts['ticketType'])) {?>
        <tr>
          <td>Type of Ticket </td>
          <td><?php echo $row_rsContacts['ticketType']; ?></td>
        </tr>
		<?php } ?>
		<?php if (isset($row_rsContacts['attType'])) {?>
        <tr>
          <td>Type of Attendance </td>
          <td><?php echo $row_rsContacts['attType']; ?></td>
        </tr>
		<?php } ?>
		<?php if (isset($row_rsContacts['attStatus'])) {?>
        <tr>
          <td>Status of Attendance </td>
          <td><?php echo $row_rsContacts['attStatus']; ?></td>
        </tr>
		<?php } ?>
		<?php if (isset($row_rsContacts['dateAdded'])) {?>
        <tr>
          <td>Date Added</td>
          <td><?php echo $row_rsContacts['dateAdded']; ?></td>
        </tr>
        <?php } ?>
		<?php if (isset($row_rsContacts['dietReq'])) {?>
        <tr>
          <td>Dietry Requirements </td>
          <td><?php echo $row_rsContacts['dietReq']; ?></td>
        </tr>
		<?php } ?>
		<?php if (isset($row_rsContacts['dietTransfer'])) {?>
        <tr>
          <td>detTransfer</td>
          <td><?php echo $row_rsContacts['detTransfer']; ?></td>
        </tr>
		<?php } ?>
		<?php if (isset($row_rsContacts['noEmployees'])) {?>
        <tr>
          <td>Number of Employees </td>
          <td><?php echo $row_rsContacts['noEmployees']; ?></td>
        </tr>
		<?php } ?>
		<?php if (isset($row_rsContacts['delList'])) {?>
        <tr>
          <td>Delegate List </td>
          <td><?php echo $row_rsContacts['delList']; ?></td>
        </tr>
		<?php } ?>
		<?php if (isset($row_rsContacts['countriesInterest'])) {?>
        <tr>
          <td>Countries of Interest</td>
          <td><?php echo $row_rsContacts['countriesInterest']; ?></td>
        </tr>
		<?php } ?>
		<?php if (isset($row_rsContacts['exportExp'])) {?>
        <tr>
          <td>Export Experience </td>
          <td><?php echo $row_rsContacts['exportExp']; ?></td>
        </tr>
		<?php } ?>
		<?php if (isset($row_rsContacts['smsMobile'])) {?>
        <tr>
          <td>SMS Marketing Mobile</td>
          <td><?php echo $row_rsContacts['smsMobile']; ?></td>
        </tr>
		<?php } ?>
		<?php if (isset($row_rsContacts['events'])) {?>
        <tr>
          <td>Events</td>
          <td><?php echo $row_rsContacts['events']; ?></td>
        </tr>
		<?php } ?>
    </table>
    <br/>
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

mysql_free_result($rsContacts);

?>
