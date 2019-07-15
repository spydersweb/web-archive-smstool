<?php require_once('../Connections/connUKTIsms.php'); ?>
<?php require_once('scripts/common.php'); ?>

<?php 
if ((isset($_GET['del'])) && ($_GET['del'] != "")) {

	if ($_GET['del']=="all") {
	$deleteSQL = sprintf("DELETE FROM tblsmslist where profileID=%s",
                       GetSQLValueString($_SESSION['MM_ProfileID'], "int"));
	} else {
	  $deleteSQL = sprintf("DELETE FROM tblsmslist WHERE uniqueID=%s and profileID=%s",
                       GetSQLValueString($_GET['uid'], "text"),GetSQLValueString($_SESSION['MM_ProfileID'], "int"));
	}

  mysql_select_db($database_connUKTIsms, $connUKTIsms);
  $Result1 = mysql_query($deleteSQL, $connUKTIsms) or die(mysql_error());

}
// Perform a delete if necessary
if (isset($_POST['deleteFrom']) && ($_POST['deleteFrom']))
{
	
	$delSQL = 'DELETE a.*, l.* FROM %s a left join tblsmslist l on a.attRef = l.uniqueID WHERE a.attRef IN (%s);';
	if ((isset($_POST['addToList'])) && ($_POST['addToList'])) {
		foreach ($_POST['addToList'] as $v)
		{
			$valueList .= GetSQLValueString($v,"text").",";
		}
		$delSQL = sprintf($delSQL, $_SESSION['MM_DBTable'], rtrim($valueList,','));
		mysql_select_db($database_connUKTIsms, $connUKTIsms);
  		$Result2 = mysql_query($delSQL, $connUKTIsms) or die(mysql_error());
		header('Location: review.php');
	}
}

// Run the import of UserID's to sms Send List
if (isset($_POST['addTo']) && ($_POST['addTo']))
{
	$valueList ="";
	$importtotal=0;
	if (isset($_POST['addToList']) && ($_POST['addToList']))
	{
		foreach ($_POST['addToList'] as $v)
		{
			$check = insertVal($v,$_SESSION['MM_ProfileID']);
			if ($check==0) { $valueList .= "(".GetSQLValueString($v,"text").")";$importtotal++;}
		}
	}

}


$colname_rsContacts = "-1";
if (isset($_SESSION['MM_ProfileID'])) {
  $colname_rsContacts = (get_magic_quotes_gpc()) ? $_SESSION['MM_ProfileID'] : addslashes($_SESSION['MM_ProfileID']);
}
mysql_select_db($database_connUKTIsms, $connUKTIsms);
$query_rsContacts = sprintf("SELECT * FROM tblattendees2 WHERE attRef in (select uniqueID from tblsmslist where profileID = %s)", GetSQLValueString($colname_rsContacts, "int"));
$rsContacts = mysql_query($query_rsContacts, $connUKTIsms) or die(mysql_error());
$row_rsContacts = mysql_fetch_assoc($rsContacts);
$totalRows_rsContacts = mysql_num_rows($rsContacts);


mysql_select_db($database_connUKTIsms, $connUKTIsms);
$query_rsMisc1 = "SELECT distinct(misc1) FROM tblattendees2 order by misc1 asc";
$rsMisc1 = mysql_query($query_rsMisc1, $connUKTIsms) or die(mysql_error());
$row_rsMisc1 = mysql_fetch_assoc($rsMisc1);
$totalRows_rsMisc1 = mysql_num_rows($rsMisc1);

mysql_select_db($database_connUKTIsms, $connUKTIsms);
$query_rsMisc2 = "SELECT distinct(misc2) FROM tblattendees2 order by misc2 asc";
$rsMisc2 = mysql_query($query_rsMisc2, $connUKTIsms) or die(mysql_error());
$row_rsMisc2 = mysql_fetch_assoc($rsMisc2);
$totalRows_rsMisc2 = mysql_num_rows($rsMisc2);

mysql_select_db($database_connUKTIsms, $connUKTIsms);
$query_rsMisc3 = "SELECT distinct(misc3) FROM tblattendees2 order by misc3 asc";
$rsMisc3 = mysql_query($query_rsMisc3, $connUKTIsms) or die(mysql_error());
$row_rsMisc3 = mysql_fetch_assoc($rsMisc3);
$totalRows_rsMisc3 = mysql_num_rows($rsMisc3);

