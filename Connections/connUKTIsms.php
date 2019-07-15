<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connUKTIsms = "localhost";
$database_connUKTIsms = "uktisms";
$username_connUKTIsms = "uktisms";
$password_connUKTIsms = "76-3NwTx/4";
$connUKTIsms = mysql_pconnect($hostname_connUKTIsms, $username_connUKTIsms, $password_connUKTIsms) or trigger_error(mysql_error(),E_USER_ERROR); 
?>