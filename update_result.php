<?php
if(isset($_POST['submit']))
{
    $h_team = $_POST['home_team'];
    $a_team = $_POST['away_team'];
    $w_team = $_POST['w_team'];
    $l_team = $_POST['l_team'];
    
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');

    if($w_team==$l_team)
    {
        $show = "UPDATE FIXTURE SET WINNER_ID=0, LOSER_ID=0 WHERE HOME_TEAM_NAME='$h_team'";
    }
    else
    {
        $show = "UPDATE FIXTURE SET WINNER_ID=(SELECT TEAM_ID FROM TEAM WHERE TEAM_NAME='$w_team'), LOSER_ID=(SELECT TEAM_ID FROM TEAM WHERE TEAM_NAME='$l_team') WHERE HOME_TEAM_NAME='$h_team'";
    }
    
    $query = oci_parse($conn, $show);
    oci_execute($query);
    oci_close($conn);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Result</title>
    <link rel="stylesheet" href="css/w3.css" type="text/css">
</head>
<body class="w3-container w3-margin w3-padding w3-xlarge w3-myfont">
 <div class="w3-display-container">
     <a class="w3-display-topright" href="admin_login.php">Back to Admin Login</a>
 </div>
 
  <p class="w3-xxlarge w3-center w3-padding w3-margin">ADMIN AREA</p>
    <form action="update_result.php" method="post">
      <p class="w3-container w3-center w3-black">If the match is drawn, write "Drawn" in both winning and losing team name</p>
       <label class="w3-blue w3-padding">Enter Gameweek No.</label><br>
        <input class="w3-input w3-margin w3-padding " type="number" name="week" min="1" max="38" required><br>
        <input class="w3-button w3-hover-grey w3-margin" type="submit" name="go" value="go!!!">
    </form>
       <?php

if(isset($_POST['go']))
{ ?>
       <form action="update_result.php" method="post">
        <label class="w3-blue w3-padding">Enter Home Team Name</label><br>
        <input class="w3-input w3-margin w3-padding " type="text" name="home_team" required><br>
        <label class="w3-blue w3-padding">Enter Away Team Name</label><br>
        <input class="w3-input w3-margin w3-padding " type="text" name="away_team" required><br>
        <label class="w3-blue w3-padding">Enter Winning Team Name</label><br>
        <input class="w3-input w3-margin w3-padding " type="text" name="w_team" required><br>
        <label class="w3-blue w3-padding">Enter Losing Team Name</label><br>
        <input class="w3-input w3-margin w3-padding " type="text" name="l_team" required><br>
        <input class="w3-button w3-margin" type="submit" name="submit"><br><br>
    </form>
    <p class="w3-black w3-center w3-xxlarge">FIXTURES</p>
<?php
$week = $_POST['week'];
$conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');

$show = "SELECT HOME_TEAM_NAME, AWAY_TEAM_NAME FROM FIXTURE WHERE WEEK_NO='$week'";
    
$query = oci_parse($conn, $show);
oci_execute($query);

while (($row = oci_fetch_row($query))) {
?>
 <p class="w3-container w3-center w3-hover-grey"><?php echo $row[0] ?> VS. <?php echo $row[1] ?></p>
<?php } 
    oci_close($conn);
            
} ?>
</body>
</html>

