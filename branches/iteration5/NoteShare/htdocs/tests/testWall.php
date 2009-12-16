#!/usr/bin/php

<?php
	$_SERVER = array( "DOCUMENT_ROOT" => "/var/www/localhost/htdocs/" );

	require_once( $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php' );
	
	//addSessionWallPostDAL(14811933, 14, "This here, it's a wall post 1");
	
	 echo getSessionWallPostsDAL(14);
	 
	 //Looks good

?>
