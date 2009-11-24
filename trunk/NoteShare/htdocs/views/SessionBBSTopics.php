<?php
  /**
   * CoursePageView.php
   * Provides information and functionality of a course session.
   * Right now, partisipants.
  **/

	require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/Session.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/SessionBBSTopics.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . 'views/View.php';


//--------------------------View Functions--------------------------------//
  /**
   * Gathers and displays the contents of this session's BBS topics.
   * @return displays table of session's BBS topics
  **/
  function genSessionBBS( $sessionId, $user_id )
  {
    $bbsTopicsXML = getSessionBBSTopics( $sessionId, $user_id );
    //echo $bbsTopicsXML;
    echo XSLTransform( $bbsTopicsXML, 'SessionBBSTopics.xsl');
  }

//----------------------Begin View Code----------------------------------//

  genViewHeader( "Course View Page" );
  genPageHeader( array( "Main Page", "Course View", "All BBS Topics" ),
                 array( "/views/UserHomePage.php", 
                        "/views/CoursePage.php?ns_session=" . $_GET['ns_session'],
                        "/views/SessionBBSTopics.php?ns_session=" . $_GET['ns_session'] ));

  $sessionId = $_GET['ns_session'];

  // SessionBBS Topics
  genHeadingBar( "Session Bulletin Board", "Create New Thread", "/views/NewThread.php?ns_session=" . $sessionId );
  genSessionBBS( $sessionId, $user_id );
  echo '<br /><br />';

  // Close out page
  genViewFooter();
?>