mysql_select_db($database_connUKTIsms, $connUKTIsms);
$query_rsMisc4 = "SELECT distinct(misc4) FROM tblattendees2 order by misc4 asc";
$rsMisc4 = mysql_query($query_rsMisc4, $connUKTIsms) or die(mysql_error());
$row_rsMisc4 = mysql_fetch_assoc($rsMisc4);
$totalRows_rsMisc4 = mysql_num_rows($rsMisc4);

mysql_select_db($database_connUKTIsms, $connUKTIsms);
$query_rsOrgs = "SELECT distinct(organisation) FROM tblattendees2 order by organisation asc";
$rsOrgs = mysql_query($query_rsOrgs, $connUKTIsms) or die(mysql_error());
$row_rsOrgs = mysql_fetch_assoc($rsOrgs);
$totalRows_rsOrgs = mysql_num_rows($rsOrgs);
/*
mysql_select_db($database_connUKTIsms, $connUKTIsms);
$query_rsEvents = "SELECT events FROM tblattendees";
$rsEvents = mysql_query($query_rsEvents, $connUKTIsms) or die(mysql_error());
$row_rsEvents = mysql_fetch_assoc($rsEvents);
$totalRows_rsEvents = mysql_num_rows($rsEvents);
*/
$sQry_rsSearchQry = "-1";
if (isset($_POST['qry'])) {
  $sQry_rsSearchQry = (get_magic_quotes_gpc()) ? $_POST['qry'] : addslashes($_POST['qry']);
}
$pid_rsSearchQry = "-1";
if (isset($_SESSION['MM_ProfileID'])) {
  $pid_rsSearchQry = (get_magic_quotes_gpc()) ? $_SESSION['MM_ProfileID'] : addslashes($_SESSION['MM_ProfileID']);
}

$sql = "";
if (isset($_POST['ft'])) {
$sql = sprintf("Select * from tblattendees where match(countriesInterest,events) against (%s) and smsMobile<>'' and attRef not in  (select uniqueID from tblsmslist where profileID=%s)", GetSQLValueString($sQry_rsSearchQry, "text"),GetSQLValueString($pid_rsSearchQry, "int"));
} else {
$sql = sprintf("SELECT * FROM tblattendees WHERE (countriesInterest LIKE CONCAT('%%', %s, '%%') OR events LIKE CONCAT('%%', %s, '%%')) and smsMobile<>'' and attRef not in (select uniqueID from tblsmslist where profileID = %s)", GetSQLValueString($sQry_rsSearchQry, "text"),GetSQLValueString($sQry_rsSearchQry, "text"), GetSQLValueString($pid_rsSearchQry, "int"));
}
mysql_select_db($database_connUKTIsms, $connUKTIsms);

$query_rsSearchQry = $sql;
$rsSearchQry = mysql_query($query_rsSearchQry, $connUKTIsms) or die(mysql_error());
$row_rsSearchQry = mysql_fetch_assoc($rsSearchQry);
$totalRows_rsSearchQry = mysql_num_rows($rsSearchQry);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Event Technologies :: SMS Tool :: Create SMS List</title>
<link href="styles/bracken.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="scripts/date.js"></script>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript">

<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}



function getXMLHTTPRequest() 
{
var req = false;
try 
  {
   req = new XMLHttpRequest(); /* e.g. Firefox */
  } 
catch(err1) 
  {
  try 
    {
     req = new ActiveXObject("Msxml2.XMLHTTP");  /* some versions IE */
    } 
  catch(err2) 
    {
    try 
      {
       req = new ActiveXObject("Microsoft.XMLHTTP");  /* some versions IE */
      } 
      catch(err3) 
        {
         req = false;
        } 
    } 
  }
return req;
}

// Create HTTP Object
var reqConst = getXMLHTTPRequest();
var im;

if (!reqConst) {
alert('There was a problem creating a request object');
//}else{
//alert('Got object');
} //else {
//alert("done");
//}



function qryD(value,qryParam) {

// catch the query string 
var qry = escape(value);
//alert (qryParam);
//
//alert(qry);
// One or two parameters passed to the script
if (!qryParam) {
var qryURL = 'ajx/qry.php?qry='+qry;
im = 'im0';
} else {
var qryURL = 'ajx/qry.php?qry='+qry+'&qp='+qryParam;
im = "im"+ qryParam;
}

// send the request to the page
reqConst.open("GET", qryURL, true);

// Update the page
reqConst.onreadystatechange = getConst;

// Send the request
reqConst.send(null);
}



