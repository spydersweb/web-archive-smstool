<?php require_once('../Connections/connUKTIsms.php'); ?>
<?php require_once('scripts/common.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Event Technologies :: SMS Tool :: Upload CSV File</title>
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
	<td class="texxtCopy"><p><strong>Upload CSV to Database </strong></p>
    <p>To make use of this tool, you must first load the database with your contact list according to your expected CSV (Comma Seperated Value) file settings. These settings determine the Unique Identifier of the contact, Number of Columns in the CSV file, and the column to use for sending SMS messages. </p>
    <p>The upload process will not format any SMS numbers, therefore each number to be used for text messaging must start with country code and the number cannot have any spaces. EG. 447*********. International numbers must also just start with the respective country code followed by the mobile number.</p>
    <form enctype="multipart/form-data" action="<?php echo $_SESSION['importScript']; ?>" method="POST" onSubmit="checkFileUpload(this,'csv',true,'','','','','','','');return document.MM_returnValue">
Select a CSV file to upload: 
  <input name="u" type="file" id="u" onChange="checkOneFileUpload(this,'csv',true,'','','','','','','')"/>
  <input type="submit" value="Upload file to Server" />
</form></p>
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      
      <tr>
        <td colspan="3" align="center" class="tableTitle">Expected CSV Upload Settings </td>
        </tr>
      <tr>
        <td align="center">Number of Columns Expected </td>
        <td align="center">Unique ID Column </td>
        <td align="center">SMS Number Column </td>
      </tr>
      <tr>
        <td align="center"><?php echo $row_rsDatabaseStats['csvNoCols']; ?></td>
        <td align="center"><?php echo $row_rsDatabaseStats['csvUIDcol']; ?></td>
        <td align="center"><?php echo $row_rsDatabaseStats['csvSMScol']; ?></td>
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

</body>
</html>
<?php
mysql_free_result($rsDatabaseStats);

?>
