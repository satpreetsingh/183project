<?php

  include_once $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Controller.php';

  /*
   * Retrieves the all of the BBS postings for the indicated group.
   *  Inserts the viewing user's id at the top of the BBS topics so that
   *  the XSL can create deletion links if the viewing user created that topic.
   *
   * @version 2.1
   * @param string $groupId postings are retrieved for this group id number
   * @return group topics in XML form
   */
  function getStudyGroupBBSTopics( $sessionId, $groupId, $userId )
  {
    $bbsTopicsXML = getStudyGroupBBSTopicsDAL( $groupId );
    $tags = array( "UserId", "SessionId" );
    $values = array( $userId, $sessionId );
    return insertXMLTags( $tags, $values, $bbsTopicsXML, 'StudyGroupBBSTopics' );
  }

  /**
   * Get Response -- Handles get requests made from the Course Home Page.
   *
   * Expected responses:
   *  To remove a group
  **/
  if( isset( $_GET['parentId'] ))
  {
    $groupId = $_GET['ns_group'];
    $parentId = $_GET['parentId'];
    removeGroupBBSDAL( $parentId );
    
    $facebook->redirect( "http://apps.facebook.com/notesharesep/views/GroupBBSTopics.php?ns_group=" . $groupId );
  }

?>
