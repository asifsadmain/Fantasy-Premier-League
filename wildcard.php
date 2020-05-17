<?php

session_start();
$username = $_SESSION['user'];

$current_week = $_SESSION['current_week'];

$conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
$show = "SELECT WILD_CARD FROM USERLIST WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username')";
$query = oci_parse($conn, $show);
oci_execute($query);

while($row=oci_fetch_row($query))
{
    $wildcard = $row[0];
}
if($wildcard==0)
{
$show = "DELETE FROM SQUAD WHERE WEEK_NO='$current_week' AND USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username')";
    
$query = oci_parse($conn, $show);
oci_execute($query);
    
$show = "UPDATE USERLIST SET MONEY=10000 WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username')";
    
$query = oci_parse($conn, $show);
oci_execute($query);

$show = "UPDATE USERLIST SET WILD_CARD=1 WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username')";
echo '<script type="text/javascript">alert("u have successfully used wildcard!");</script>';
    
$query = oci_parse($conn, $show);
oci_execute($query);
}
else
{
    echo '<script type="text/javascript">alert("sorry!u have already used wildcard!");</script>';
}

?>