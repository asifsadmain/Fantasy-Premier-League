<?php
//*****************for login************
session_start();
global $_loggedin;
$_loggedin = false;
$conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
$show = "select max(week_no) from squad";
    
$query = oci_parse($conn, $show);
oci_execute($query);
    
while($row=oci_fetch_row($query))
{
    $current_week = $row[0];
}

if($current_week==null)
{
    $current_week=0;
}

$_SESSION['current_week'] = $current_week;

            if((isset($_POST['submit'])) && ($_POST['username']!=null) && ($_POST['username']!=null))
            {
                
                $username = $_POST['username'];
                $pass = $_POST['password'];
                $password = md5($pass);
    
                $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    
                $statement = "SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'";
        
                $query = oci_parse($conn, $statement);
                oci_execute($query);
                
                $count = 0;
                
                while($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS))
                    $count = $count + 1;
    
                if($count!=0) 
                {
                    $_SESSION['user'] = $username;
                    $_SESSION['password'] = $password;
                    $_loggedin = true;
                }
                else
                {
                    $_SESSION['user'] = null;
                    $_SESSION['password'] = null;
                    ?>
    <p class="alert">invalid username/password,</p>
    <a class="alert" href="index.php"> back to home</a>
    <?php
                }
                echo $_SESSION['login_user'];
    
                oci_close($conn);
            }
$username = $_SESSION['user'];
$password = $_SESSION['password'];
?>

        <?php
//********************adding GK******************
if(isset($_POST['addGK']))
{
    $gk = $_POST['hidden_gk'];
    
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    
    $moneycheck = "SELECT MONEY FROM USERLIST WHERE USERNAME='$username'";
    $query = oci_parse($conn, $moneycheck);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $balance = $row[0];
    }
    
    $transfer = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$gk')";
    $query = oci_parse($conn, $transfer);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $price = $row[0];
    }
    
    $counter = 0;
    
    $checkblank = "SELECT PLAYER_ID FROM SQUAD WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS='GK'";
    $query = oci_parse($conn, $checkblank);
    oci_execute($query);
    
    while($row = oci_fetch_row($query))
          {
           $count = $count + 1;
           $p_id = $row[0];
          }
        
    if($count==0 && $balance>=$price)
    {
        $statement = "INSERT INTO SQUAD (USER_ID, PLAYER_ID, P_POS, WEEK_NO) 
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$gk'), 'GK','$current_week')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        $balance = $balance - $price;
    }
    else if($count==0 && $balance<$price)
    {
        echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
    }
    else
    {
        $statement = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID='$p_id'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        while($row = oci_fetch_row($query))
          {
           $change = $row[0];
          }
        if(($balance+$change)>=$price)
        {
        $statement = "SELECT CHANGES FROM USERLIST WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        while($row = oci_fetch_row($query))
          {
           $cng = $row[0];
          }
        
        if($cng>3)
        {
            echo '<script type="text/javascript">alert("You Have Already Updated 3 Players in This Week!");</script>';
        }
        else
        {
            $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$gk') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'GK' AND WEEK_NO='$current_week'";
            $query = oci_parse($conn, $statement);
            oci_execute($query);
        
            $balance = $balance+$change-$price;
            $cng = $cng+1;
            
            $statement = "UPDATE USERLIST SET CHANGES='$cng' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
            $query = oci_parse($conn, $statement);
            oci_execute($query);
        }
        }
        else
        {
            echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
        }
    }
    $statement = "UPDATE USERLIST SET MONEY='$balance' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
}

//********************adding LB******************
if(isset($_POST['addLB']))
{
    $lb = $_POST['hidden_lb'];
    
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    
    $moneycheck = "SELECT MONEY FROM USERLIST WHERE USERNAME='$username'";
    $query = oci_parse($conn, $moneycheck);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $balance = $row[0];
    }
    
    $transfer = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lb')";
    $query = oci_parse($conn, $transfer);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $price = $row[0];
    }
    
    $counter = 0;
    
    $checkblank = "SELECT PLAYER_ID FROM SQUAD WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS='LB' ";
    $query = oci_parse($conn, $checkblank);
    oci_execute($query);
    
    while($row = oci_fetch_row($query))
          {
           $count = $count + 1;
           $p_id = $row[0];
          }
        
    if($count==0 && $balance>=$price)
    {
        $statement = "INSERT INTO SQUAD (USER_ID, PLAYER_ID, P_POS, WEEK_NO) 
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lb'), 'LB', '$current_week')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        $balance = $balance - $price;
    }
    else if($count==0 && $balance<$price)
    {
        echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
    }
    else
    {
        $statement = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID='$p_id'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        while($row = oci_fetch_row($query))
          {
           $change = $row[0];
          }
        if(($balance+$change)>=$price)
        {
        $statement = "SELECT CHANGES FROM USERLIST WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        while($row = oci_fetch_row($query))
          {
           $cng = $row[0];
          }
        
        if($cng>3)
        {
            echo '<script type="text/javascript">alert("You Have Already Updated 3 Players in This Week!");</script>';
        }
        else{
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lb') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'LB' AND WEEK_NO='$current_week'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
        $cng = $cng+1;
            
            $statement = "UPDATE USERLIST SET CHANGES='$cng' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
            $query = oci_parse($conn, $statement);
            oci_execute($query);
        }
        }
        else
        {
            echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
        }
    }
    $statement = "UPDATE USERLIST SET MONEY='$balance' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
}

