<?php require_once('../../Connections/connUKTIsms.php'); ?>
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

sleep(1);

$q_rsSearchQry = "-1";
if (isset($_GET['qry'])) {
  $q_rsSearchQry = (get_magic_quotes_gpc()) ? $_GET['qry'] : addslashes($_GET['qry']);
}

// Assume its a name query
$sql = sprintf("SELECT * FROM tblattendees where (tblattendees.firstname like CONCAT('%%', %s, '%%') OR tblattendees.surname like CONCAT('%%', %s, '%%')) and smsMobile<>'' and attRef not in (select uniqueID from tblsmslist where profileID = %s)", GetSQLValueString($q_rsSearchQry, "text"), GetSQLValueString($q_rsSearchQry, "text"),GetSQLValueString($_SESSION['MM_ProfileID'], "int") );

$qp_rsSearchQry = "-1";
if (isset($_GET['qp'])) {
  $qp_rsSearchQry = (get_magic_quotes_gpc()) ? $_GET['qp'] : addslashes($_GET['qp']);
  switch ($qp_rsSearchQry) {
  	case 1 : 
	$sql = sprintf("SELECT * FROM tblattendees where country = %s and smsMobile<>'' and attRef not in (select uniqueID from tblsmslist where profileID = %s)", GetSQLValueString($q_rsSearchQry, "text"),GetSQLValueString($_SESSION['MM_ProfileID'], "int"));
	break;
	case 2 :
	$sql = sprintf("SELECT * FROM tblattendees where organisation like concat('%%', %s ,'%%') and smsMobile<>'' and attRef not in (select uniqueID from tblsmslist where profileID = %s)", GetSQLValueString($q_rsSearchQry, "text"),GetSQLValueString($_SESSION['MM_ProfileID'], "int"));
	break;
	case 3 :
	$sql = sprintf("SELECT * FROM tblattendees where city = %s and smsMobile<>'' and attRef not in (select uniqueID from tblsmslist where profileID = %s)", GetSQLValueString($q_rsSearchQry, "text"),GetSQLValueString($_SESSION['MM_ProfileID'], "int"));
	break;
	case 4 :
	$sql = sprintf("SELECT * FROM tblattendees where email like CONCAT('%%', %s, '%%') and smsMobile<>'' and attRef not in (select uniqueID from tblsmslist where profileID = %s)", GetSQLValueString($q_rsSearchQry, "text"),GetSQLValueString($_SESSION['MM_ProfileID'], "int"));
	break;
	case 5 :
	$sql = sprintf("SELECT * FROM tblattendees where telephone like CONCAT('%%', %s, '%%') and smsMobile<>'' and attRef not in (select uniqueID from tblsmslist where profileID = %s)", GetSQLValueString($q_rsSearchQry, "text"),GetSQLValueString($_SESSION['MM_ProfileID'], "int"));
	break;
	
	case 6:
	$sql = sprintf("SELECT * FROM tblattendees where events like CONCAT('%%',%s,'%%') and smsMobile<>'' and attRef not in (select uniqueID from tblsmslist)",GetSQLValueString($q_rsSearchQry, "text"),GetSQLValueString($_SESSION['MM_ProfileID'], "int"));
	break;
	}
}

// and smsMobile<>'' and attRef not in (select uniqueID from tblsmslist)

mysql_select_db($database_connUKTIsms, $connUKTIsms);
$query_rsSearchQry = $sql;
$rsSearchQry = mysql_query($query_rsSearchQry, $connUKTIsms) or die(mysql_error());
$row_rsSearchQry = mysql_fetch_assoc($rsSearchQry);
$totalRows_rsSearchQry = mysql_num_rows($rsSearchQry);
?>

<?php 
//echo $q_rsSearchQry;
if ($totalRows_rsSearchQry > 0) { // Show if recordset not empty ?>
  <form name="add" method="post" action="createSMSlist.php">
    <table width="100%" border="0" cellspacing="0" cellpadding="3">
      <tr>
        <td colspan="4" align="center" class="tableTitle">Search Results (Query:<?php echo $q_rsSearchQry?>)</td>
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
        <td><a href="../ajx/revIndividual.php?uid=<?php echo $row_rsSearchQry['attRef']; ?>"><?php echo $row_rsSearchQry['title']; ?>&nbsp;<?php echo $row_rsSearchQry['firstname']; ?>&nbsp;<?php echo $row_rsSearchQry['surname']; ?></a></td>
        <td><?php echo $row_rsSearchQry['smsMobile']; ?></td>
        <td><input name="addToList[]" type="checkbox" id="addToList[]" value="<?php echo $row_rsSearchQry['attRef']; ?>"  checked="checked" /></td>
      </tr>
      <?php } while ($row_rsSearchQry = mysql_fetch_assoc($rsSearchQry));?>
      <tr><td colspan="4" align="right"><input name="sisAdd" type="hidden" id="sisAdd" value="true"/>
        <input name="submit" type="submit" id="submit" value="Add checked Contacts to SMS List" /></td>
      </tr>
    </table>
      </form>
  <?php } // Show if recordset not empty ?>
  
  <?php if ($totalRows_rsSearchQry == 0) { // Show if recordset empty ?>
  <table width="100%" border="0" cellspacing="0" cellpadding="3">
      <tr>
        <td colspan="4" align="center" class="tableTitle">Search Results (Query:<?php echo $q_rsSearchQry?>)</td>
      </tr>
      <tr >
        <td colspan="4">No results</td>
        
      </tr>
      <?php } // Show if recordset empty ?>
  
  
  
<?php
mysql_free_result($rsSearchQry);
?>
