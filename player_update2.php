<!DOCTYPE html>
<html>

<head>
    <title>DB_project_player_info</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/w3.css">
</head>

<body class="w3-center">

    <?php

	if(isset($_POST['submit']) && isset($_POST['playername'])  && isset($_POST['club']))
	{    	
	
			
			$playername = $_POST['playername'];
			$club = $_POST['club'];
			//$rating = $_POST['rating'];
			//$value = $_POST['value'];
			//$injury = $_POST['injury'];
			if(isset($_POST['rating'])){
				$rating = $_POST['rating'];
				$conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
				$statement = "UPDATE PLAYERS SET RATING='$rating' WHERE PLAYER_NAME='$playername' AND CLUB='$club'";
				$query = oci_parse($conn, $statement);
				oci_execute($query);
			}

      if(isset($_POST['injury'])){
        $injury = $_POST['injury'];
        $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
        $statement = "UPDATE PLAYERS SET INJURY='$injury' WHERE PLAYER_NAME='$playername' AND CLUB='$club'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
      }


      if(isset($_POST['value'])){
        $value = $_POST['value'];
        $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
        $statement = "UPDATE PLAYERS SET TRANSFER_VALUE='$value' WHERE PLAYER_NAME='$playername' AND CLUB='$club'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        echo '<script type="text/javascript">alert("Successfully Updated");</script>';
      }
			oci_close($conn);
}

?>


        <div class="w3-card w3-border w3-myfont w3-xlarge w3-margin">
            <div class="w3-display-container">
                <a class="w3-display-topright" href="admin_login.php">Back to Admin Login</a>
            </div>
            <div class="w3-container w3-blue">
                <p class="w3-xxlarge w3-center w3-padding w3-margin">ADMIN AREA</p>
                <p class="w3-xxlarge">Player Informations</p>
            </div>
            <form action="Player_update.php" method="post" class="w3-container w3-left-align w3-margin">

                <label>PLAYER NAME</label>
                <input type="text" class="w3-input w3-margin-bottom" placeholder="Enter Player name" name="playername" value="<?php echo $_POST['playername'] ?>" required>

                <label>CLUB NAME</label>
                <input type="text" class="w3-input w3-margin-bottom" placeholder="Enter Club Name" name="club" value="<?php echo $_POST['playerclub'] ?>" required>

                <label>RATING</label>
                <input type="number" class="w3-input w3-margin-bottom" placeholder="Rating" name="rating" value="<?php echo $_POST['playerrating'] ?>">

                <label>TRANSFER VALUE</label>
                <input type="number" class="w3-input w3-margin-bottom" placeholder="Enter Transfer Value" name="value" value="<?php echo $_POST['playervalue'] ?>">

                <label>INJURY</label>
                <input type="text" class="w3-input w3-margin-bottom" placeholder="Enter Injury Status" name="injury" value="<?php echo $_POST['playerinjury'] ?>">

                <input class="w3-button w3-round-large w3-black w3-margin" type="Submit" name="submit">

            </form>
        </div>


</body>

</html>