//********************adding LCB******************
if(isset($_POST['addLCB']))
{
    $lcb = $_POST['hidden_lcb'];
    
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    
    $moneycheck = "SELECT MONEY FROM USERLIST WHERE USERNAME='$username'";
    $query = oci_parse($conn, $moneycheck);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $balance = $row[0];
    }
    
    $transfer = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lcb')";
    $query = oci_parse($conn, $transfer);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $price = $row[0];
    }
    
    $counter = 0;
    
    $checkblank = "SELECT PLAYER_ID FROM SQUAD WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS='LCB'";
    $query = oci_parse($conn, $checkblank);
    oci_execute($query);
    
    while($row = oci_fetch_row($query))
          {
           $count = $count + 1;
           $p_id = $row[0];
          }
        
    if($count==0 && $balance>=$price)
    {
        $statement = "INSERT INTO SQUAD (USER_ID, PLAYER_ID, P_POS, WEEK_NO) 
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lcb'), 'LCB', '$current_week')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        $balance = $balance - $price;
    }
    else if($count==0 && $balance<$price)
    {
        echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
    }
    else
    {
        $statement = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID='$p_id'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        while($row = oci_fetch_row($query))
          {
           $change = $row[0];
          }
        if(($balance+$change)>=$price)
        {
        $statement = "SELECT CHANGES FROM USERLIST WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        while($row = oci_fetch_row($query))
          {
           $cng = $row[0];
          }
        
        if($cng>3)
        {
            echo '<script type="text/javascript">alert("You Have Already Updated 3 Players in This Week!");</script>';
        }
        else{
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lcb') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'LCB' AND WEEK_NO='$current_week'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
        $cng = $cng+1;
            
            $statement = "UPDATE USERLIST SET CHANGES='$cng' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
            $query = oci_parse($conn, $statement);
            oci_execute($query);
        }
        }
        else
        {
            echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
        }
    }
    $statement = "UPDATE USERLIST SET MONEY='$balance' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
}

//********************adding RCB******************
if(isset($_POST['addRCB']))
{
    $rcb = $_POST['hidden_rcb'];
    
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    
    $moneycheck = "SELECT MONEY FROM USERLIST WHERE USERNAME='$username'";
    $query = oci_parse($conn, $moneycheck);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $balance = $row[0];
    }
    
    $transfer = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rcb')";
    $query = oci_parse($conn, $transfer);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $price = $row[0];
    }
    
    $counter = 0;
    
    $checkblank = "SELECT PLAYER_ID FROM SQUAD WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS='RCB'";
    $query = oci_parse($conn, $checkblank);
    oci_execute($query);
    
    while($row = oci_fetch_row($query))
          {
           $count = $count + 1;
           $p_id = $row[0];
          }
        
    if($count==0 && $balance>=$price)
    {
        $statement = "INSERT INTO SQUAD (USER_ID, PLAYER_ID, P_POS, WEEK_NO) 
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rcb'), 'RCB', '$current_week')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        $balance = $balance - $price;
    }
    else if($count==0 && $balance<$price)
    {
        echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
    }
    else
    {
        $statement = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID='$p_id'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        while($row = oci_fetch_row($query))
          {
           $change = $row[0];
          }
        if(($balance+$change)>=$price)
        {
        $statement = "SELECT CHANGES FROM USERLIST WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        while($row = oci_fetch_row($query))
          {
           $cng = $row[0];
          }
        
        if($cng>3)
        {
            echo '<script type="text/javascript">alert("You Have Already Updated 3 Players in This Week!");</script>';
        }
        else{
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rcb') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'RCB' AND WEEK_NO='$current_week'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
        $cng = $cng+1;
            
            $statement = "UPDATE USERLIST SET CHANGES='$cng' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
            $query = oci_parse($conn, $statement);
            oci_execute($query);
        }
        }
        else
        {
            echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
        }
    }
    $statement = "UPDATE USERLIST SET MONEY='$balance' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
}

//********************adding RB******************
if(isset($_POST['addRB']))
{
    $rb = $_POST['hidden_rb'];
    
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    
    $moneycheck = "SELECT MONEY FROM USERLIST WHERE USERNAME='$username'";
    $query = oci_parse($conn, $moneycheck);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $balance = $row[0];
    }
    
    $transfer = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rb')";
    $query = oci_parse($conn, $transfer);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $price = $row[0];
    }
    
    $counter = 0;
    
    $checkblank = "SELECT PLAYER_ID FROM SQUAD WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS='RB'";
    $query = oci_parse($conn, $checkblank);
    oci_execute($query);
    
    while($row = oci_fetch_row($query))
          {
           $count = $count + 1;
           $p_id = $row[0];
          }
        
    if($count==0 && $balance>=$price)
    {
        $statement = "INSERT INTO SQUAD (USER_ID, PLAYER_ID, P_POS, WEEK_NO) 
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rb'), 'RB', '$current_week')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        $balance = $balance - $price;
    }
    else if($count==0 && $balance<$price)
    {
        echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
    }
    else
    {
        $statement = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID='$p_id'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        while($row = oci_fetch_row($query))
          {
           $change = $row[0];
          }
        if(($balance+$change)>=$price)
        {
        $statement = "SELECT CHANGES FROM USERLIST WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        while($row = oci_fetch_row($query))
          {
           $cng = $row[0];
          }
        
        if($cng>3)
        {
            echo '<script type="text/javascript">alert("You Have Already Updated 3 Players in This Week!");</script>';
        }
        else{
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rb') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'RB' AND WEEK_NO='$current_week'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
        $cng = $cng+1;
            
            $statement = "UPDATE USERLIST SET CHANGES='$cng' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
            $query = oci_parse($conn, $statement);
            oci_execute($query);
        }
        }
        else
        {
            echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
        }
    }
    $statement = "UPDATE USERLIST SET MONEY='$balance' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
}

