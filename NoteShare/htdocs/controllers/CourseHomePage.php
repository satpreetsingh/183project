<?php

  include $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Controller.php';

  /**
   * Get Response -- Handles get requests made from the Course Home Page.
   *
   * Expected responses:
   *  To remove a session
  **/
  if( isset( $_GET['funct'] ))
  {
    if( $_GET['funct'] == "DELETEBBS" )
    {
      $sessionId = $_GET['ns_session'];
      $parentId = $_GET['parentId'];
      removeSessionBBSDAL( $parentId );

      $facebook->redirect( "http://apps.facebook.com/notesharesep/views/CoursePage.php?ns_session=" . $sessionId );
    }
    elseif( $_GET['funct'] == "DELETENOTE" )
    {
      $sessionId = $_GET['ns_session'];
      $noteId = $_GET['noteId'];
      removeSessionNoteDAL( $noteId );

      $facebook->redirect( "http://apps.facebook.com/notesharesep/views/CoursePage.php?ns_session=" . $sessionId );
    }
  }

  /*
   * Returns the session's descriptive data from the DAL in XML format
   *
   * @version 1.0
   * @return session metadata as XML
   */
  function getSessionMetadata( $sessionid )
  {
    return getSessionMetadataDAL( $sessionid );
  }

  /**
   * Retrieves a list of eight random session members for the indicated session.
   *
   * @version 1.0
   * @param string $userid viewing user's facebook id number
   * @param string $sessionid members are retrieved from this session id number
   * @return session member XML
   */
  function getSessionMembers( $userid, $sessionId, $facebook )
  {
    return getSessionMembersDAL( $sessionId, $userid, $facebook, 8 );
  }

/*
 *
 */
function removeSession($sessionID)
{
//Is this ok?

    return "
	<?xml version=\"1.0\"?>
	<response>You have been removed from the course...</response>
	";
}

  /*
   * Retrieves the five most recent BBS postings for the indicated session.
   *  Inserts the viewing user's id at the top of the BBS topics so that
   *  the XSL can create deletion links if the viewing user created that topic.
   *
   * @version 2.1
   * @param string $sessionId postings are retrieved for this session id number
   * @return session topics in XML form
   */
  function getSessionBBSTopics( $sessionId, $userId )
  {
    // Request the five most recent BBS postings for this session.
    $bbsTopicsXML = getSessionBBSTopicsDAL( $sessionId, 5 );

    // Add the user id into the xml return
    $tags = array( "UserId" );
    $values = array( $userId );
    return insertXMLTags( $tags, $values, $bbsTopicsXML, "SessionBBSTopics" );
  }

  /**
   * Retrieves the five most recent wall posts from the DAL for the indicated
   *  session.
   *
   * @version 2.0
   * @param int $sessionID Noteshare session Id
   * @return XML for as athe five most recent wall posts
  **/
  function getSessionWall( $userId, $sessionID, $facebook )
  {
    $sessionWallXML = getSessionWallPostsDAL( $sessionID, 5, $facebook );

    // Add the user id into the xml return
    $tags = array( "UserId" );
    $values = array( $userId );
    return insertXMLTags( $tags, $values, $sessionWallXML, "sessionWallPosts" );
  }

  /*
   * Retrieves the five most recent note postings for the indicated session
   *
   * @version 3.0
   * @param integer $sessionId notes are retrieved from this session id
   * @return session notes in XML format containing note titles, descriptions,
   *           and active links
   */
  function getSessionNotes( $sessionId, $userId )
  {
    // Request the five most recent note postings for this session.
    $sessionNotesXML = getSessionNoteDAL( $sessionId, 0, 5 );
    $sessionNotesDOM = new DOMDocument('1.0');
    $sessionNotesDOM->loadXML( $sessionNotesXML );
    $sessionNotesList = $sessionNotesDOM->getElementsByTagName( 'getSessionNotes' );
    if( $sessionNotesList->length > 0 )
    {
      $sessionNotes = $sessionNotesList->item(0);
      $sessionUserId = $sessionNotesDOM->createElement('UserId');
      $sessionNotes->insertBefore( $sessionUserId,
        $sessionNotes->firstChild );
      $sessionUserId_text = $sessionNotesDOM->createTextNode( $userId );
      $sessionUserId->appendChild( $sessionUserId_text );
    }

    return $sessionNotesDOM->saveXML();
  }

  function getSessionStudyGroups($user_id, $sessionId)
  {
    $studyGroupXML = getStudyGroupsDAL( $user_id, $sessionId );
    return $studyGroupXML;
  }
?>
