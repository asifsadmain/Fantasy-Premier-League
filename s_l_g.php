<!DOCTYPE html>
<html>

<head>
    <title>DB_project_see_player_info</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/w3.css">
</head>

<body class="w3-container w3-aqua w3-margin w3-myfont w3-large">

    <head>
        <div class="w3-bar w3-black">
        
                <!--<h1><a href="index.php"><img src="image/logo.PNG" alt="cannot load image"></a></h1> -->
                <img src="image/logo.PNG" class="w3-bar-item">
                <br><br><br><br>
                <a href="profile2.php" class="w3-bar-item w3-button  w3-xlarge">Profile</a>
                <a href="s_l_g.php" class="w3-bar-item w3-grey w3-button w3-xlarge">League Table</a>
                <!--<a href="#" class="w3-bar-item w3-button w3-xlarge">Stats</a>!-->
                <div class="w3-xlarge w3-dropdown-hover w3-bar-item">
                    <button class="w3-button">Stats</button>
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

    <p class="w3-myfont w3-container w3-blue w3-center w3-xxlarge">CURRENT LEAGUE TABLE</p>

    <?php
		$conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
		$show = 'select team_name, games_played, won, drawn, lost, 
			     sum(won*3+drawn*1) points from team group by team_name, games_played, won, drawn, lost order by points desc';
		$query = oci_parse($conn, $show);
		oci_execute($query);
		echo '<table class="w3-table-all w3-container">';   
		echo"<tr class='w3-blue'><td>CLUB NAME</td><td>GAMES_PLAYED</td><td>WON</td><td>DRAWN</td><td>LOST</td><td>POINTS</td>\n";
		while (($row = oci_fetch_row($query))) {
			echo "<tr class='w3-hover-blue'>";
			echo "<td>$row[0]</td>";
			echo "<td>$row[1]</td>";
			echo "<td>$row[2]</td>";
			echo "<td>$row[3]</td>";
			echo "<td>$row[4]</td>";
			echo "<td>$row[5]</td>";
		}
		echo"</table>\n";
		oci_free_statement($query);
		oci_close($conn);
	?>
</body>

</html>
