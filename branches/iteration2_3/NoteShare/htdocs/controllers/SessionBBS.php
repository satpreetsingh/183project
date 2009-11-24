<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.php';
  include $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';

  /**
   * Retrieves all of the posts for the specified session
   *
   * @param integer $sessionId identifier for which session's posts to grab
   * @return XML of the posts
  **/
  function getSessionBBSPosts( $parentId, $userId )
  {
    $bbsPostsXML = getSessionBBSPostsDAL( $parentId );
    $bbsPostsDOM = new DOMDocument('1.0');
    $bbsPostsDOM->loadXML( $bbsPostsXML );
    $bbsPostsList = $bbsPostsDOM->getElementsByTagName( 'SessionBBSThread' );
    if( $bbsPostsList->length > 0 )
    {
      $bbsPosts = $bbsPostsList->item(0);
      $sessionUserId = $bbsPostsDOM->createElement('UserId');
      $bbsPosts->insertBefore( $sessionUserId, $bbsPosts->firstChild );
      $sessionUserId_text = $bbsPostsDOM->createTextNode( $userId );
      $sessionUserId->appendChild( $sessionUserId_text );

      $sessionParentId = $bbsPostsDOM->createElement('ParentId');
      $bbsPosts->insertBefore( $sessionParentId, $sessionUserId );
      $sessionParentId_text = $bbsPostsDOM->createTextNode( $parentId );
      $sessionParentId->appendChild( $sessionParentId_text );

    }
    return $bbsPostsDOM->saveXML();
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
  if( isset( $_GET['sessionBBSPost'] ))
  {
    $sessionId = $_GET['ns_session'];
    $parentId = $_GET['parentId'];
    $body = $_GET['sessionBBSPost'];
    addSessionBBSPostDAL( $user_id, $sessionId, 'null', $body, $parentId );
    $facebook->redirect( 'http://apps.facebook.com/notesharesep/views/SessionBBS.php?ns_session=' . $sessionId . '&parentId=' . $parentId );
  }
  elseif( isset( $_GET['sessionBBSDEL'] ))
  {
    $sessionId = $_GET['ns_session'];
    $postId = $_GET['post_id'];
    $parentId = $_GET['parentId'];

    removeSessionBBSDAL( $postId );
    if( $postId = $parentId )
    {
      $facebook->redirect( 'http://apps.facebook.com/notesharesep/views/CoursePage.php?ns_session=' . $sessionId );
    }
    else
    {
      $facebook->redirect( 'http://apps.facebook.com/notesharesep/views/SessionBBS.php?ns_session=' . $sessionId . '&parentId=' . $parentId );
    }
  }
?>
