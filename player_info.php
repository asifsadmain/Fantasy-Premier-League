<?php

if(isset($_POST['submit']) && isset($_POST['playername']) && isset($_POST['age']) && isset($_POST['club']) && isset($_POST['pos']) && isset($_POST['rating']) && isset($_POST['value']))
{
    echo "YOU HAVE SUCCESSFULLY ENTERED ALL INFORMATIONS!!!";
    
    $playername = $_POST['playername'];
    $age = $_POST['age'];
    $club = $_POST['club'];
    $pos = $_POST['pos'];
    $rating = $_POST['rating'];
    $value = $_POST['value'];
    
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    
    $insert = "INSERT INTO PLAYERS (PLAYER_NAME, AGE, CLUB,                           POS,RATING,TRANSFER_VALUE,INJURY)
            VALUES ('$playername','$age','$club','$pos','$rating','$value','NO')";
        
    $query = oci_parse($conn, $insert);
    oci_execute($query);
    
    oci_close($conn);
    
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="css/w3.css" type="text/css">
</head>
<body class="w3-container w3-margin w3-padding w3-xlarge w3-myfont">
 <div class="w3-display-container">
     <a class="w3-display-topright" href="admin_login.php">Back to Admin Login</a>
 </div>
  <p class="w3-xxlarge w3-center w3-padding w3-margin">ADMIN AREA</p>
   
   <form action="player_info.php" method="post">
       
       Playername:<br> <input class="w3-margin-bottom w3-input" type="text" placeholder="Enter Player name" name="playername" required><br>
       Age: <br><input class="w3-margin-bottom w3-input" type="number" placeholder="Enter Age" name="age" required><br>
       Club Name: <br><input  class="w3-margin-bottom w3-input" type="text" placeholder="Enter Club Name" name="club" required><br>
       Position: <br> <input class="w3-margin-bottom w3-input" type="text" placeholder="Enter Position Name" name="pos" required><br>
       Rating: <br> <input class="w3-margin-bottom w3-input" type="number" placeholder="Rating" name="rating" required><br>
       Transfer Value: <br> <input class="w3-margin-bottom w3-input" type="number" placeholder="Enter Transfer Value" name="value" required><br>
       <input class="w3-margin w3-button w3-hover-blue" type="Submit" name="submit">
       
       
   </form>
     
</body>
</html>