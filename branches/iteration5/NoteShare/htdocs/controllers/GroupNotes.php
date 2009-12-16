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
  function getStudyGroupNotes( $groupId, $userId, $sessionId )
  {
    // Request the five most recent note postings for this group.
    $groupNotesXML = getStudyGroupNoteDAL( $groupId, 0, 0 );
    $tags = array( 'UserId', 'SessionId', 'StudyGroupId' );
    $values = array( $userId, $sessionId, $groupId );
    return insertXMLTags( $tags, $values, $groupNotesXML );
  }


  /**
   * Get Response -- Handles get requests made from the Course Home Page.
   *
   * Expected responses:
   *  To remove a note post from a study group
  **/
  if( isset( $_GET['funct'] ))
  {
    if( $_GET['funct'] == "DELETENOTE" )
    {
      $sessionId = $_GET['ns_session'];
      $groupId = $_GET['nsStudyGroup'];
      $noteId = $_GET['noteId'];
      removeStudyGroupNoteDAL ( $noteId );

      $facebook->redirect( "http://apps.facebook.com/notesharesep/views/GroupNotes.php?ns_session=" . $sessionId . "&nsStudyGroup=" . $groupId );
    }
  }
?>
