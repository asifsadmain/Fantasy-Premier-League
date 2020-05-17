
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<?php

$conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
$show = "SELECT USERNAME,LEAGUE_NAME,USER_TEAM,COUNTRY,AGE,EMAIL,MONEY,WILD_CARD FROM USERLIST";
    
$query = oci_parse($conn, $show);
oci_execute($query);



//$row = oci_fetch_row($query);   
echo "<table border='1'>\n";
    
    
echo"<tr><td>USERNAME</td><td>LEAGUE_NAME</td><td>USER_TEAM</td><td>COUNTRY</td><td>AGE</td><td>EMAIL</td><td>MONEY</td><td>WILD_CARD</td> </tr>\n";
while (($row = oci_fetch_row($query))) {
    echo "<tr>\n";
    echo "<td>";
    echo $row[0];
    echo "</td>";
    echo "<td>";
    echo $row[1];
    echo "</td>";
    echo "<td>";
    echo $row[2];
    echo "</td>";
    echo "<td>";
    echo $row[3];
    echo "</td>";
    echo "<td>";
    echo $row[4];
    echo "</td>";
    echo "<td>";
    echo $row[5];
    echo "</td>";
    echo "<td>";
    echo $row[6];
    echo "</td>";
    echo "<td>";
    echo $row[7];
    echo "</td>";
    echo "</tr>\n";
}

echo "</table>\n";
    
oci_free_statement($query);
oci_close($conn);

?>
</body>
</html>