//********************adding LW******************
if(isset($_POST['addLW']))
{
    $lw = $_POST['hidden_lw'];
    
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    
    $moneycheck = "SELECT MONEY FROM USERLIST WHERE USERNAME='$username'";
    $query = oci_parse($conn, $moneycheck);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $balance = $row[0];
    }
    
    $transfer = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lw')";
    $query = oci_parse($conn, $transfer);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $price = $row[0];
    }
    
    $counter = 0;
    
    $checkblank = "SELECT PLAYER_ID FROM SQUAD WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS='LW'";
    $query = oci_parse($conn, $checkblank);
    oci_execute($query);
    
    while($row = oci_fetch_row($query))
          {
           $count = $count + 1;
           $p_id = $row[0];
          }
        
    if($count==0 && $balance>=$price)
    {
        $statement = "INSERT INTO SQUAD (USER_ID, PLAYER_ID, P_POS, WEEK_NO) 
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lw'), 'LW', '$current_week')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        $balance = $balance - $price;
    }
    else if($count==0 && $balance<$price)
    {
        echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
    }
    else
    {
        $statement = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID='$p_id'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        while($row = oci_fetch_row($query))
          {
           $change = $row[0];
          }
        if(($balance+$change)>=$price)
        {
        $statement = "SELECT CHANGES FROM USERLIST WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        while($row = oci_fetch_row($query))
          {
           $cng = $row[0];
          }
        
        if($cng>3)
        {
            echo '<script type="text/javascript">alert("You Have Already Updated 3 Players in This Week!");</script>';
        }
        else{
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lw') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'LW' AND WEEK_NO='$current_week'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
        $cng = $cng+1;
            
            $statement = "UPDATE USERLIST SET CHANGES='$cng' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
            $query = oci_parse($conn, $statement);
            oci_execute($query);
        }
        }
        else
        {
            echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
        }
    }
    $statement = "UPDATE USERLIST SET MONEY='$balance' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
}

//********************adding LCM******************
if(isset($_POST['addLCM']))
{
    $lcm = $_POST['hidden_lcm'];
    
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    
    $moneycheck = "SELECT MONEY FROM USERLIST WHERE USERNAME='$username'";
    $query = oci_parse($conn, $moneycheck);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $balance = $row[0];
    }
    
    $transfer = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lcm')";
    $query = oci_parse($conn, $transfer);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $price = $row[0];
    }
    
    $counter = 0;
    
    $checkblank = "SELECT PLAYER_ID FROM SQUAD WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS='LCM'";
    $query = oci_parse($conn, $checkblank);
    oci_execute($query);
    
    while($row = oci_fetch_row($query))
          {
           $count = $count + 1;
           $p_id = $row[0];
          }
        
    if($count==0 && $balance>=$price)
    {
        $statement = "INSERT INTO SQUAD (USER_ID, PLAYER_ID, P_POS, WEEK_NO) 
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lcm'), 'LCM', '$current_week')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        $balance = $balance - $price;
    }
    else if($count==0 && $balance<$price)
    {
        echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
    }
    else
    {
        $statement = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID='$p_id'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        while($row = oci_fetch_row($query))
          {
           $change = $row[0];
          }
        if(($balance+$change)>=$price)
        {
        $statement = "SELECT CHANGES FROM USERLIST WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        while($row = oci_fetch_row($query))
          {
           $cng = $row[0];
          }
        
        if($cng>3)
        {
            echo '<script type="text/javascript">alert("You Have Already Updated 3 Players in This Week!");</script>';
        }
        else{
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lcm') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'LCM' AND WEEK_NO='$current_week'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
        $cng = $cng+1;
            
            $statement = "UPDATE USERLIST SET CHANGES='$cng' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
            $query = oci_parse($conn, $statement);
            oci_execute($query);
        }
        }
        else
        {
            echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
        }
    }
    $statement = "UPDATE USERLIST SET MONEY='$balance' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
}

