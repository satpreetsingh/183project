<?php
// Open the database connection 
$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql' . mysql_error());
mysql_select_db($dbname);

?> 