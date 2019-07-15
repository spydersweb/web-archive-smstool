<?php 
$URI = "https://www.secpay.com/java-bin/ValCard";

$fields = array(

	'merchant' => 'bracke01',//$_SESSION['clientID'],
	'trans_id' => '0002',//$_SESSION['clientPass'],
	'amount' => '95',//$messageID,
	'callback' => 'http://www.meetthebuyer.co.uk'
);

// Fire off the Post to Text AnyWhere and capture the response
/*
do_post_request($URI, $fields);

  function do_post_request($url, $data, $optional_headers = null)
  {
     $params = array('http' => array(
                  'method' => 'POST',
                  'content' => $data
               ));
     if ($optional_headers !== null) {
        $params['http']['header'] = $optional_headers;
     }
     $ctx = stream_context_create($params);
     $fp = @fopen($url, 'rb', false, $ctx);
     if (!$fp) {
        throw new Exception("Problem with $url, $php_errormsg");
     }
     $response = @stream_get_contents($fp);
     if ($response === false) {
        throw new Exception("Problem reading data from $url, $php_errormsg");
     }
     return $response;
  }*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<?php echo phpinfo();?>
</body>
</html>
