<?php require_once('../Connections/connUKTIsms.php'); ?>
<?php require_once('scripts/common.php'); ?>
<?php require_once('scripts/smsScripts.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Event Technologies :: SMS Tool :: Send SMS Message</title>
<link href="styles/bracken.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript" src="scripts/date.js"></script>
<script language="javascript" type="text/javascript" src="scripts/smsLengthCheck.js"></script>
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
	mysql_free_result($rsSMSList);
?>
