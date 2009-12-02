<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/Controller.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/model/NoteshareDatabase.php';

  if( isset( $_GET[ 'Create_Group' ] ))
  {
    $header = mysql_real_escape_string( $_GET[ 'header' ] );
    $sessionId = $_GET[ 'ns_session' ];
    $post = mysql_real_escape_string( $_GET[ 'post' ] );
    //addStudyGroupDAL( $user_id, $sessionId, $header, $post, null );
    $facebook->redirect( 'http://apps.facebook.com/notesharesep/views/CoursePage.php?ns_session=' . $sessionId );
  }

?>
