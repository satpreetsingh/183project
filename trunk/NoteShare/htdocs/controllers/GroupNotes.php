<?php

  include_once $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Controller.php';

  /*
   * Retrieves the five most recent note postings for the indicated grou
   *
   * @version 3.0
   * @param integer $groupId notes are retrieved from this group id
   * @return group notes in XML format containing note titles, descriptions,
   *           and active links
   */
  function getStudyGroupNotes( $groupId, $userId )
  {
    // Request the five most recent note postings for this group.
    $groupNotesXML = getStudyGroupNoteDAL( $groupId, 0, 0 );
    $tags = array( 'UserId' );
    $values = array( $userId );
    return insertXMLTags( $tags, $values, $groupNotesXML, 'getStudyGroupNotes' );
  }


  /**
   * Get Response -- Handles get requests made from the Course Home Page.
   *
   * Expected responses:
   *  To remove a session
  **/
  if( isset( $_GET['funct'] ))
  {
    if( $_GET['funct'] == "DELETENOTE" )
    {
      $sessionId = $_GET['ns_session'];
      $groupId = $_GET['nsStudyGroup'];
      $noteId = $_GET['noteId'];
      removeSessionNoteDAL( $noteId );

      $facebook->redirect( "http://apps.facebook.com/notesharesep/views/GroupNotes.php?ns_session=" . $sessionId . "&nsStudyGroup=" . $groupId );
    }
  }
?>
