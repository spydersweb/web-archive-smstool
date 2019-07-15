<html>
<head>
</head>
<body>
<?php
echo phpInfo();
?>
<?php
$row = 1;
$handle = fopen("upload/Offshore Masteredit.csv", "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    echo "<p> $num fields in line $row: <br /></p>\n";
    $row++;
    for ($c=0; $c < $num; $c++) {
        echo $data[$c] . "<br />\n";
    }
}
fclose($handle);
?> 

</body>
</html>