<?php

            if(isset($_POST['submit']))
                {
                    $_SESSION['login_user'] = null;
                    $_SESSION['login_password'] = null;
                    session_destroy();
                }
            if(isset($_POST['a_submit']))
            {
                $_SESSION['admin'] = null;
            }
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pro Manager</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/grid.css" type="text/css">
    
</head>
<body>
   <head>
       <div>
           <h1><a href="index.php"><img src="image/logo.PNG" alt="cannot load image"></a></h1>
       </div>  
   </head>
   
   <section> 
       <div class="loginbox">
           <img class="avatar" src="image/avatar.svg">
           <form action="profile2.php" method="post">
               <input class="boxes" type="text" placeholder="Enter Username" name="username" required><br><br>
               <input class="boxes" type="password" placeholder="Enter Password" name="password" required><br><br>
               <input class="boxes" type="Submit" name="submit" value="Log In"><br><br><br>
               <a class="signup" href="signup.php" onclick="signup.php">New here? Sign up for free!!!</a><br><br><br>
               <a class="signup" href="admin_login.php">login as admin</a>
           </form>
           
            
       </div>
   </section>
   
   <footer>
      
      
       
   </footer>
    
</body>
</html>