<?php
  /**
   * CoursePageView.php
   * Provides information and functionality of a course group.
   * Right now, partisipants.
  **/

	require_once $_SERVER['DOCUMENT_ROOT'] . 'views/View.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/GroupBBSTopics.php';


//--------------------------View Functions--------------------------------//
  /**
   * Gathers and displays the contents of this group's BBS topics.
   * @return displays table of group's BBS topics
  **/
  function genGroupBBS( $sessionId, $groupId, $user_id )
  {
    $bbsTopicsXML = getStudyGroupBBSTopics( $sessionId, $groupId, $user_id );
    echo $bbsTopicsXML;
    echo XSLTransform( $bbsTopicsXML, 'GroupBBSTopics.xsl');
  }

//----------------------Begin View Code----------------------------------//

  genViewHeader( "Course View Page" );
  genPageHeader( array( "Main Page", "Course View", "Group View", "All BBS Topics" ),
                 array( "/views/UserHomePage.php",
                        "/views/CoursePage.php?ns_session=" . $_GET['ns_session'],
                        "/views/GroupPage.php?ns_session=" . $_GET['ns_session'] . "&nsStudyGroup=" . $_GET['nsStudyGroup'],
                        "/views/GroupBBSTopics.php?ns_session=" . $_GET['ns_session'] . "&nsStudyGroup=" . $_GET['nsStudyGroup'] ));

  $sessionId = $_GET['ns_session'];
  $groupId = $_GET['nsStudyGroup'];

  // GroupBBS Topics
  genHeadingBar( "Group Bulletin Board", "Create New Thread", "/views/NewThread_Group.php?ns_session=" . $sessionId . "&nsStudyGroup=" . $_GET['nsStudyGroup']);
  genGroupBBS( $sessionId, $groupId, $user_id );
  echo '<br /><br />';

  // Close out page
  genViewFooter();
?>


