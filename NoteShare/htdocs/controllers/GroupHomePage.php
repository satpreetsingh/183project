<?php

  include $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Controller.php';

  /**
   * Returns the group's descriptive data from the DAL in XML format
   *
   * @version 1.0
   * @return group metadata as XML
  **/
  function getGroupMetadata( $groupid )
  {
	  return getStudyGroupMetadataDAL( $groupid );
  }
  
  function getGroupWall( $groupID, $facebook )
  {
	  return getStudyGroupWallPostsDAL( $groupID, $facebook );

  /*	
  return '' .
  '<?xml version="1.0" encoding="UTF-8"?>
   <groupWallPosts>
     <post time="1234567890"
           user="66000948">
		   This is one cool group!
     </post>
     <post time="1234567890"
		user="66000948">
		Is anyone here...?
	 </post>
     <post time="1234567890"
           User="66000948">
		   Hello...
     </post>
   </groupWallPosts>';
  */ 
  }


  /*
   * Retrieves the five most recent note postings for the indicated session
   *
   * @version 4.0
   * @param integer $groupId notes are retrieved from this group id
   * @return session notes in XML format containing note titles, descriptions,
   *           and active links
   */
  function getGroupNotes( $userId, $groupId )
  {
    // Request the five most recent note postings for this session.
    $groupNotesXML = getStudyGroupNoteDAL( $groupId, 0, 5 );
    $groupNotesDOM = new DOMDocument('1.0');
    $groupNotesDOM->loadXML( $groupNotesXML );
    $groupNotesList = $groupNotesDOM->getElementsByTagName( 'getGroupNotes' );
    if( $groupNotesList->length > 0 )
    {
      $groupNotes = $groupNotesList->item(0);
      $groupUserId = $groupNotesDOM->createElement('UserId');
      $groupNotes->insertBefore( $groupUserId,
      $groupNotes->firstChild );
      $groupUserId_text = $groupNotesDOM->createTextNode( $userId );
      $groupUserId->appendChild( $groupUserId_text );
    }

    return $groupNotesDOM->saveXML();
  }


  /*
   * Retrieves the five most recent BBS postings for the indicated group.
   *  Inserts the viewing user's id at the top of the BBS topics so that
   *  the XSL can create deletion links if the viewing user created that topic.
   *
   * @version 4.0
   * @param string $groupId postings are retrieved for this group id number
   * @return group topics in XML form
   */
  function getGroupBBSTopics( $sessionId, $groupId, $userId )
  {
    // Request the five most recent BBS postings for this session.
    $bbsTopicsXML = getStudyGroupBBSTopicsDAL( $groupId, 5 );
    $tags = array( "UserId", "SessionId" );
    $values = array( $userId, $sessionId );
    return insertXMLTags( $tags, $values, $bbsTopicsXML, 'StudyGroupBBSTopics' );
  }

  /*
 * Retrieves a list of eight random session members for the indicated session.
 *
 * @version 1.0
 * @param string $userid viewing user's facebook id number
 * @param string $sessionid members are retrieved from this session id number
 * @return session member XML
 */
  function getGroupMembers( $userid, $groupId, $facebook )
  {
	  return getStudyGroupMembersDAL( $groupId, $userid, $facebook, 8 );
  /*
	  return "<?xml version=\"1.0\"?>
	    <groupUserList>
		    <groupUserItem friend=\"False\">66000948</groupUserItem>
		    <groupUserItem friend=\"True\">14821122</groupUserItem>
		    <groupUserItem friend=\"True\">100000182749925</groupUserItem>	
		    <groupUserItem friend=\"True\">14811933</groupUserItem>	
		    <groupUserItem friend=\"True\">850985471</groupUserItem>	
	    </groupUserList>";
  */
  }


  /**
   * Get Response -- Handles get requests made from the Group Home Page
   *
   * Expected responses:
   *  To remove a session
  **/
  if( isset( $_GET['funct'] ))
  {
    if( $_GET['funct'] == "DELETEBBS" )
    {
      $sessionId = $_GET['ns_session'];
      $groupId = $_GET['nsStudyGroup'];
      $parentId = $_GET['parentId'];
      removeStudyGroupBBSDAL( $parentId );

      $facebook->redirect( "http://apps.facebook.com/notesharesep/views/GroupPage.php?ns_session=" . $sessionId . "&nsStudyGroup=" . $groupId );
    }
    elseif( $_GET['funct'] == "DELETENOTE" )
    {
      $sessionId = $_GET['ns_session'];
      $groupId = $_GET['nsStudyGroup'];
      $noteId = $_GET['noteId'];
      removeStudyGroupNoteDAL( $noteId );

      $facebook->redirect( "http://apps.facebook.com/notesharesep/views/GroupPage.php?ns_session=" . $sessionId . "&nsStudyGroup=" . $groupId );
    }
  }
  
  function leaveGroup($groupID)
  {
	//Is this ok?
    return "
	<?xml version=\"1.0\"?>	    
	<response>You have been removed from the course...</response>
	";
  }

?>
