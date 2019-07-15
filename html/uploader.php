<?php require_once('../Connections/connUKTIsms.php'); ?>
<?php require_once('scripts/common.php'); ?>
<?php

$colname_rsDBFields = "-1";
if (isset($_SESSION['MM_ProfileID'])) {
  $colname_rsDBFields = (get_magic_quotes_gpc()) ? $_SESSION['MM_ProfileID'] : addslashes($_SESSION['MM_ProfileID']);
}
mysql_select_db($database_connUKTIsms, $connUKTIsms);
$query_rsDBFields = sprintf("SELECT dbField FROM tblcsvmappings WHERE profileID = %s", GetSQLValueString($colname_rsDBFields, "int"));
$rsDBFields = mysql_query($query_rsDBFields, $connUKTIsms) or die(mysql_error());
$row_rsDBFields = mysql_fetch_assoc($rsDBFields);
$totalRows_rsDBFields = mysql_num_rows($rsDBFields);
?>
<?php
// Assemble the database fields as a string 
$dbFields ="";
$counter =0;
$dbPlaceHolders = "";
do { 
	$dbFields .= $row_rsDBFields['dbField'].","; 
	$counter +=1;
	$dbPlaceHolders .= "%s,";
} while ($row_rsDBFields = mysql_fetch_assoc($rsDBFields)); 
$dbFields = $dbFields."profileID";
$dbPlaceHolders .= "%s";
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Event Technologies :: SMS Tool :: SMS Tool :: Uploading SMS Data from CSV File</title>
<link href="styles/bracken.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript" src="scripts/date.js"></script>
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
	<td valign="top" class="texxtCopy"><p><strong>Database Loading Tool </strong><?php echo($counter); ?></p>
      <p><?php 
$target = "upload/"; 
$target = $target . basename( $_FILES['u']['name']) ; 
$ok=1; 

//This is our size condition 
if ($u_size > 350000) 
{ 
echo "Your file is too large.<br>"; 
$ok=0; 
} 

//This is our limit file type condition 
if ($u_type =="text/php") 
{ 
echo "No PHP files<br>"; 
$ok=0; 
} 


//Here we check that $ok was not set to 0 by an error 
if ($ok==0) 
{ 
Echo "<p class=\"STOP\">Sorry your file was not uploaded</p>"; 
} 

//If everything is ok we try to upload it 
else 
{ 
if(move_uploaded_file($_FILES['u']['tmp_name'], $target)) 
{ 
echo "<p class=\"GO\">The file ". basename( $_FILES['u']['name']). " has been uploaded</p>"; 

// Now lets split the csv and enter the data into the database
$filetoopen = $target;

$insSQL = sprintf("insert into %s (%s) values ",$_SESSION['MM_DBTable'],$dbFields);

$sqlValues = "(".$dbPlaceHolders."),";
$sql ="";
$row = 1;
$numcols = $_SESSION['MM_CSVNoCols'];
$importRows=0;
$handle = fopen($filetoopen, "r");
while (($data = fgetcsv($handle,0)) !== FALSE) {
    $num = count($data);
	
	if ($num<39) {
		
		$data = array_pad ( $data, 39, 39-$num );
		$num = count($data);
		}
		
    if ($row!=1) { // We do not want to import the column headers
	
		if ($num==$numcols) { // We expect to import 39 columns, if this is not the case we skip the row
			
			echo "<span class=\"GO\">Row $row contains the correct number of columns attempting import of data!</span><br/>";
			if ($data[1]) { // Ensure we are importing a row with a unique identifier
				
				$bk_rstips = "-1";
				if (isset($data[($_SESSION['MM_CSVUID']-1)])) {
  					$bk_rstips = (get_magic_quotes_gpc()) ? $data[($_SESSION['MM_CSVUID']-1)] : addslashes($data[($_SESSION['MM_CSVUID']-1)]);
				}
				
				$query_rstips = sprintf("SELECT * FROM %s WHERE attRef=%s", $_SESSION['MM_DBTable'],GetSQLValueString($bk_rstips, "text"));
				$rstips = mysql_query($query_rstips, $connUKTIsms) or die(mysql_error());
				$row_rstips = mysql_fetch_assoc($rstips);
				$totalRows_rstips = mysql_num_rows($rstips);
				
				
				if ($totalRows_rstips == 0) { // If the record has already been imported we skip it
				
					echo "<span class=\"GO\">$num fields in line $row with Unique Identifier ".$data[($_SESSION['MM_CSVUID']-1)].":</span> <br />\n";
					
					// Lets assign the variables according to thier position
					$sql = sprintf($sqlValues,
					GetSQLValueString($data[0],"text"),
					GetSQLValueString($data[1],"text"),
					GetSQLValueString($data[2],"text"),
					GetSQLValueString($data[3],"text"),
					GetSQLValueString($data[4],"text"),
					GetSQLValueString($data[5],"text"),
					GetSQLValueString($data[6],"text"),
					GetSQLValueString($data[7],"text"),
					GetSQLValueString($data[8],"text"),
					GetSQLValueString($data[9],"text"),
					GetSQLValueString($data[10],"text"),
					GetSQLValueString($data[11],"text"),
					GetSQLValueString($data[12],"text"),
					GetSQLValueString($data[13],"text"),
					GetSQLValueString($data[14],"text"),
					GetSQLValueString($data[15],"text"),
					GetSQLValueString($data[16],"text"),
					GetSQLValueString($data[17],"text"),
					GetSQLValueString($data[18],"text"),
					GetSQLValueString($data[19],"text"),
					GetSQLValueString($data[20],"text"),
					GetSQLValueString($data[21],"text"),
					GetSQLValueString($data[22],"text"),
					GetSQLValueString($data[23],"text"),
					GetSQLValueString($data[24],"text"),
					GetSQLValueString(strtolower($data[25]),"text"),
					GetSQLValueString($data[26],"text"),
					GetSQLValueString($data[27],"text"),
					GetSQLValueString(str_replace (" ", "", $data[28]),"text"),
					GetSQLValueString(sprintf("%s,%s,%s,%s,%s,%s,%s,%s,%s,%s",$data[29],$data[30],$data[31],$data[32],$data[33],$data[34],$data[35],$data[36],$data[37],$data[38]),"text"),
					GetSQLValueString($_SESSION['MM_ProfileID'],"int")
					);
					//echo $sql;
					//mysql_select_db($database_connUKTIsms, $connUKTIsms);
					// Insert the row into the database
					$Result1 = mysql_query(trim($insSQL.$sql,","), $connUKTIsms) or die(mysql_error());
					
					// Increment the import counter
					$importRows++;
					
				} else {
				echo "<span class=\"STOP\">The Unique Identifier : ".$data[($_SESSION['MM_CSVUID']-1)]." has been found in the database this row was not imported!</span><br/>";
				}
				
				mysql_free_result($rstips); // Delete the record
				
			} else {
			echo "<span class=\"STOP\">No Unique Identifier found not importing this row!</span><br/>";
			}
		}
		
	}
   
    $row++;
    
}
fclose($handle);
//echo trim($insSQL.$sql,",");
if ($importRows>0) {
echo "<p class=\"GO\">$importRows of $row imported</p><p>To review the database <a href=\"review.php\">click here</a>";
} else {
echo "<p class=\"STOP\">No Rows were imported</p>";
}

} 
else 
{ 
echo "<span class=\"STOP\">Sorry, there was a problem uploading your file.</span>"; 
} 
} 
?> 
      </p>
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

</body>
</html>
<?php
mysql_free_result($rsDBFields);
?>
