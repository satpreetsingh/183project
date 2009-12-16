<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/Controller.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/model/NoteshareDatabase.php';

  if( isset( $_GET[ 'Create_Group' ] ))
  {
    $header = urldecode(stripslashes($_GET[ 'header' ]));
    $sessionId = $_GET[ 'ns_session' ];
    $post = urldecode(stripslashes($_GET[ 'post' ]));
    
    $header = preg_replace('/\<\s*(?!\/?\s*(i|b|code|del)).*?\/?\s*\>/','',$header);
    $post = preg_replace('/\<\s*(?!\/?\s*(i|b|code|del)).*?\/?\s*\>/','',$post);
    
    if( !createStudyGroupDAL( $sessionId, $header, $post ))
    {
      echo '<script type="text/javascript">';
      echo ' alert( "Group creation failed." );';
      echo '</script>';
    }
    $facebook->redirect( 'http://apps.facebook.com/notesharesep/views/CoursePage.php?ns_session=' . $sessionId );
  }
?>
