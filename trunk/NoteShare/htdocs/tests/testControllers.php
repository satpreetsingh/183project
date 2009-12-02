<?php
  /**
   * Contains a barage of tests for iterations 1,2,3,4.  This excerisizes the
   *   DAL functions to ensure proper insertion into and removal from the
   *   database.
   *
   * This is a script intended to be run from the command line via 'php'  This
   *   will not currently render properly into a web page.
  **/

//----< Main Tests >----------------------------------------------------------//
  $_SERVER = array( "DOCUMENT_ROOT" => "/var/www/localhost/htdocs/" );
  require_once( $_SERVER['DOCUMENT_ROOT'] . 'controllers/CourseHomePage.php' );

  // Testing Variables
  $_TESTVARS = array( "userId" => 14821122, "sessionId" => 1 );

?>