function getConst() {

if (reqConst.readyState==4) {
//
document.getElementById('userInfo').innerHTML = '';

	if (reqConst.status ==200) {
		
			// change in html property of div tag
			
			var serverResponse = reqConst.responseText;
			document.getElementById('userInfo').innerHTML =serverResponse;
			
			for (i=0;i!=7;i++){
			var g = "im"+i;
			document.getElementById(g).innerHTML ='<img src=\"images\/spacer.gif\" width=\"20\" height=\"20\" \/>';
			}
			
		} else {
			
			// Error on response from server
			MM_changeProp('userInfo','','innerHTML','','DIV');
			alert('There has been an error');
		}
	} else {
		
			// Waiting on ready state change
			document.getElementById(im).innerHTML = "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0\" width=\"20\" height=\"20\"><param name=\"movie\" value=\"images/timer.swf\" /><param name=\"quality\" value=\"high\" /><param name=\"wmode\" value=\"transparent\" /><embed src=\"images/timer.swf\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"20\" height=\"20\" wmode=\"transparent\"></embed></object>";
			
			}
} 



function MM_changeProp(objName,x,theProp,theValue) { //v6.0
  var obj = MM_findObj(objName);
  if (obj && (theProp.indexOf("style.")==-1 || obj.style)){
    if (theValue == true || theValue == false)
      eval("obj."+theProp+"="+theValue);
    else eval("obj."+theProp+"='"+theValue+"'");
  }
}


//-->
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
	<td valign="top" class="texxtCopy"><p><strong>Create SMS List </strong><br />
	  To search for a specific group of contacts enter your search criteria below. Please note that for a contact to be returned in the search results it must have an SMS number entered against it and not already be in the SMS Send List. Should you wish to delete a contact from the send list simply click the <a href="review.php?uid=<?php echo $row_rsContacts['attRef']; ?>" onclick="return sure('You are attempting to delete a contact from the database.\nThis means no messages can be sent to this contact via SMS message.\n\nThis contact can be inserted into the database again by uploading the original CSV file.\n\nAre you sure you wish to proceed with this action\nYes (Continue) / No (Cancel)');"><img src="images/cross.jpg" alt="Delete this Item" width="16" height="16" border="0" /></a> next to the contact. You will not be asked to confirm this action.</p>
      

            
            <?php 
			if ($totalRows_rsSearchQry > 0) { // Show if recordset not empty 
			?>
			<form name="add" method="post" action="createSMSlist.php">
			<table width="100%" border="0" cellspacing="0" cellpadding="3">
            <tr>
              <td colspan="4" align="center" class="tableTitle">Search Results              </td>
            </tr>
			<tr>
              <td><strong>Unique ID </strong></td>
              <td><strong> Name </strong></td>
              <td><strong>SMS Marketing Number </strong></td>
              <td><strong>Add</strong></td>
            </tr>
			
			<?php
			do { ?>
              <tr>
                <td><?php echo $row_rsSearchQry['attRef']; ?></td>
                <td><a href="revIndividual.php?uid=<?php echo $row_rsSearchQry['attRef']; ?>"><?php echo $row_rsSearchQry['title']; ?>&nbsp;<?php echo $row_rsSearchQry['firstname']; ?>&nbsp;<?php echo $row_rsSearchQry['surname']; ?></a></td>
                <td><?php echo $row_rsSearchQry['smsMobile']; ?></td>
                <td><input name="addToList[]" type="checkbox" id="addToList[]" value="<?php echo $row_rsSearchQry['attRef']; ?>" /></td>
              </tr>
              <?php } while ($row_rsSearchQry = mysql_fetch_assoc($rsSearchQry));?>
			  <tr><td colspan="4" align="right"><input name="sisAdd" type="hidden" id="sisAdd" value="true" />
			    <input name="submit" type="submit" id="submit" value="Add checked Contacts to SMS List" /></td>
			  </tr>
			  </table>
	  </form>
			  <?php 
			  } // Show if recordset not empty
			  
			  ?>
			  
			  <?php if (isset($_POST['qry'])&&($totalRows_rsSearchQry == 0)) { // Show if recordset is empty ?>
			  <table width="100%" border="0" cellspacing="0" cellpadding="3">
            <tr>
              <td colspan="4" align="center" class="tableTitle">Search Results              </td>
            </tr>
			
			  <tr><td colspan="4" class="STOP">No Results found</td></tr>
              </table>
	  <?php } // Show if recordset is empty ?>
	  
	  <!-- Insert the tabbed panels here -->
	    <div id="TabbedPanels1" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Current SMS Send List</li>
          <li class="TabbedPanelsTab" tabindex="0"><strong>Search</strong></li>
          
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
		  <table width="100%" border="0" cellspacing="0" cellpadding="3">
            <tr>
              <td colspan="4" align="center" class="tableTitle">Current SMS Send List 
              <?php 

