<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Controller.php';
  include_once $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';

  /**
   * Retrieves all of the posts for the specified session
   *
   * @param integer $sessionId identifier for which session's posts to grab
   * @return XML of the posts
  **/
  function getStudyGroupBBSPosts( $sessionId, $parentId, $userId, $facebook )
  {
    $bbsPostsXML = getStudyGroupBBSPostsDAL( $parentId, $facebook );
    $tags = array( 'UserId', 'ParentId', 'SessionId' );
    $values = array( $userId, $parentId, $sessionId );
    return insertXMLTags( $tags, $values, $bbsPostsXML, 'StudyGroupBBSThread' );
  }

  function getThreadWall( $threadID )
  {
    return '' .
    '<?xml version="1.0" encoding="UTF-8"?>
    <ThreadWall Topic="Tests Coming up...">
     <Post Header="Example Header"
           Date="11-09-09 12:00:01"
           User="14821122">
		   Test next week everyone!
     </Post>
		 <Comment Header="Example Header"
			   Date="11-09-09 12:00:02"
			   User="14821222">
			   Just 7 days!
		 </Comment>		
     <Post Header="Example Header2"
           Date="11-09-09 12:00:05"
           User="14821122">
		   Welcome to the new semester!
     </Post>
   </ThreadWall>';
  }

  /** Post reply**/
  if( isset( $_GET['groupBBSPost'] ))
  {
    // Gather variables
    $sessionId = $_GET['ns_session'];
    $groupId = $_GET['nsStudyGroup'];
    $parentId = $_GET['parentId'];
    $body = urldecode( $_GET['groupBBSPost'] );

    // Prune Body for HTML tags
    $body = preg_replace('/\<\s*(?!\/?\s*(i|b|code|del)).*?\/?\s*\>/','',$body);

    // Post Contents and redirect
    addStudyGroupBBSPostDAL( $user_id, $groupId, 'null', $body, $parentId );
    $facebook->redirect( 'http://apps.facebook.com/notesharesep/views/GroupBBS.php?ns_session=' . $sessionId . '&parentId=' . $parentId . '&nsStudyGroup=' . $groupId );
  }
  elseif( isset( $_GET['groupBBSDEL'] ))
  {
    $sessionId = $_GET['ns_session'];
    $groupId = $_GET['nsStudyGroup'];
    $postId = $_GET['post_id'];
    $parentId = $_GET['parentId'];

    removeStudyGroupBBSDAL( $postId );
    if( $postId == $parentId )
    {
      $facebook->redirect( 'http://apps.facebook.com/notesharesep/views/GroupPage.php?ns_session=' . $sessionId . '&nsStudyGroup=' . $groupId );
    }
    else
    {
      $facebook->redirect( 'http://apps.facebook.com/notesharesep/views/GroupBBS.php?ns_session=' . $sessionId . '&parentId=' . $parentId . '&nsStudyGroup=' . $groupId );
    }
  }
?>
