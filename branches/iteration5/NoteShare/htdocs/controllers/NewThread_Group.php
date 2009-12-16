<?php
  /*
   * File:        /controllers/NewThread_Group.php
   * Description: Fields posts for creating new bbs threads for either a course
   *                or for a study group.  If invalid parameters are passed for
   *                the course id (ns_session) or
   *                the study group (nsStudyGroup), the controller attempts to 
   *                return to the user to whichever of the following pages is
   *                possible (in order): the study group, the course page, or
   *                the user homepage
   */
  require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/Controller.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/model/NoteshareDatabase.php';

  if( isset( $_GET[ 'Post_Thread' ] ))
  {
    // Field Study Group Posts
    if( isset( $_GET[ 'nsStudyGroup' ] ))
    {
      $header = $_GET[ 'header' ];
      $sessionId = $_GET[ 'ns_session' ];
      $groupId = $_GET[ 'nsStudyGroup' ];
      $post = $_GET[ 'post' ];
      addStudyGroupBBSPostDAL( $user_id, $groupId, $header, $post, null );
      $facebook->redirect( 'http://apps.facebook.com/notesharesep/views/GroupPage.php?ns_session=' . $sessionId . '&nsStudyGroup=' . $groupId );
    }
    // Bad parameters, redirect to user homepage
    else
    {
      $facebook->redirect( 'http://apps.facebook.com/notesharesep/views/UserHomePage.php' );
    }
  }
?>
