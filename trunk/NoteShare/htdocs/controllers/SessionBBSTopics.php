<?php

  include_once $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Controller.php';

  /*
   * Retrieves the all of the BBS postings for the indicated session.
   *  Inserts the viewing user's id at the top of the BBS topics so that
   *  the XSL can create deletion links if the viewing user created that topic.
   *
   * @version 2.1
   * @param string $sessionId postings are retrieved for this session id number
   * @return session topics in XML form
   */
  function getSessionBBSTopics( $sessionId, $userId )
  {
    $bbsTopicsXML = getSessionBBSTopicsDAL( $sessionId );
    $bbsTopicsDOM = new DOMDocument('1.0');
    $bbsTopicsDOM->loadXML( $bbsTopicsXML );
    $sessionBBSTopicsList = $bbsTopicsDOM->getElementsByTagName( 'SessionBBSTopics' );
    if( $sessionBBSTopicsList->length > 0 )
    {
      $sessionBBSTopics = $sessionBBSTopicsList->item(0);
      $sessionUserId = $bbsTopicsDOM->createElement('UserId');
      $sessionBBSTopics->insertBefore( $sessionUserId,
        $sessionBBSTopics->firstChild );
      $sessionUserId_text = $bbsTopicsDOM->createTextNode( $userId );
      $sessionUserId->appendChild( $sessionUserId_text );
    }
    return $bbsTopicsDOM->saveXML();
  }

  /**
   * Get Response -- Handles get requests made from the Course Home Page.
   *
   * Expected responses:
   *  To remove a session
  **/
  if( isset( $_GET['parentId'] ))
  {
    $sessionId = $_GET['ns_session'];
    $parentId = $_GET['parentId'];
    removeSessionBBSDAL( $parentId );
    
    $facebook->redirect( "http://apps.facebook.com/notesharesep/views/SessionBBSTopics.php?ns_session=" . $sessionId );
  }

?>
