<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="css/w3.css">
</head>

<body>
    <?php

$conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
$show = "SELECT PLAYER_NAME,AGE,CLUB,POS,RATING,TRANSFER_VALUE,INJURY FROM PLAYERS";
    
$query = oci_parse($conn, $show);
oci_execute($query);


//while(($row = oci_fetch_array($query,OCI_BOTH))!=false){
  //  echo $row['USERNAME'] . "<br>";
    
//
    ?>
    <table class="w3-table-all w3-container">  
        <tr class='w3-blue'><td>PLAYER NAME</td><td>AGE</td><td>CLUB</td><td>POSITION</td><td>RATING</td><td>TRANSFER VALUE</td><td>INJURY</td></tr>
		<?php
		while (($row = oci_fetch_row($query))) { ?>
            <form action="weekly_update.php" method="post">
			<tr class='w3-hover-blue'>
			<td><?php echo $row[0] ?></td>
			<td><?php echo $row[1] ?></td>
			<td><?php echo $row[2] ?></td>
			<td><?php echo $row[3] ?></td>
			<td><?php echo $row[4] ?></td>
            <td><?php echo $row[5] ?></td>
            <td><?php echo $row[6] ?></td>
            <td><input type="hidden" name="playername" value="<?php echo $row[0]; ?>">
                <input type="submit" name="update" value="update performance">
            </td>
                </tr>
            </form>
            <?php
		} ?>
    </table>
            
		<?php
		oci_free_statement($query);
		oci_close($conn);
    
    
?>
</body>

</html>
