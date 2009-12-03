<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Controller.php';
  include_once $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';

  /**
   * Retrieves all of the posts for the specified session
   *
   * @param integer $sessionId identifier for which session's posts to grab
   * @return XML of the posts
  **/
  function getSessionBBSPosts( $parentId, $userId, $facebook )
  {
    $bbsPostsXML = getSessionBBSPostsDAL( $parentId, $facebook );
    $tags = array( 'UserId', 'ParentId' );
    $values = array( $userId, $parentId );
    return insertXMLTags( $tags, $values, $bbsPostsXML, 'SessionBBSThread' );
  }

  /** Post reply**/
  if( isset( $_GET['sessionBBSPost'] ))
  {
    $sessionId = $_GET['ns_session'];
    $parentId = $_GET['parentId'];
    $body = urldecode( $_GET['sessionBBSPost'] );

    $body = preg_replace('/\<\s*(?!\/?\s*(i|b|code|del)).*?\/?\s*\>/','',$body);

    addSessionBBSPostDAL( $user_id, $sessionId, 'null', $body, $parentId );
    $facebook->redirect( 'http://apps.facebook.com/notesharesep/views/SessionBBS.php?ns_session=' . $sessionId . '&parentId=' . $parentId );
  }
  elseif( isset( $_GET['sessionBBSDEL'] ))
  {
    $sessionId = $_GET['ns_session'];
    $postId = $_GET['post_id'];
    $parentId = $_GET['parentId'];

    removeSessionBBSDAL( $postId );
    if( $postId == $parentId )
    {
      $facebook->redirect( 'http://apps.facebook.com/notesharesep/views/CoursePage.php?ns_session=' . $sessionId );
    }
    else
    {
      $facebook->redirect( 'http://apps.facebook.com/notesharesep/views/SessionBBS.php?ns_session=' . $sessionId . '&parentId=' . $parentId );
    }
  }
?>
