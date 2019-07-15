<%@LANGUAGE="VBSCRIPT" CODEPAGE="65001"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<%
Dim xmlhttp
    Set xmlhttp = server.Createobject("MSXML2.ServerXMLHTTP")

    xmlhttp.Open "POST","http://ws.textanywhere.net/HTTPRX/SendSMSEx.aspx"
    xmlhttp.setRequestHeader "Content-Type", "application/x-www-form-urlencoded"
    xmlhttp.send("Client_ID=PU0729377&Client_Pass=smstool&Client_Ref=123456&Billing_Ref=BrackenPresentationsLtd&Connection=2&Originator=BrackenPres&Type=0&DestinationEx=%2b447796767646&Body=hello%20world&SMS_Type=0&Reply_Type=0")

    Response.Write xmlhttp.responseText
%>
$_SESSION['clientID'] = "PU0729377";
	$_SESSION['clientPass'] = "smstool";
	$_SESSION['originator'] = "BrackenPres";
	$_SESSION['sesImp'] = 1;
</body>
</html>
