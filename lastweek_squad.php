
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="css/w3.css">
</head>
<body>
<?php
session_start();
$week = $_SESSION['current_week'];
$username = $_SESSION['user'];
    
if($week==0)
{
    echo '<script type="text/javascript">alert("this is the first week");</script>';
}
else
{
$last_week = $week-1;
$conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
$show = "SELECT PL.PLAYER_NAME, SQ.P_POS FROM PLAYERS PL JOIN SQUAD SQ ON(PL.PLAYER_ID=SQ.PLAYER_ID) WHERE SQ.USER_ID = (SELECT USER_ID FROM USERLIST WHERE USERNAME='$username') AND WEEK_NO='$last_week'";
    
$query = oci_parse($conn, $show);
oci_execute($query);


//while(($row = oci_fetch_array($query,OCI_BOTH))!=false){
  //  echo $row['USERNAME'] . "<br>";
    
//
    


//$row = oci_fetch_row($query);   
echo "<table class=\"w3-table w3-grey\">\n";
    
    
echo"<tr><td>PLAYER NAME</td><td>POSITION</td></tr>\n";
while (($row = oci_fetch_row($query))) {
    echo "<tr>\n";
    echo "<td>";
    echo $row[0];
    echo "</td>";
    echo "<td>";
    echo $row[1];
    echo "</td>";
    echo "</tr>\n";
}

echo "</table>\n";
    
oci_free_statement($query);
oci_close($conn);
}
?>
</body>
</html>