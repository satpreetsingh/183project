<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.php';
  include $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';

  /**
   * Retrieves all of the posts for the specified session
   *
   * @param integer $sessionId identifier for which session's posts to grab
   * @return XML of the posts
  **/
  function getSessionBBSPosts( $parentId )
  {
    return getSessionBBSPostsDAL( $parentId );
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
    echo "Session: " . $sessionId . "<br>";
    echo "Parent: " . $parentId . "<br>";
    echo "User: " . $user_id . "<br>";
    echo "Body: " . $body . "<br>";
    addSessionBBSPostDAL( $user_id, $sessionId, 'null', $body, $parentId );
    $facebook->redirect( 'http://apps.facebook.com/notesharesep/views/SessionBBS.php?noteshare_session=' . $sessionId . '&parentId=' . $parentId );
  }
?>
