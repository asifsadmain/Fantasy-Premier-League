<?php
    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');
    
    $show = "BEGIN SQUAD_COPY2; END;";
    $query = oci_parse($conn, $show);
    oci_execute($query);

    $show = "BEGIN UPDATE_RANK; END;";
    $query = oci_parse($conn, $show);
    oci_execute($query);

    $show = "BEGIN UPDATE_OVERALL_RANK; END;";
    $query = oci_parse($conn, $show);
    oci_execute($query);
    
    $show = "UPDATE USERLIST SET CHANGES=0 WHERE USER_ID IS NOT NULL";
    $query = oci_parse($conn, $show);
    oci_execute($query);
    
    /*$show = "INSERT INTO squad (user_id, player_id, p_pos, week_no)
            SELECT user_id, player_id,p_pos, max(week_no)+1
            FROM squad
            group by user_id,player_id,p_pos";
    $query = oci_parse($conn, $show);
    oci_execute($query);*/

?>
<h1>You Have Successfully Started New Week</h1>