//********************adding RCM******************
if(isset($_POST['addRCM']))
{
    $rcm = $_POST['hidden_rcm'];
    
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    
    $moneycheck = "SELECT MONEY FROM USERLIST WHERE USERNAME='$username'";
    $query = oci_parse($conn, $moneycheck);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $balance = $row[0];
    }
    
    $transfer = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rcm')";
    $query = oci_parse($conn, $transfer);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $price = $row[0];
    }
    
    $counter = 0;
    
    $checkblank = "SELECT PLAYER_ID FROM SQUAD WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS='RCM'";
    $query = oci_parse($conn, $checkblank);
    oci_execute($query);
    
    while($row = oci_fetch_row($query))
          {
           $count = $count + 1;
           $p_id = $row[0];
          }
        
    if($count==0 && $balance>=$price)
    {
        $statement = "INSERT INTO SQUAD (USER_ID, PLAYER_ID, P_POS, WEEK_NO) 
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rcm'), 'RCM', '$current_week')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        $balance = $balance - $price;
    }
    else if($count==0 && $balance<$price)
    {
        echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
    }
    else
    {
        $statement = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID='$p_id'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        while($row = oci_fetch_row($query))
          {
           $change = $row[0];
          }
        if(($balance+$change)>=$price)
        {
        $statement = "SELECT CHANGES FROM USERLIST WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        while($row = oci_fetch_row($query))
          {
           $cng = $row[0];
          }
        
        if($cng>3)
        {
            echo '<script type="text/javascript">alert("You Have Already Updated 3 Players in This Week!");</script>';
        }
        else{
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rcm') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'RCM' AND WEEK_NO='$current_week'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
        $cng = $cng+1;
            
            $statement = "UPDATE USERLIST SET CHANGES='$cng' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
            $query = oci_parse($conn, $statement);
            oci_execute($query);
        }
        }
        else
        {
            echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
        }
    }
    $statement = "UPDATE USERLIST SET MONEY='$balance' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
}
//********************adding RW******************
if(isset($_POST['addRW']))
{
    $rw = $_POST['hidden_rw'];
    
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    
    $moneycheck = "SELECT MONEY FROM USERLIST WHERE USERNAME='$username'";
    $query = oci_parse($conn, $moneycheck);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $balance = $row[0];
    }
    
    $transfer = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rw')";
    $query = oci_parse($conn, $transfer);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $price = $row[0];
    }
    
    $counter = 0;
    
    $checkblank = "SELECT PLAYER_ID FROM SQUAD WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS='RW'";
    $query = oci_parse($conn, $checkblank);
    oci_execute($query);
    
    while($row = oci_fetch_row($query))
          {
           $count = $count + 1;
           $p_id = $row[0];
          }
        
    if($count==0 && $balance>=$price)
    {
        $statement = "INSERT INTO SQUAD (USER_ID, PLAYER_ID, P_POS, WEEK_NO) 
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rw'), 'RW', '$current_week')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        $balance = $balance - $price;
    }
    else if($count==0 && $balance<$price)
    {
        echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
    }
    else
    {
        $statement = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID='$p_id'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        while($row = oci_fetch_row($query))
          {
           $change = $row[0];
          }
        if(($balance+$change)>=$price)
        {
        $statement = "SELECT CHANGES FROM USERLIST WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        while($row = oci_fetch_row($query))
          {
           $cng = $row[0];
          }
        
        if($cng>3)
        {
            echo '<script type="text/javascript">alert("You Have Already Updated 3 Players in This Week!");</script>';
        }
        else{
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rw') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'RW' AND WEEK_NO='$current_week'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
        $cng = $cng+1;
            
            $statement = "UPDATE USERLIST SET CHANGES='$cng' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
            $query = oci_parse($conn, $statement);
            oci_execute($query);
        }
        }
        else
        {
            echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
        }
    }
    $statement = "UPDATE USERLIST SET MONEY='$balance' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
}

//********************adding LS******************
if(isset($_POST['addLS']))
{
    $ls = $_POST['hidden_ls'];
    
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    
    $moneycheck = "SELECT MONEY FROM USERLIST WHERE USERNAME='$username'";
    $query = oci_parse($conn, $moneycheck);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $balance = $row[0];
    }
    
    $transfer = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$ls')";
    $query = oci_parse($conn, $transfer);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $price = $row[0];
    }
    
    $counter = 0;
    
    $checkblank = "SELECT PLAYER_ID FROM SQUAD WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS='LS'";
    $query = oci_parse($conn, $checkblank);
    oci_execute($query);
    
    while($row = oci_fetch_row($query))
          {
           $count = $count + 1;
           $p_id = $row[0];
          }
        
    if($count==0 && $balance>=$price)
    {
        $statement = "INSERT INTO SQUAD (USER_ID, PLAYER_ID, P_POS, WEEK_NO) 
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$ls'), 'LS', '$current_week')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        $balance = $balance - $price;
    }
    else if($count==0 && $balance<$price)
    {
        echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
    }
    else
    {
        $statement = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID='$p_id'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        while($row = oci_fetch_row($query))
          {
           $change = $row[0];
          }
        if(($balance+$change)>=$price)
        {
        $statement = "SELECT CHANGES FROM USERLIST WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        while($row = oci_fetch_row($query))
          {
           $cng = $row[0];
          }
        
        if($cng>3)
        {
            echo '<script type="text/javascript">alert("You Have Already Updated 3 Players in This Week!");</script>';
        }
        else{
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$ls') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'LS' AND WEEK_NO='$current_week'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
        $cng = $cng+1;
            
            $statement = "UPDATE USERLIST SET CHANGES='$cng' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
            $query = oci_parse($conn, $statement);
            oci_execute($query);
        }
        }
        else
        {
            echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
        }
    }
    $statement = "UPDATE USERLIST SET MONEY='$balance' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
}

