<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '../php/facebook.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.Controller.php';
	
	include $_SERVER['DOCUMENT_ROOT'] . 'controllers/DropCourse.Controller.php';
	
	$session_id = $_POST['session'];
	
	ob_start();
	removeUserSession($user_id, $session_id);
	ob_end_clean();
	
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	
	$extra = $_GET['from'];
	
	header("Location:http://$host$uri/$extra");
	exit();
?>
