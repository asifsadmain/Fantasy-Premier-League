<?php
if(isset($_POST['submit']))
{
    $password = $_POST['password'];
    if($password=='adminpassword')
    {
        $_SESSION['admin'] = "admin";
    }
    else
    {
        echo "<h3>Enter The Correct Password!</h3>";
    }
}
?>

<?php
if(isset($_POST['w_start']))
{
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    $show = "BEGIN SQUAD_COPY2; END;";
    $query = oci_parse($conn, $show);
    oci_execute($query);
    
    $show = "UPDATE USERLIST SET CHANGES=0 WHERE USER_ID IS NOT NULL";
    $query = oci_parse($conn, $show);
    oci_execute($query);
    
    /*$show = "INSERT INTO squad (user_id, player_id, p_pos, week_no)
            SELECT user_id, player_id,p_pos, max(week_no)+1
            FROM squad
            group by user_id,player_id,p_pos";
    $query = oci_parse($conn, $show);
    oci_execute($query);*/
    
    
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="css/w3.css" type="text/css">
</head>
<body class="w3-container w3-myfont w3-margin w3-padding w3-grey">
    <?php
    if(isset($_SESSION['admin'])){
    ?>
    <p class="w3-xxlarge w3-center w3-padding w3-margin">ADMIN AREA</p>
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
    ?>
    <p class="w3-xxlarge w3-center w3-padding w3-margin">CURRENT GAMEWEEK:<?php echo $currentWeek ?></p>
    <a class="w3-button w3-margin" href="confirmation.php">Start New Week</a><br><br>
    <a class="w3-button w3-margin" href="show_players.php">Update Weekly Performance</a><br><br>
    <a class="w3-button w3-margin" href="player_info.php">Insert Player Information</a><br><br>
    <a class="w3-button w3-margin" href="player_update1.php">Update Player Information</a><br><br>
    <a class="w3-button  w3-margin" href="update_fixture.php">Update Fixture</a><br><br>
    <form action="index.php" method="post">
        <input class="w3-button w3-margin" type="submit" name="a_submit" value="Log Out">
    </form>
    <?php } 
    else{
    ?>
    <form action="admin_login.php" method="post">
        <input class="w3-input w3-margin" type="password" name="password" placeholder="enter admin password" required>
        <input class="w3-margin w3-button" type="submit" name="submit" value="Log In">
    </form>
    <?php } ?>
</body>
</html>