<?php

    $conn = oci_connect('DBPROJECT', 'dhaka', 'localhost/ORCL');

    $show = "DELETE FROM FIXTURE WHERE MATCH_ID=87";

    //$show = "UPDATE USERLIST SET WILD_CARD=0 WHERE USER_ID IS NOT NULL";
    $query = oci_parse($conn, $show);
    oci_execute($query);

    /*$show = "BEGIN SQUAD_COPY2; END;";
    $query = oci_parse($conn, $show);
    oci_execute($query);
    $show = "BEGIN SQUAD_COPY2; END;";
    
    $show = "SELECT SYSTIMESTAMP FROM DUAL";
    $query = oci_parse($conn, $show);
    oci_execute($query);
    
    while($row=oci_fetch_row($query))
    {
        $systime = $row[0];
    }

    $show = "SELECT MATCH_TIME FROM FIXTURE WHERE HOME_TEAM_NAME='g'";
    $query = oci_parse($conn, $show);
    oci_execute($query);

    while($row=oci_fetch_row($query))
    {
        $matchtime = $row[0];
    }

    if($matchtime>$systime)
        echo "permitted";
    else 
        echo "not permitted";*/
?>