//********************adding RS******************
if(isset($_POST['addRS']))
{
    $rs = $_POST['hidden_rs'];
    
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    
    $moneycheck = "SELECT MONEY FROM USERLIST WHERE USERNAME='$username'";
    $query = oci_parse($conn, $moneycheck);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $balance = $row[0];
    }
    
    $transfer = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rs')";
    $query = oci_parse($conn, $transfer);
    oci_execute($query);
    
    while (($row = oci_fetch_row($query)))
    {
        $price = $row[0];
    }
    
    $counter = 0;
    
    $checkblank = "SELECT PLAYER_ID FROM SQUAD WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS='RS'";
    $query = oci_parse($conn, $checkblank);
    oci_execute($query);
    
    while($row = oci_fetch_row($query))
          {
           $count = $count + 1;
           $p_id = $row[0];
          }
        
    if($count==0 && $balance>=$price)
    {
        $statement = "INSERT INTO SQUAD (USER_ID, PLAYER_ID, P_POS, WEEK_NO) 
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rs'), 'RS', '$current_week')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        $balance = $balance - $price;
    }
    else if($count==0 && $balance<$price)
    {
        echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
    }
    else
    {
        $statement = "SELECT TRANSFER_VALUE FROM PLAYERS WHERE PLAYER_ID='$p_id'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        while($row = oci_fetch_row($query))
          {
           $change = $row[0];
          }
        if(($balance+$change)>=$price)
        {
        $statement = "SELECT CHANGES FROM USERLIST WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        while($row = oci_fetch_row($query))
          {
           $cng = $row[0];
          }
        
        if($cng>3)
        {
            echo '<script type="text/javascript">alert("You Have Already Updated 3 Players in This Week!");</script>';
        }
        else{
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rs') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'RS' AND WEEK_NO='$current_week'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
        $cng = $cng+1;
            
            $statement = "UPDATE USERLIST SET CHANGES='$cng' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
            $query = oci_parse($conn, $statement);
            oci_execute($query);
        }
        }
        else
        {
            echo '<script type="text/javascript">alert("u do not have enough balance to buy this player!");</script>';
        }
    }
    $statement = "UPDATE USERLIST SET MONEY='$balance' WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password')";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
}
?>


            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <title>Profile</title>
                <link rel="stylesheet" href="css/w3.css" type="text/css">
                <script>
                    function addGK() {
                        <?php  $_SESSION['players']=1 ?>
                    }

                </script>
            </head>

            <body>

                <head>
                    <div class="w3-bar w3-black">
                        <?php
           if((isset($_SESSION['user'])) && (isset($_SESSION['password']))){
               ?>
                            <!--<h1><a href="index.php"><img src="image/logo.PNG" alt="cannot load image"></a></h1> -->
                            <img src="image/logo.PNG" class="w3-bar-item">
                            <br><br><br><br>
                            <a href="profile.php" class="w3-bar-item w3-button w3-grey w3-xlarge">Profile</a>
                            <a href="s_l_g.php" class="w3-bar-item w3-button w3-xlarge">League Table</a>
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
                            <?php
                    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');

                    $moneycheck = "SELECT MONEY FROM USERLIST WHERE USERNAME='$username'";
                    $query = oci_parse($conn, $moneycheck);
                    oci_execute($query);
    
                    while (($row = oci_fetch_row($query)))
                    {
                        $balance = $row[0];
                    }
               } 
           if((isset($_SESSION['user'])) && (isset($_SESSION['password']))){
               ?>
                                <form action="index.php" method="post">
                                    <input class="w3-bar-item w3-right w3-button" type="submit" name="logout" value="Logout">
                                </form>
                    </div>
                </head>
                <br>
                <div class="w3-row">
                    <div class="w3-container w3-col s2 w3-blue w3-center w3-blue" style="height:1500px">
                       <p class=" w3-center w3-xlarge w3-hover-grey w3-border">Gameweek:<?php echo $current_week ?></p>
                        <p class="w3-center w3-xxlarge">FIXTURES</p>
                        <?php


               $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');

               $show = "SELECT HOME_TEAM_NAME, AWAY_TEAM_NAME FROM FIXTURE WHERE WEEK_NO='$current_week'";
    
               $query = oci_parse($conn, $show);
               oci_execute($query);

               while (($row = oci_fetch_row($query))) {
            ?>
                            <p class=" w3-center w3-hover-grey w3-border">
                                <?php echo $row[0] ?> VS.
                                <?php echo $row[1] ?>
                            </p>
                            <?php } 
            oci_close($conn);    
        ?>
                    </div>
                    <div class="w3-container w3-col s2 w3-blue w3-center" style="height:1500px">
                        <p class=" w3-myfont w3-xlarge">SELECT PLAYERS</p><br>
                        <a href="select_gk.php" class="w3-button w3-light-blue w3-block">Add/Replace GK</a><br>
                        <a href="select_lb.php" class="w3-button w3-light-blue w3-block">Add/Replace LB</a><br>
                        <a href="select_lcb.php" class="w3-button w3-light-blue w3-block">Add/Replace LCB</a><br>
                        <a href="select_rcb.php" class="w3-button w3-light-blue w3-block">Add/Replace RCB</a><br>
                        <a href="select_rb.php" class="w3-button w3-light-blue w3-block">Add/Replace RB</a><br>
                        <a href="select_lw.php" class="w3-button w3-light-blue w3-block">Add/Replace LW</a><br>
                        <a href="select_lcm.php" class="w3-button w3-light-blue w3-block">Add/Replace LCM</a><br>
                        <a href="select_rcm.php" class="w3-button w3-light-blue w3-block">Add/Replace RCM</a><br>
                        <a href="select_rw.php" class="w3-button w3-light-blue w3-block">Add/Replace RW</a><br>
                        <a href="select_ls.php" class="w3-button w3-light-blue w3-block">Add/Replace LS</a><br>
                        <a href="select_rs.php" class="w3-button w3-light-blue w3-block">Add/Replace RS</a><br>
                    </div>
                    <div class="w3-container w3-col s6 w3-dark-grey w3-center" style="height:1500px">
                        <p class=" w3-myfont w3-xlarge">MY SQUAD (4-4-2)</p><br> <br>

                        <div class=" w3-center w3-container w3-margin-top w3-row">
                            <div class="w3-card-4 w3-col s3 w3-container">

                            </div>

                            <div class="w3-card-4 w3-col s3 w3-container">
                                <?php
                                    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
                                    $moneycheck = "select player_name, club, rating from players where player_id=(select player_id from squad where user_id=(select user_id from userlist where username='$username' and user_password='$password') and week_no='$current_week' and p_pos ='LS')";
                                    $query = oci_parse($conn, $moneycheck);
                                    oci_execute($query);
                                    $playername = null;
                                    $club = null;
                                    $rating = null;
    
                                    while (($row = oci_fetch_row($query)))
                                    {
                                        $playername = $row[0];
                                        $club = $row[1];
                                        $rating = $row[2];
                                    }
                                ?>
                                    <img class="w3-image" src="image/midfielder.jpg" alt="">
                                    <div class="w3-container w3-center">
                                        <p>
                                            <?php echo $playername; ?>
                                        </p>
                                        <p>Club:
                                            <?php echo $club; ?>
                                        </p>
                                        <p>Rating:
                                            <?php echo $rating; ?>
                                        </p>
                                    </div>
                            </div>
                            <div class="w3-card-4 w3-col s3 w3-container">
                                <?php
                                    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
                                    $moneycheck = "select player_name, club, rating from players where player_id=(select player_id from squad where user_id=(select user_id from userlist where username='$username' and user_password='$password') and week_no='$current_week' and p_pos ='RS')";
                                    $query = oci_parse($conn, $moneycheck);
                                    oci_execute($query);
                                    $playername = null;
                                    $club = null;
                                    $rating = null;
    
                                    while (($row = oci_fetch_row($query)))
                                    {
                                        $playername = $row[0];
                                        $club = $row[1];
                                        $rating = $row[2];
                                    }
                                ?>
                                    <img class="w3-image" src="image/midfielder.jpg" alt="">
                                    <div class="w3-container w3-center">
                                        <p>
                                            <?php echo $playername; ?>
                                        </p>
                                        <p>Club:
                                            <?php echo $club; ?>
                                        </p>
                                        <p>Rating:
                                            <?php echo $rating; ?>
                                        </p>
                                    </div>
                            </div>
                        </div>
                        <br> <br>
                        <div class="w3-container w3-margin-top w3-row">
                            <div class="w3-card-4 w3-col s3 w3-container">
                                <?php
                                    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
                                    $moneycheck = "select player_name, club, rating from players where player_id=(select player_id from squad where user_id=(select user_id from userlist where username='$username' and user_password='$password') and week_no='$current_week' and p_pos ='LW')";
                                    $query = oci_parse($conn, $moneycheck);
                                    oci_execute($query);
                                    $playername = null;
                                    $club = null;
                                    $rating = null;
    
                                    while (($row = oci_fetch_row($query)))
                                    {
                                        $playername = $row[0];
                                        $club = $row[1];
                                        $rating = $row[2];
                                    }
                                ?>
                                    <img class="w3-image" src="image/midfielder.jpg" alt="">
                                    <div class="w3-container w3-center">
                                        <p>
                                            <?php echo $playername; ?>
                                        </p>
                                        <p>Club:
                                            <?php echo $club; ?>
                                        </p>
                                        <p>Rating:
                                            <?php echo $rating; ?>
                                        </p>
                                    </div>
                            </div>
                            <div class="w3-card-4 w3-col s3 w3-container">
                                <?php
                                    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
                                    $moneycheck = "select player_name, club, rating from players where player_id=(select player_id from squad where user_id=(select user_id from userlist where username='$username' and user_password='$password') and week_no='$current_week' and p_pos ='LCM')";
                                    $query = oci_parse($conn, $moneycheck);
                                    oci_execute($query);
                                    $playername = null;
                                    $club = null;
                                    $rating = null;
    
                                    while (($row = oci_fetch_row($query)))
                                    {
                                        $playername = $row[0];
                                        $club = $row[1];
                                        $rating = $row[2];
                                    }
                                ?>
                                    <img class="w3-image" src="image/midfielder.jpg" alt="Norway">
                                    <div class="w3-container w3-center">
                                        <p>
                                            <?php echo $playername; ?>
                                        </p>
                                        <p>Club:
                                            <?php echo $club; ?>
                                        </p>
                                        <p>Rating:
                                            <?php echo $rating; ?>
                                        </p>
                                    </div>
                            </div>
                            <div class="w3-card-4 w3-col s3 w3-container">
                                <?php
                                    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
                                    $moneycheck = "select player_name, club, rating from players where player_id=(select player_id from squad where user_id=(select user_id from userlist where username='$username' and user_password='$password') and week_no='$current_week' and p_pos ='RCM')";
                                    $query = oci_parse($conn, $moneycheck);
                                    oci_execute($query);
                                    $playername = null;
                                    $club = null;
                                    $rating = null;
    
                                    while (($row = oci_fetch_row($query)))
                                    {
                                        $playername = $row[0];
                                        $club = $row[1];
                                        $rating = $row[2];
                                    }
                                ?>
                                    <img class="w3-image" src="image/midfielder.jpg" alt="">
                                    <div class="w3-container w3-center">
                                        <p>
                                            <?php echo $playername; ?>
                                        </p>
                                        <p>Club:
                                            <?php echo $club; ?>
                                        </p>
                                        <p>Rating:
                                            <?php echo $rating; ?>
                                        </p>
                                    </div>
                            </div>
                            <div class="w3-card-4 w3-col s3 w3-container">
                                <?php
                                    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
                                    $moneycheck = "select player_name, club, rating from players where player_id=(select player_id from squad where user_id=(select user_id from userlist where username='$username' and user_password='$password') and week_no='$current_week' and p_pos ='RW')";
                                    $query = oci_parse($conn, $moneycheck);
                                    oci_execute($query);
                                    $playername = null;
                                    $club = null;
                                    $rating = null;
    
                                    while (($row = oci_fetch_row($query)))
                                    {
                                        $playername = $row[0];
                                        $club = $row[1];
                                        $rating = $row[2];
                                    }
                                ?>
                                    <img class="w3-image" src="image/midfielder.jpg" alt="">
                                    <div class="w3-container w3-center">
                                        <p>
                                            <?php echo $playername; ?>
                                        </p>
                                        <p>Club:
                                            <?php echo $club; ?>
                                        </p>
                                        <p>Rating:
                                            <?php echo $rating; ?>
                                        </p>
                                    </div>
                            </div>
                        </div>

                        <br>
                        <br>

                        <div class="w3-container w3-margin-top w3-row">
                            <div class="w3-card-4 w3-col s3 w3-container">
                                <?php
                                    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
                                    $moneycheck = "select player_name, club, rating from players where player_id=(select player_id from squad where user_id=(select user_id from userlist where username='$username' and user_password='$password') and week_no='$current_week' and p_pos ='LB')";
                                    $query = oci_parse($conn, $moneycheck);
                                    oci_execute($query);
                                    $playername = null;
                                    $club = null;
                                    $rating = null;
    
                                    while (($row = oci_fetch_row($query)))
                                    {
                                        $playername = $row[0];
                                        $club = $row[1];
                                        $rating = $row[2];
                                    }
                                ?>
                                    <img class="w3-image" src="image/midfielder.jpg" alt="">
                                    <div class="w3-container w3-center">
                                        <p>
                                            <?php echo $playername; ?>
                                        </p>
                                        <p>Club:
                                            <?php echo $club; ?>
                                        </p>
                                        <p>Rating:
                                            <?php echo $rating; ?>
                                        </p>
                                    </div>
                            </div>
                            <div class="w3-card-4 w3-col s3 w3-container">
                                <?php
                                    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
                                    $moneycheck = "select player_name, club, rating from players where player_id=(select player_id from squad where user_id=(select user_id from userlist where username='$username' and user_password='$password') and week_no='$current_week' and p_pos ='LCB')";
                                    $query = oci_parse($conn, $moneycheck);
                                    oci_execute($query);
                                    $playername = null;
                                    $club = null;
                                    $rating = null;
    
                                    while (($row = oci_fetch_row($query)))
                                    {
                                        $playername = $row[0];
                                        $club = $row[1];
                                        $rating = $row[2];
                                    }
                                ?>
                                    <img class="w3-image" src="image/midfielder.jpg" alt="">
                                    <div class="w3-container w3-center">
                                        <p>
                                            <?php echo $playername; ?>
                                        </p>
                                        <p>Club:
                                            <?php echo $club; ?>
                                        </p>
                                        <p>Rating:
                                            <?php echo $rating; ?>
                                        </p>
                                    </div>
                            </div>
                            <div class="w3-card-4 w3-col s3 w3-container">
                                <?php
                                    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
                                    $moneycheck = "select player_name, club, rating from players where player_id=(select player_id from squad where user_id=(select user_id from userlist where username='$username' and user_password='$password') and week_no='$current_week' and p_pos ='RCB')";
                                    $query = oci_parse($conn, $moneycheck);
                                    oci_execute($query);
                                    $playername = null;
                                    $club = null;
                                    $rating = null;
    
                                    while (($row = oci_fetch_row($query)))
                                    {
                                        $playername = $row[0];
                                        $club = $row[1];
                                        $rating = $row[2];
                                    }
                                ?>
                                    <img class="w3-image" src="image/midfielder.jpg" alt="">
                                    <div class="w3-container w3-center">
                                        <p>
                                            <?php echo $playername; ?>
                                        </p>
                                        <p>Club:
                                            <?php echo $club; ?>
                                        </p>
                                        <p>Rating:
                                            <?php echo $rating; ?>
                                        </p>
                                    </div>
                            </div>
                            <div class="w3-card-4 w3-col s3 w3-container">
                                <?php
                                    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
                                    $moneycheck = "select player_name, club, rating from players where player_id=(select player_id from squad where user_id=(select user_id from userlist where username='$username' and user_password='$password') and week_no='$current_week' and p_pos ='RB')";
                                    $query = oci_parse($conn, $moneycheck);
                                    oci_execute($query);
                                    $playername = null;
                                    $club = null;
                                    $rating = null;
    
                                    while (($row = oci_fetch_row($query)))
                                    {
                                        $playername = $row[0];
                                        $club = $row[1];
                                        $rating = $row[2];
                                    }
                                ?>
                                    <img class="w3-image" src="image/midfielder.jpg" alt="Norway">
                                    <div class="w3-container w3-center">
                                        <p>
                                            <?php echo $playername; ?>
                                        </p>
                                        <p>Club:
                                            <?php echo $club; ?>
                                        </p>
                                        <p>Rating:
                                            <?php echo $rating; ?>
                                        </p>
                                    </div>
                            </div>
                        </div>

                        <br>
                        <br>
                        <div class=" w3-center w3-container w3-margin-top w3-row">
                            <div class="w3-card-4 w3-col s4 w3-margin-left w3-container">

                            </div>

                            <div class="w3-card-4 w3-col s3 w3-container">
                                <?php
                                    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
                                    $moneycheck = "select player_name, club, rating from players where player_id=(select player_id from squad where user_id=(select user_id from userlist where username='$username' and user_password='$password') and week_no='$current_week' and p_pos ='GK')";
                                    $query = oci_parse($conn, $moneycheck);
                                    oci_execute($query);
                                    $playername = null;
                                    $club = null;
                                    $rating = null;
    
                                    while (($row = oci_fetch_row($query)))
                                    {
                                        $playername = $row[0];
                                        $club = $row[1];
                                        $rating = $row[2];
                                    }
                                ?>
                                    
                                    <img class="w3-image" src="image/goalkeeper.jpg" alt="Norway">
                                    <div class="w3-container w3-center">
                                        <p>
                                            <?php echo $playername; ?>
                                        </p>
                                        <p>Club:
                                            <?php echo $club; ?>
                                        </p>
                                        <p>Rating:
                                            <?php echo $rating; ?>
                                        </p>
                                    </div>
                            </div>
                        </div>




                    </div>
                    <div class="w3-container w3-col s2 w3-blue w3-center" style="height:1500px">
                        <img src="image/avatar.svg">
                        <p class="w3-myfont w3-large">Username:
                            <?php echo "$username"; ?>
                        </p>
                        <p class="w3-myfont w3-large">Balance:
                            <?php echo "$balance"; ?>
                        </p>
                        <?php
                            $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
                            $show = "SELECT COUNTRY, WILD_CARD FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'";
    
                            $query = oci_parse($conn, $show);
                            oci_execute($query);
                            while($row=oci_fetch_row($query))
                            {
                                $country = $row[0];
                                $wildcard = $row[1];
                            }
                            $wk = $current_week-1;
                            $show = "SELECT WEEKLY_RANKING FROM GAMEWEEK_HISTORY WHERE WEEK_NO='$wk' AND USER_ID=(select user_id from userlist where username='$username' and user_password='$password')";
    
                            $query = oci_parse($conn, $show);
                            oci_execute($query);
                            $w_rank = null;
                            $g_rank = null;
                            while($row=oci_fetch_row($query))
                            {
                                $w_rank = $row[0];
                            }

                            $show = "SELECT RANKING FROM SEASON_STATS WHERE USER_ID=(select user_id from userlist where username='$username' and user_password='$password')";
    
                            $query = oci_parse($conn, $show);
                            oci_execute($query);
                            while($row=oci_fetch_row($query))
                            {
                                $g_rank = $row[0];
                            }
                        ?>
                            <p class="w3-myfont w3-large">Country:
                                <?php echo "$country"; ?>
                            </p>
                            <p class="w3-myfont w3-large">Wildcard Used:
                                <?php echo "$wildcard"; ?>
                            </p>
                            <p class="w3-myfont w3-large">Weekly Rank:
                                <?php echo "$w_rank"; ?>
                            </p>
                            <p class="w3-myfont w3-large">Global Rank:
                                <?php echo "$g_rank"; ?>
                            </p><br><br><br><br>
                            <a class="w3-myfont w3-large" href="wildcard.php">Use Wildcard</a><br>
                            <a class="w3-myfont w3-large" href="lastweek_squad.php">LastWeek Squad</a>
                    </div>
                </div>
                <!--<section><div class="ground">
                            <img id="fbground" class="fbground" name="fbground" src="image/football%20ground.jpg" alt="cannot load image">
                            <a href="select_gk.php"><img id="gk" name="gk" class="gk"  src="image/lightblue.gif" alt="cannot load image" onclick="show_players.php">
           </a>
                            <a href="select_lb.php"><img id="lb" name="lb" class="lb"  src="image/lightblue.gif" alt="cannot load image"></a>
                            <a href="select_lcb.php"><img id="lcb" name="lcb" class="lcb"  src="image/lightblue.gif" alt="cannot load image"></a>
                            <a href="select_rcb.php"><img id="rcb" name="rcb" class="rcb"  src="image/lightblue.gif" alt="cannot load image"></a>
                            <a href="select_rb.php"><img id="rb" name="rb" class="rb"  src="image/lightblue.gif" alt="cannot load image"></a>
                            <a href="select_lw.php"><img id="lw" name="lw" class="lw"  src="image/lightblue.gif" alt="cannot load image"></a>
                            <a href="select_lcm.php"><img id="lcm" name="lcm" class="lcm"  src="image/lightblue.gif" alt="cannot load image"></a>
                            <a href="select_rcm.php"><img id="rcm" name="rcm" class="rcm"  src="image/lightblue.gif" alt="cannot load image"></a>
                            <a href="select_rw.php"><img id="rw" name="rw" class="rw"  src="image/lightblue.gif" alt="cannot load image"></a>
                            <a href="select_ls.php"><img id="ls" name="ls" class="ls"  src="image/lightblue.gif" alt="cannot load image"></a>
                            <a href="select_rs.php"><img id="rs" name="rs" class="rs"  src="image/lightblue.gif" alt="cannot load image"></a>
                            <?php


$conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');

$show = "SELECT PL.PLAYER_NAME, SQ.P_POS FROM PLAYERS PL JOIN SQUAD SQ ON(PL.PLAYER_ID=SQ.PLAYER_ID) WHERE SQ.USER_ID = (SELECT USER_ID FROM USERLIST WHERE USERNAME='$username') AND WEEK_NO='$current_week'";
    
$query = oci_parse($conn, $show);
oci_execute($query);

?>
                                <table class="userteam" border=1>


                                    <tr>
                                        <td>
                                            <p class="data">PLAYER NAME</p>
                                        </td>
                                        <td>
                                            <p class="data">POSITION</p>
                                        </td>
                                    </tr>
                                    <?php
while (($row = oci_fetch_row($query))) {
    ?>
                                        <tr>
                                            <td>
                                                <p class="data">
                                                    <?php echo $row[0]; ?>
                                                </p>
                                            </td>
                                            <td>
                                                <p class="data">
                                                    <?php echo $row[1]; ?>
                                                </p>
                                            </td>
                                        </tr>
                                        <?php } ?>

                                </table>

                                <?php
oci_free_statement($query);
oci_close($conn);
               

?>
                        </div>
                        <?php } 
            
       ?>
                </section>

            </body>

            </html>-->