if (isset($importtotal)) {echo "<span class=\"STOP\">$importtotal contacts where added to the SMS send list</span>";}
?></td>
            </tr>
            
            <?php 
			if ($totalRows_rsContacts > 0) { // Show if recordset not empty 
			?>
			<tr>
              <td><strong>Unique ID </strong></td>
              <td><strong> Name </strong></td>
              <td><strong>SMS Marketing Number </strong></td>
              <td>[ <a href="createSMSList.php?del=all">ALL</a> ] </td>
            </tr>
			
			<?php
			do { ?>
              <tr>
                <td><?php echo $row_rsContacts['attRef']; ?></td>
                <td><a href="revIndividual.php?uid=<?php echo $row_rsContacts['attRef']; ?>"><?php echo $row_rsContacts['title']; ?>&nbsp;<?php echo $row_rsContacts['firstname']; ?>&nbsp;<?php echo $row_rsContacts['surname']; ?></a></td>
                <td><?php echo $row_rsContacts['smsMobile']; ?></td>
                <td><a href="createSMSlist.php?uid=<?php echo $row_rsContacts['attRef']; ?>&amp;del=1">
                <img src="images/cross.jpg" alt="Remove this contact from the SMS List" width="16" height="16" border="0" /></a></td>
              </tr>
              <?php } while ($row_rsContacts = mysql_fetch_assoc($rsContacts)); 
			  } // Show if recordset not empty
			  ?>
			  <?php if ($totalRows_rsContacts == 0) { // Show if recordset is empty ?>
			  <tr><td colspan="4" class="STOP">SMS send list is currently empty</td></tr>
			  <?php } // Show if recordset is empty ?>
            </table>
	    </div>
          <div class="TabbedPanelsContent">
		  <!-- Quick Search -->
		        <form id="form1" name="form1" method="post" action="createSMSlist.php">
		          
		          
   
      
      <table width="100%" border="0" cellspacing="0" cellpadding="3">
        <tr>
          <td width="320">Type and search by name: </td>
          <td width="21">&nbsp;</td>
          
          <td width="146">Misc 1</td>
          <td width="21">&nbsp;</td>
          <td width="124">Misc 2</td>
          <td width="9">&nbsp;</td>
        </tr>
        <tr>
          <td width="320"><input name="qry" type="text" id="qry" onkeyup="qryD(this.value);"/>            </td>
          <td width="21"><div id="im0"><img src="images/spacer.gif" width="20" height="20" /></div></td>
          
          <td width="146"><select name="misc1" id="misc1" onchange="qryD(this.options[this.selectedIndex].value,'7');">
		  <option value="">Please Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rsMisc1['misc1']?>"><?php echo $row_rsMisc1['misc1']?></option>
            <?php
} while ($row_rsMisc1 = mysql_fetch_assoc($rsMisc1));
  $rows = mysql_num_rows($rsMisc1);
  if($rows > 0) {
      mysql_data_seek($rsMisc1, 0);
	  $row_rsMisc1 = mysql_fetch_assoc($rsMisc1);
  }
?>
          </select> </td>
          <td width="21"><div id="im1"><img src="images/spacer.gif" alt="" width="20" height="20" /></div></td>
          <td width="124"><select name="misc2" id="misc2" onchange="qryD(this.options[this.selectedIndex].value,'8');">
		  <option value="">Please Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rsMisc2['misc2']?>"><?php echo $row_rsMisc2['misc2']?></option>
            <?php
} while ($row_rsMisc2 = mysql_fetch_assoc($rsMisc2));
  $rows = mysql_num_rows($rsMisc2);
  if($rows > 0) {
      mysql_data_seek($rsMisc2, 0);
	  $row_rsMisc2 = mysql_fetch_assoc($rsMisc2);
  }
?>
          </select></td>
          <td width="9"><div id="im2"><img src="images/spacer.gif" alt="" width="20" height="20" /></div></td>
        </tr>
        <tr>
          <td>Select from Organisation: </td>
          <td>&nbsp;</td>
          <td>Misc 3</td>
          <td width="21">&nbsp;</td>
          <td> Misc 4</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><select name="orgs" id="orgs" onchange="qryD(this.options[this.selectedIndex].value,'11');">
		  <option value="">Please Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rsOrgs['organisation']?>"><?php echo $row_rsOrgs['organisation']?></option>
            <?php
} while ($row_rsOrgs = mysql_fetch_assoc($rsOrgs));
  $rows = mysql_num_rows($rsOrgs);
  if($rows > 0) {
      mysql_data_seek($rsOrgs, 0);
	  $row_rsOrgs = mysql_fetch_assoc($rsOrgs);
  }
