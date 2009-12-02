<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/Controller.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/model/NoteshareDatabase.php';

	$session_id = $_GET['ns_session'];

	ob_start();
	removeUserSessionDAL($user_id, $session_id);
	ob_end_clean();

	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

	$facebook->redirect( 'http://apps.facebook.com/notesharesep/views/UserHomePage.php' );
?>
