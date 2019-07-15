<?php require_once('../Connections/connUKTIsms.php'); ?>
<html>
<head>
</head>
<body>
<?php
//echo phpInfo();
?>
<?php
/*$row = 1;
$handle = fopen("upload/Offshore Masteredit.csv", "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    echo "<p> $num fields in line $row: <br /></p>\n";
    $row++;
    for ($c=0; $c < $num; $c++) {
        echo $data[$c] . "<br />\n";
    }
}
fclose($handle);*/
?> 
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

if (!isset($_SESSION)) {
  session_start();
}

$deleteSQL = 'DELETE FROM %s where attendeeID IN (%s)';

if (isset($_POST['addTo']) && ($_POST['addTo'])) {
	echo 'add pressed';
} else {
	echo 'add not pressed';
}
if (isset($_POST['deleteFrom']) && ($_POST['deleteFrom'])) {
	echo 'delete pressed';
} else {
	echo 'delete not pressed';
}
foreach ($_POST['addToList'] as $v) {
	$valueList .= GetSQLValueString($v,"text").",";
}
$deleteSQL = sprintf($deleteSQL, $_SESSION['MM_DBTable'], rtrim($valueList,','));
echo $deleteSQL;
?>

</body>
</html>