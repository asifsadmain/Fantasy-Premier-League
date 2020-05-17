<?php
session_start();
if(isset($_POST['lg_submit']))
{
    $league = $_POST['league'];
    $username = $_SESSION['user'];
    
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    $insert = "INSERT INTO LEAGUE (LEAGUE_NAME)
            VALUES ('$league')";
        
    $query = oci_parse($conn, $insert);
    oci_execute($query);
    
    $insert = "INSERT INTO USERLEAGUES (LEAGUE_ID, USER_ID)
            VALUES ((SELECT LEAGUE_ID FROM LEAGUE WHERE LEAGUE_NAME = '$league'), (SELECT USER_ID FROM USERLIST WHERE USERNAME='$username'))";
        
    $query = oci_parse($conn, $insert);
    oci_execute($query);
    
    oci_close($conn);
}

if(isset($_POST['join']))
{
    $league =  $_POST['hidden_name'];
    $username = $_SESSION['user'];
    
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    
    $insert = "SELECT LEAGUE_ID, USER_ID FROM USERLEAGUES WHERE LEAGUE_ID=(SELECT LEAGUE_ID FROM LEAGUE WHERE LEAGUE_NAME='$league') AND USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username')";
        
    $query = oci_parse($conn, $insert);
    oci_execute($query);
    
    $counter = 0;
    
    while($row = oci_fetch_row($query))
    {
        $counter = $counter+1;
    }
    
    if($counter==0)
    {
        $insert = "INSERT INTO USERLEAGUES (LEAGUE_ID, USER_ID)
            VALUES ((SELECT LEAGUE_ID FROM LEAGUE WHERE LEAGUE_NAME='$league'), (SELECT USER_ID FROM USERLIST WHERE USERNAME='$username'))";
        $query = oci_parse($conn, $insert);
        oci_execute($query);
        echo '<script type="text/javascript">alert("u have succesfully registered!");</script>';
    }
    else
    {
        echo '<script type="text/javascript">alert("u are already in this league!");</script>';
    }
    
    oci_close($conn);
    
}

?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Leagues</title>
        <link rel="stylesheet" href="css/w3.css" type="text/css">
    </head>

    <body>
       
       <head>
        <div class="w3-bar w3-black">
        
                <!--<h1><a href="index.php"><img src="image/logo.PNG" alt="cannot load image"></a></h1> -->
                <img src="image/logo.PNG" class="w3-bar-item">
                <br><br><br><br>
                <a href="profile2.php" class="w3-bar-item w3-button w3-xlarge">Profile</a>
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
                <a href="leagues.php" class="w3-bar-item w3-button w3-grey w3-xlarge">Leagues</a>
                    <form action="index.php" method="post">
                        <input class="w3-bar-item w3-right w3-button" type="submit" name="logout" value="Logout">
                    </form>
        </div>
    </head>
        <?php

$conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
$show = "SELECT DISTINCT LEAGUE_NAME FROM LEAGUE";
    
$query = oci_parse($conn, $show);
oci_execute($query);
?>
            <div class="w3-row">
                <div class="w3-container w3-col s6 w3-grey" style="height:800px">

                    <table border='1' class="w3-center">

                        <tr>
                            <td class="w3-myfont w3-xxlarge">LEAGUE NAME</td>
                        </tr>
                        <?php
while (($row = oci_fetch_row($query))) {
    ?>
                            <form action="leagues.php" method="post">
                                <tr>
                                    <td class="w3-myfont w3-xlarge">
                                        <?php echo $row[0]; ?>
                                    </td>
                                    <td>
                                        <input type="hidden" name="hidden_name" value="<?php echo $row[0] ?>">
                                        <input type="submit" name="join" value="join">
                                    </td>
                                </tr>
                            </form>
                            <?php } ?>

                    </table>
                    <a href="create_league.php">Create New League</a>
                </div>
                <div class="w3-container w3-col s6 w3-blue w3-grey" style="height:800px">
                    <p class="w3-myfont w3-xxlarge">MY LEAGUES</p>
                    <?php
                        $username = $_SESSION['user'];
                        $show = "SELECT LG.LEAGUE_NAME, LG.LEAGUE_ID FROM LEAGUE LG JOIN USERLEAGUES ULG  ON(LG.LEAGUE_ID=ULG.LEAGUE_ID) WHERE USER_ID=(SELECT USER_ID FROM USERLIST WHERE USERNAME='$username')";
                        $query = oci_parse($conn, $show);
                        oci_execute($query);
                    
                        while($row=oci_fetch_row($query))
                        {
                            ?><form action="league_stats.php" method="post">
                                
                                <li class="w3-myfont w3-xlarge"><?php echo $row[0]; ?>
                                <input type="number" name="week" placeholder="week" min=1 max=38 required>
                                <input type="hidden" name="hidden_league" value="<?php echo $row[1] ?>">
                                <input type="submit" name="stats" value="See Stats">
                    </li>
                                
                            </form>
        
                        <?php } 
                    ?>
                </div>
            </div>

            <?php    
oci_free_statement($query);
oci_close($conn);

?>
            

    </body>

    </html>
