<!DOCTYPE html>
<html>

<head>
    <title>player_info</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/w3.css">
    <link rel="stylesheet" href="css/snackbar.css">
</head>

<body class="w3-center">


<?php
    
    if(isset($_POST['submit']))
    {
      $Name_p = $_POST['playername'];
      $season = $_POST['Season'];
      $Game_week = $_POST['Game_week'];
      $Goals = $_POST['Goals'];
      $Assists = $_POST['Assists'];
      $Penalty_saved = $_POST['Penalty_saved'];
      $Penalty_missed = $_POST['Penalty_missed'];
      $Red_card = $_POST['Red_card'];
      $Yellow_card = $_POST['Yellow_card'];
      $Clean_sheet=$_POST['Clean_sheet'];
      $Own_goal=$_POST['Own_goal'];
      $Tackles=$_POST['Tackles'];
      $Mom = $_POST['Mom'];
        
      $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
        
      $statement = "INSERT INTO WEEKLY_PERFORMANCE (SEASON,WEEK_NO,PLAYER_ID,GOALS,ASSISTS,PENALTY_SAVED,PENALTY_MISSED,OWN_GOAL,CLEAN_SHEET,RED_CARD,
      YELLOW_CARD,TACKLES,MOM) 
                      VALUES ('$season', '$Game_week', (SELECT PLAYER_ID FROM PLAYERS WHERE PLAYER_NAME='$Name_p'), '$Goals', '$Assists', '$Penalty_saved', '$Penalty_missed', '$Own_goal',  '$Clean_sheet', '$Red_card', '$Yellow_card', '$Tackles', '$Mom')";
        
        $query = oci_parse($conn, $statement);
    oci_execute($query);  
    }

?>

<div class=" w3-margin w3-myfont w3-display-container">
     <a class="w3-display-topright" href="admin_login.php">Back to Admin Login</a>
 </div>
<div class="w3-card w3-border w3-myfont w3-xlarge w3-margin">
  <div class="w3-container w3-blue">
      <p class="w3-xxlarge">Player Informations</p>
  </div>
  <form onsubmit="return myFunction();" action="weekly_update.php" method="post" class="w3-container w3-left-align w3-margin">
    <label>Playername</label>
    <input type="text" class="w3-input w3-margin-bottom" placeholder="Playername" name="playername" value="<?php echo $_POST['playername'] ?>" required>

    <label>Season</label>
    <input type="text" class="w3-input w3-margin-bottom" placeholder="Season year" name="Season" value="2017-2018" required>

    <label>Game Week No.</label>
    <input type="number" class="w3-input w3-margin-bottom" min="0" max="38" placeholder="Game Week no." name="Game_week" value=1 required>
      
    <label>Goals</label>
    <input type="number" class="w3-input w3-margin-bottom"  min="0" max="10" placeholder="Goals Scored" name="Goals" value=0 required>

    <label>Assists</label>
    <input type="number" class="w3-input w3-margin-bottom" min="0" max="10" placeholder="Goal Assists" name="Assists" value=0 required>

    <label>Penalty Saved</label>
    <input type="number" class="w3-input w3-margin-bottom" min="0" max="10" placeholder="Penalty Saved" name="Penalty_saved" value=0 required>

    <label>Penalty missed</label>
    <input type="number" class="w3-input w3-margin-bottom" min="0" max="10" placeholder="Penalty missed" name="Penalty_missed" value=0 required>
    
    <label>Red card</label>
    <input type="number" class="w3-input w3-margin-bottom" min="0" max="1" placeholder="Red Card" name="Red_card" value=0 required>

    <label>Yellow card</label>
    <input type="number" class="w3-input w3-margin-bottom" min="0" max="1" placeholder="Yellow card" name="Yellow_card" value=0 required>

    <label>Clean Sheet</label>
    <input type="number" class="w3-input w3-margin-bottom" min="0" max="1" placeholder="Clean Sheet" name="Clean_sheet" value=0 required>

    <label>Own Goal</label>
    <input type="number" class="w3-input w3-margin-bottom" min="0" max="10" placeholder="Own Goal" name="Own_goal" value=0 required>

    <label>Tackles</label>
    <input type="number" class="w3-input w3-margin-bottom" min="0" max="10" placeholder="Tackles" name="Tackles" value=0 required>

    <label>Man Of the Match</label>
    <input type="number" class="w3-input w3-margin-bottom"  min="0" max="1" placeholder="Man of the Match" name="Mom" value=0 required>
       
    <input class="w3-button w3-round-large w3-black w3-margin" type="Submit" name="submit" >
    <div id="snackbar">Entry Successful</div>
           
   </form>
</div>




<script type="text/javascript">
  function myFunction() {
    var x = document.getElementById("snackbar")
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 100000);
  }
</script>
   
     
</body>
</html>