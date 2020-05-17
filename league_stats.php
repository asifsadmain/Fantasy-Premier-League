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
$username = $_SESSION['user'];

if(isset($_POST['stats']))
{
    $league_id=$_POST['hidden_league'];
    $week=$_POST['week'];
    
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    $insert = "SELECT LEAGUE_NAME FROM LEAGUE WHERE LEAGUE_ID='$league_id'";
        
    $query = oci_parse($conn, $insert);
    oci_execute($query);
    
    while($row=oci_fetch_row($query))
    {
        $l_name = $row[0];
    }
    
    $insert = "SELECT US.USERNAME, GH.POINTS FROM USERLIST US JOIN GAMEWEEK_HISTORY GH ON(US.USER_ID=GH.USER_ID) JOIN USERLEAGUES LG ON (GH.USER_ID=LG.USER_ID) WHERE LG.LEAGUE_ID='$league_id' AND GH.WEEK_NO='$week' ORDER BY GH.POINTS DESC";
        
    $query = oci_parse($conn, $insert);
    oci_execute($query);
}
    
echo "<h2 class='w3-center'>";
echo "League:  ";
echo $l_name;
echo "</h2>";

echo "<table class='w3-table-all w3-container' border='1'>\n";
    
    
echo"<tr><td>USERNAME</td><td>POINTS</td></tr>\n";
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

?>

</body>
</html>