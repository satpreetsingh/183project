<?php


    // Free the result memory.
    mysql_free_result($result);

    // Close the database connection.
    mysql_close($GLOBALS['conn']);


?>