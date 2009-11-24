<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/Session.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/model/NoteshareDatabase.php';

	$session_id = $_GET['ns_session'];
	$body = urldecode($GET['body']);
	
	preg_replace('/<\t*(?!(b)|(i)|(del)|(code))[^>]*>.*?<\/[^>]*?>/','',$body);

	ob_start();
	addSessionWallPostDAL($user_id, $session_id, $body)
	ob_end_clean();

	$facebook->redirect( 'http://apps.facebook.com/notesharesep/views/CoursePage.php' );
?>
