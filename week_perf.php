
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
     <link rel="stylesheet" href="css/w3.css">
</head>
<body class="w3-aqua w3-myfont w3-container w3-margin">

   <head>
        <div class="w3-bar w3-black">
        
                <!--<h1><a href="index.php"><img src="image/logo.PNG" alt="cannot load image"></a></h1> -->
                <img src="image/logo.PNG" class="w3-bar-item">
                <br><br><br><br>
                <a href="profile2.php" class="w3-bar-item w3-button  w3-xlarge">Profile</a>
                <a href="s_l_g.php" class="w3-bar-item w3-button w3-xlarge">League Table</a>
                <!--<a href="#" class="w3-bar-item w3-button w3-xlarge">Stats</a>!-->
                <div class="w3-xlarge w3-dropdown-hover w3-bar-item">
                    <button class="w3-button w3-grey">Stats</button>
                    <div class="w3-dropdown-content w3-bar-block w3-border">
                        <a href="week_perf.php" class="w3-bar-item w3-button">PLayer Performance</a>
                        <a href="gameweek_history.php" class="w3-bar-item w3-button">Game week points</a>
                    </div>
                </div>
                <a href="#" class="w3-bar-item w3-button w3-xlarge">Rules</a>
                <a href="leagues.php" class="w3-bar-item w3-button w3-xlarge">Leagues</a>
                    <form action="index.php" method="post">
                        <input class="w3-bar-item w3-right w3-button" type="submit" name="logout" value="Logout">
                    </form>
        </div>
    </head>
    <p class="w3-center w3-xxlarge">Player weekly Performance</p>
<?php

session_start();
$username = $_SESSION['user'];
    
$conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
$show = "SELECT US.PLAYER_NAME, US.CLUB, GH.WEEK_NO, GH.GOALS,  GH.ASSISTS,GH.PENALTY_SAVED,GH.PENALTY_MISSED,GH.OWN_GOAL,GH.RED_CARD,GH.YELLOW_CARD,GH.TACKLES,GH.MOM
FROM PLAYERS US JOIN WEEKLY_PERFORMANCE GH ON(US.PLAYER_ID=GH.PLAYER_ID)";
    
$query = oci_parse($conn, $show);
oci_execute($query);
  
echo "<table border='1' class='w3-table w3-xlarge w3-white'>\n";    
echo"<tr><td>PLAYER NAME</td><td>CLUB</td><td>WEEK NO</td><td>GOALS</td><td>ASSISTS</td><td>PENALTY SAVED</td><td>PENALTY MISSED</td><td>OWN GOAL</td><td>RED CARD</td><td>YELLOW CARD</td><td>TACKLES</td><td>MAN OF THE MATCH</td></tr>\n";
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
    echo "<td>";
    echo $row[8];
    echo "</td>";
    echo "<td>";
    echo $row[9];
    echo "</td>";
    echo "<td>";
    echo $row[10];
    echo "</td>";
    echo "<td>";
    echo $row[11];
    echo "</td>";
    echo "</tr>\n";
}

echo "</table>\n";
    
oci_free_statement($query);
oci_close($conn);

?>
</body>
</html>