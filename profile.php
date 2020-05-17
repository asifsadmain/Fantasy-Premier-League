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
                $password = $_POST['password'];
    
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
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$gk') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'GK' AND WEEK_NO='$current_week'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
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
    
    $checkblank = "SELECT PLAYER_ID FROM SQUAD WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS='LB'";
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
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lb'), 'LB', 0)";
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
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lb') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'LB'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
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
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lcb'), 'LCB', 0)";
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
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lcb') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'LCB'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
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
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rcb'), 'RCB', 0)";
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
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rcb') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'RCB'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
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
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rb'), 'RB', 0)";
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
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rb') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'RB'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
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
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lw'), 'LW', 0)";
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
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lw') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'LW'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
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
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lcm'), 'LCM', 0)";
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
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$lcm') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'LCM'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
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
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rcm'), 'RCM', 0)";
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
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rcm') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'RCM'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
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
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rw'), 'RW', 0)";
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
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rw') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'RW'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
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
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$ls'), 'LS', 0)";
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
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$ls') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'LS'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
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
                      VALUES ((SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password'), (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rs'), 'RS', 0)";
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
        $statement = "UPDATE SQUAD SET PLAYER_ID=(SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME = '$rs') WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND P_POS = 'RS'";
        $query = oci_parse($conn, $statement);
        oci_execute($query);
        
        $balance = $balance+$change-$price;
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
                <link rel="stylesheet" href="css/p_style.css" type="text/css">
                <script>
                    function addGK() {
                        <?php  $_SESSION['players']=1 ?>
                    }
                </script>
            </head>

            <body>

                <head>
                    <div>
                        <?php
           if((isset($_SESSION['user'])) && (isset($_SESSION['password']))){
               ?>
                            <h1><a href="index.php"><img src="image/logo.PNG" alt="cannot load image"></a></h1>
                            <nav>
                                <ul class="menu">
                                    <li class="current"><a href="profile.php">Profile</a></li>
                                    <li><a href="#">League Table</a></li>
                                    <li><a href="#">Stats</a></li>
                                    <li><a href="#">Rules</a></li>
                                    <li><a href="leagues.php">Leagues</a></li>
                <?php
                    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');

                    $moneycheck = "SELECT MONEY FROM USERLIST WHERE USERNAME='$username'";
                    $query = oci_parse($conn, $moneycheck);
                    oci_execute($query);
    
                    while (($row = oci_fetch_row($query)))
                    {
                        $balance = $row[0];
                    }
               ?>
                                    <li><p class="alert">balance:<?php echo "$balance"; ?></p></li>
                                </ul>
                            </nav>
                            <?php } ?>
                    </div>
                </head>
                <section>
                    <?php
           if((isset($_SESSION['user'])) && (isset($_SESSION['password']))){
               ?>
                        <form action="index.php" method="post">
                            <input class="logout" type="submit" name="logout" value="Logout">
                        </form>
                        <div class="ground">
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

$show = "SELECT PL.PLAYER_NAME, SQ.P_POS FROM PLAYERS PL JOIN SQUAD SQ ON(PL.PLAYER_ID=SQ.PLAYER_ID) WHERE SQ.USER_ID = (SELECT USER_ID FROM USERLIST WHERE USERNAME='$username' AND USER_PASSWORD='$password') AND WEEK_NO=1";
    
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

            </html>