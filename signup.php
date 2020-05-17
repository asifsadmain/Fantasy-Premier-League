<?php

if(isset($_POST['submit']) && isset($_POST['username']) && isset($_POST['password']) &&  isset($_POST['DoB']) && isset($_POST['country']) && isset($_POST['email']))
{
    echo "YOU HAVE SUCCESSFULLY REGISTERED!!!";
    
    $username = $_POST['username'];
    $pass = $_POST['password'];
    $DoB = $_POST['DoB'];
    $country = $_POST['country'];
    $email = $_POST['email'];
    $password = md5($pass);
    
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    
    $insert = "INSERT INTO USERLIST (USER_PASSWORD, USERNAME,                           COUNTRY,DATE_OF_BIRTH,EMAIL,MONEY,WILD_CARD,CHANGES)
            VALUES ('$password','$username','$country',TIMESTAMP '$DoB','$email',10000,0,0)";
        
    $query = oci_parse($conn, $insert);
    oci_execute($query);
    
    oci_close($conn);
    
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/w3.css">
    <link rel="stylesheet" href="css/snackbar.css">
</head>
<body class="w3-center">
  <div class="w3-card w3-border w3-myfont w3-xlarge w3-margin">
  <div class="w3-container w3-blue">
      <p class="w3-xxlarge">User Informations</p>
  </div>
   
       <form onsubmit="return myFunction();" action="signup.php" method="post" class="w3-container w3-left-align w3-margin">
       
       <label>Username</label>
       <input type="text" class="w3-input w3-margin-bottom" placeholder="Enter Username" name="username" required>
       <label>Password</label>
       <input type="password" class="w3-input w3-margin-bottom" placeholder="Enter Password" name="password" required>
       <label>Date of Birth</label>
       <input type="text" class="w3-input w3-margin-bottom" placeholder="Date of Birth"  name="DoB" value="1996-01-01 00:00:00" required>
       <label>Country</label>
       <input type="text" class="w3-input w3-margin-bottom" placeholder="Enter Your Country Name" name="country" value="Bangladesh" required>
       <label>Email</label>
       <input type="email" class="w3-input w3-margin-bottom" placeholder="Enter Email" name="email" value="a@a.com" required>
       <input type="Submit" class="w3-button w3-round-large w3-black w3-margin" name="submit">
       
       
       </form>
    </div>
    <script type="text/javascript">
  function myFunction() {
    var x = document.getElementById("snackbar")
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 6000);
  }
</script>
</body>
</html>