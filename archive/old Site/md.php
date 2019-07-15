<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Untitled Document</title>
<script type="text/javascript" language="javascript" src="incPureUpload.js"></script>
<link href="styles/bracken.css" rel="stylesheet" type="text/css" />
</head>

<body class="background">
<form enctype="multipart/form-data" action="uploader.php" method="POST" onSubmit="checkFileUpload(this,'csv',true,'','','','','','','');return document.MM_returnValue">
Please choose a file: <input name="u" type="file" id="u" onChange="checkOneFileUpload(this,'csv',true,'','','','','','','')"/>
<br />
<input type="submit" value="Upload" />
</form>

<p><?php echo $row_Recordset1['bkRef']; ?></p>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-3517413-4";
urchinTracker();
</script>
</body>
</html>
