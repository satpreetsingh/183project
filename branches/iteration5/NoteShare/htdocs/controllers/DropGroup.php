<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/Controller.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/model/NoteshareDatabase.php';

	$session_id = $_GET['ns_session'];
  $group_id = $_GET['nsStudyGroup'];

  if( $group_id != NULL && $session_id != NULL )
	{
    ob_start();
	  removeUserStudyGroupDAL($user_id, $group_id);
	  ob_end_clean();
  }
	$facebook->redirect( 'http://apps.facebook.com/notesharesep/views/CoursePage.php?ns_session=' . $session_id );
?>
