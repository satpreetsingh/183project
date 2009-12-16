<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Controller.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';

  // Gather possible variables
	$session_id = $_GET['ns_session'];
  $group_id = $_GET['nsStudyGroup'];
  $action = $_GET['enroll'];

  if( $session_id != NULL && $group_id != NULL && $user_id != NULL )
  {
	  ob_start();
      if( $action == 'DROP' )
      {
	      removeUserStudyGroupDAL($user_id, $group_id);
//        echo "DROPPED!\n";
      }
      else if( $action == 'ADD' )
      {
        addStudyGroupUserGroupDAL( $user_id, $session_id, $group_id );
//        echo "ADDED!\n";
      }
    ob_end_clean();
  }

	$facebook->redirect( 'http://apps.facebook.com/notesharesep/views/CoursePage.php?ns_session=' . $session_id );
?>
