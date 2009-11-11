<?php

  // Set the MySQL information.
  $dbhost = 'localhost';
  $dbuser = 'root';
  $dbpass = 'b4n4n4s';
  $dbname = 'NoteShareSEP';


  // Connect to the MySQL service.
  $conn = mysql_connect ($dbhost, $dbuser, $dbpass) or 
                    die ('Error connecting to mysql' . mysql_error());
  
  // Select the database NoteShareSEP.
  mysql_select_db ($dbname);

?>