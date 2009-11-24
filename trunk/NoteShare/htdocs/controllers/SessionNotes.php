<?php

  include $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.php';

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
    $sessionNotesXML = getSessionNoteDAL( $sessionId, 0, 0 );
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
      $noteId = $_GET['noteId'];
      removeSessionNoteDAL( $noteId );

      $facebook->redirect( "http://apps.facebook.com/notesharesep/views/SessionNotes.php?ns_session=" . $sessionId );
    }
  }
?>
