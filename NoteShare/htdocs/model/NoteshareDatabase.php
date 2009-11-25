<?php
/**
 * NoteshareDatabase.php
 *
 * This file contains all of the functions to interact with our own SQL database.
 * The results from these functions are strings of XML data to be interpeted in the view.
 *
 */

/**
 * This function opens the database connection
 * @author(s) Joseph Trapani/Nathan Denklau
 * @version 1.0
 * @return string connection
 */
function openDB ()
{
  // Set the MySQL information.
  $dbhost = 'localhost';
  $dbuser = 'root';
  $dbpass = 'b4n4n4s';
  $dbname = 'NoteShareSEP';

  // Connect to the MySQL service.
  $conn = mysql_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql' . mysql_error());

  // Select the database NoteShareSEP.
  mysql_select_db ($dbname);

  return $conn;
}


/**
 * This function closes the database connection
 * @author(s) Joseph Trapani/Nathan Denklau
 * @version 1.0
 * @param string $result results from SQL querey to release
 * @param string $conn connection to close
 */
function closeDB ($result, $conn)
{
  // Free the result memory.
  if( $result )
  {
    mysql_free_result($result);
  }

  // Close the database connection.
  mysql_close($conn);
}

  // Include Add Course Database Functions <ITERATION 1>
  require_once $_SERVER['DOCUMENT_ROOT'] . '/model/NoteshareDatabase_AddCourse.php';

  // Include BBS Database Functions <ITERATION 2>
  require_once $_SERVER['DOCUMENT_ROOT'] . '/model/NoteshareDatabase_BBS.php';

  // Include Note Database Functions <ITERATION 3>
  require_once $_SERVER['DOCUMENT_ROOT'] . '/model/NoteshareDatabase_Note.php';

  // Include Group Database Function <ITERATION 4>
  require_once $_SERVER['DOCUMENT_ROOT'] . '/model/NoteshareDatabase_Group.php';
?>
