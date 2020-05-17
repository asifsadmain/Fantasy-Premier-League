<?php
    
    $conn = oci_connect('PRO_MANAGER', 'bangladesh', 'localhost/ORCL');
    $insert = "INSERT INTO USERLIST (USER_PASSWORD, LEAGUE_NAME, USERNAME,                           USER_TEAM,COUNTRY,AGE,EMAIL,MONEY,WILD_CARD)
            VALUES ('$password','$league','$username','$team','$country','$age','$email',100000,0)";

    while (($row = oci_fetch_row($query)))
        
    //$query = oci_parse($conn, $insert);
    //oci_execute($query);
    
    oci_close($conn);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
  
  <form action="login.php">
      
      Username: <input type="text" placeholder="Enter Username" name="username" required>
        Password: <input type="password" placeholder="Enter Password" name="password" required>
        <input type="submit" name="submit">
      
      
  </form>
   
   
    
</body>
</html>