?>
          </select>
          
          
          
          </td>
          <td><div id="im5"><img src="images/spacer.gif" alt="" width="20" height="20" /></div></td>
          <td><select name="misc3" id="misc3" onchange="qryD(this.options[this.selectedIndex].value,'9');">
		  <option value="">Please Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rsMisc3['misc3']?>"><?php echo $row_rsMisc3['misc3']?></option>
            <?php
} while ($row_rsMisc3 = mysql_fetch_assoc($rsMisc3));
  $rows = mysql_num_rows($rsMisc3);
  if($rows > 0) {
      mysql_data_seek($rsMisc3, 0);
	  $row_rsMisc3 = mysql_fetch_assoc($rsMisc3);
  }
?>
          </select></td>
          <td width="21"><div id="im3"><img src="images/spacer.gif" alt="" width="20" height="20" /></div></td>
          <td><select name="misc4" id="misc4" onchange="qryD(this.options[this.selectedIndex].value,'10');">
		  <option value="">Please Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rsMisc4['misc4']?>"><?php echo $row_rsMisc4['misc4']?></option>
            <?php
} while ($row_rsMisc4 = mysql_fetch_assoc($rsMisc4));
  $rows = mysql_num_rows($rsMisc4);
  if($rows > 0) {
      mysql_data_seek($rsMisc4, 0);
	  $row_rsMisc4 = mysql_fetch_assoc($rsMisc4);
  }
?>
          </select></td>
          <td><div id="im4"><img src="images/spacer.gif" alt="" width="20" height="20" /></div></td>
        </tr>
      </table>
      <script type="text/javascript" language="javascript" >
	<!--
	//if (!reqConst) {document.write('<input type=\"submit\" name=\"Submit\" value=\"Search\" />');}
	//-->
	</script>
		        </form>
	    </div>
          
        </div>
      </div>
	  <!-- End tabbed panel insertion -->
    <script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
//-->
</script>
	    
    </td>
    <td ></td>
  </tr>
  <tr><td></td>
  <td><div id="userInfo"></div></td>
  <td></td>
  </tr>
  <tr>
    <td></td>
    <td valign="top" class="texxtCopy"><table width="100%" border="0" cellspacing="0" cellpadding="5">
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
    <td rowspan="2" align="center" class="loginInfo">Logged in as: <?php echo $_SESSION['MM_Contact']; ?> of <?php echo $_SESSION['MM_Company']; ?> &nbsp;&nbsp; [ <a href="adduser.php">ADD USER</a> ]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ <a href="menu.php">HOME</a> ] &nbsp;&nbsp;[<a href="<?php echo $logoutAction ?>"> LOG OUT</a> ]  &nbsp;&nbsp;[&nbsp;STATUS:
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

mysql_free_result($rsMisc1);

mysql_free_result($rsMisc2);

mysql_free_result($rsMisc3);

mysql_free_result($rsMisc4);

mysql_free_result($rsOrgs);


function insertVal($v,$pid) {
$connUKTIsms = mysql_pconnect("localhost", "uktisms", "76-3NwTx/4");

mysql_select_db("uktisms", $connUKTIsms);
$query_rsCheckVal = sprintf("SELECT * FROM tblsmslist WHERE uniqueID = %s and profileID=%s", GetSQLValueString($v, "text"), GetSQLValueString($pid, "int"));
$rsCheckVal = mysql_query($query_rsCheckVal, $connUKTIsms) or die(mysql_error());
$totalRows_rsCheckVal = mysql_num_rows($rsCheckVal);

	// Confirm its not in
	if ($totalRows_rsCheckVal==0) {
	mysql_free_result($rsCheckVal);
	
		$query_rsInsVal = sprintf("insert into tblsmslist(profileID,uniqueID) values (%s,%s)", GetSQLValueString($pid, "int"), GetSQLValueString($v, "text"));
		$result1 = mysql_query($query_rsInsVal, $connUKTIsms) or die(mysql_error());
		
		
    return 0; // Not found in database
	} else {
	mysql_free_result($rsCheckVal);
    return 1; // Found in database
	}

}


?>
