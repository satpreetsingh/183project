<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/Session.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/model/NoteshareDatabase.php';

  if( isset( $_GET[ 'header' ] ))
  {
    $header = $_GET[ 'header' ];
    $sessionId = $_GET[ 'ns_session' ];
    $post = $_GET[ 'post' ];
    addSessionBBSPostDAL( $user_id, $sessionId, $header, $post, null );
    $facebook->redirect( 'http://apps.facebook.com/notesharesep/views/CoursePage.php?ns_session=' . $sessionId );
  }
?>
