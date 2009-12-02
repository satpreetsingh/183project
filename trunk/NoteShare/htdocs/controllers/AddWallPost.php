<?php

	require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/Controller.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/model/NoteshareDatabase.php';

	$session_id = $_GET['ns_session'];
	$body = urldecode($_GET['post_body']);
  $group_id = $_GET['nsStudyGroup'];

	$body = preg_replace('/\<\s*(?!\/?\s*(i|b|code|del)).*?\/?\s*\>/','',$body);

    if( isset( $group_id ) && isset( $session_id ))
    {
    	ob_start();
      addStudyGroupWallPostDAL( $user_id, $group_id, $body );
    	ob_end_clean();

    	$facebook->redirect( "http://apps.facebook.com/notesharesep/views/GroupPage.php?ns_session=" . $session_id . "&nsStudyGroup=" . $group_id );
    }
    elseif( isset( $session_id ))
    {
    	ob_start();
	    addSessionWallPostDAL($user_id, $session_id, $body);
    	ob_end_clean();

    	$facebook->redirect( "http://apps.facebook.com/notesharesep/views/CoursePage.php?ns_session=" . $session_id );
    }

   	$facebook->redirect( "http://apps.facebook.com/notesharesep/views/UserHomePage.php" );
?>
