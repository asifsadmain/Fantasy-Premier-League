
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="css/w3.css" type="text/css">
</head>
<body class="w3-container w3-aqua w3-margin w3-padding w3-myfont">

<p class="w3-center w3-xxlarge">List of Midfielders</p>
<?php
session_start();

$username = $_SESSION['user'];
$current_week = $_SESSION['current_week'];

$conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
$show = "select max(week_no) from squad";
    
$query = oci_parse($conn, $show);
oci_execute($query);
    
while($row=oci_fetch_row($query))
{
    $current_week = $row[0];
}

if($current_week==null)
{
    $current_week = 0;
}

$show = "SELECT DISTINCT DEADLINE FROM FIXTURE WHERE WEEK_NO='$current_week'";
$query = oci_parse($conn, $show);
oci_execute($query);
    
$deadline=0;
while($row=oci_fetch_row($query))
    {
        $deadline = $row[0];
    }
$show = "SELECT SYSTIMESTAMP FROM DUAL";
$query = oci_parse($conn, $show);
oci_execute($query);
    
while($row=oci_fetch_row($query))
{
    $systime = $row[0];
}

if(($systime<$deadline) || ($current_week==0))
{
$show = "select player_id from squad
where P_POS='LCM' and user_id=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username')";
    
$query = oci_parse($conn, $show);
oci_execute($query);
    
$count = 0;
    
while($row=oci_fetch_row($query))
{
    $count=$count+1;
    $player_id = $row[0];
}
    
if($count==0){
    $show = "SELECT PLAYER_NAME,AGE,CLUB,POS,RATING,TRANSFER_VALUE,INJURY FROM PLAYERS WHERE POS='CM' OR POS='CDM'";
}
else
{
    $show = "SELECT PLAYER_NAME,AGE,CLUB,POS,RATING,TRANSFER_VALUE,INJURY FROM PLAYERS WHERE POS='CM' OR POS='CDM' and player_id<>'$player_id'";
}
    
$query = oci_parse($conn, $show);
oci_execute($query);

//echo $_SESSION['players'];


//while(($row = oci_fetch_array($query,OCI_BOTH))!=false){
  //  echo $row['USERNAME'] . "<br>";
    
//
    


//$row = oci_fetch_row($query);   
echo "<table border='1' class='w3-table'>\n";
    
    
echo"<tr><td>PLAYER NAME</td><td>AGE</td><td>CLUB</td><td>POSITION</td><td>RATING</td><td>TRANSFER VALUE</td><td>INJURY</td></tr>\n";
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
    ?>
    <form action="profile2.php" method="post">
        <input type="hidden" name="hidden_lcm" value="<?php  echo $row[0]; ?>">   
        <input type="submit" class='w3-button w3-hover-blue' name="addLCM" value="Add">
    </form>
    <?php
    echo "</td>";
    echo "</tr>\n";
}

echo "</table>\n";
    
oci_free_statement($query);
oci_close($conn);
}
else
{
    echo '<script type="text/javascript">alert("Sorry, The Deadline is Over!");</script>';
}

?>
</body>
</html>