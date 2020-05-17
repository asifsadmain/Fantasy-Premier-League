<?php
if(isset($_POST['submit_fix']))
{
    $week = $_POST['week'];
    $h_team = $_POST['home_team'];
    $a_team = $_POST['away_team'];
    $deadline = $_POST['deadline'];
    
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');

    $show = "INSERT INTO FIXTURE(WEEK_NO, HOME_TEAM_ID, AWAY_TEAM_ID, HOME_TEAM_NAME, AWAY_TEAM_NAME, DEADLINE) VALUES('$week',(SELECT TEAM_ID FROM TEAM WHERE TEAM_NAME='$h_team'), (SELECT TEAM_ID FROM TEAM WHERE TEAM_NAME='$a_team'),'$h_team', '$a_team', TIMESTAMP '$deadline')";
    
    $query = oci_parse($conn, $show);
    oci_execute($query);
    oci_close($conn);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Fixture</title>
    <link rel="stylesheet" href="css/w3.css" type="text/css">
</head>
<body class="w3-container w3-margin w3-padding w3-xlarge w3-myfont">
 <div class="w3-display-container">
     <a class="w3-display-topright" href="admin_login.php">Back to Admin Login</a>
 </div>
  <p class="w3-xxlarge w3-center w3-padding w3-margin">ADMIN AREA</p>
    <form action="update_fixture.php" method="post">
       <label class="w3-blue w3-padding">Enter Gameweek No.</label><br>
        <input class="w3-input w3-margin w3-padding " type="number" name="week" min="1" max="38" required><br>
        <label class="w3-blue w3-padding">Enter Home Team Name</label><br>
        <input class="w3-input w3-margin w3-padding " type="text" name="home_team" required><br>
        <label class="w3-blue w3-padding">Enter Away Team Name</label><br>
        <input class="w3-input w3-margin w3-padding " type="text" name="away_team" required><br>
        <label class="w3-blue w3-padding">Enter Deadline</label><br>
        <input class="w3-input w3-margin w3-padding " type="text" name="deadline" value="2018-01-25 00:00:00" required><br>
        <input class="w3-button w3-margin" type="submit" name="submit_fix"><br><br>
    </form>
    <p class="w3-center w3-xxlarge w3-black">FIXTURES</p>
    <?php


$conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
$show = "SELECT MAX(WEEK_NO) FROM SQUAD";
    
$query = oci_parse($conn, $show);
oci_execute($query);
$currentWeek = 0;
        
while($row=oci_fetch_row($query))
{
    $currentWeek = $row[0];
}

$show = "SELECT HOME_TEAM_NAME, AWAY_TEAM_NAME FROM FIXTURE WHERE WEEK_NO='$currentWeek'";
    
$query = oci_parse($conn, $show);
oci_execute($query);

while (($row = oci_fetch_row($query))) {
?>
 <p class="w3-container w3-center w3-hover-grey"><?php echo $row[0] ?> VS. <?php echo $row[1] ?></p>
<?php } 
    oci_close($conn);
            
        ?>
    <a class="w3-hover-blue" href="update_result.php">Update Result</a>
</body>
</html>

