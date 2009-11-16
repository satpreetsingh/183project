<?php
  /**
   * CoursePageView.php
   * Provides information and functionality of a course session.
   * Right now, partisipants.
  **/

	require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/Session.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/CourseHomePage.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . 'views/View.php';


//--------------------------View Functions--------------------------------//
  /**
   * Generates a text area with the specified name and intial value
   *
   * @version 1.0
   * @param string $name the name of the text area
   * @param string $initalValue the initial value of the text area
   * @return prints a text area on the view
  **/
  function genTextBox( $name, $initialValue )
  {
    echo '<textarea name="' . $name . '" class="sessionBBS fbFont">' .
         $initialValue . '</textarea>';
  }

  /**
   * Displays the posting link for the session bulletin board system
   *
   * @return displays the link for posting
  **/
  function genNewThreadBBS( $sessionId )
  {
    echo '<div style="text-align: right;">' .
         '<a href="/views/NewThread.php?ns_session=' . $sessionId . '" target="iframe_canvas" class="fbFont">' .
         'Create New Thread</a></div>';
  }

  /**
   * Gathers and displays the contents of this session's BBS topics.
   *
   * @return displays table of session's BBS topics
  **/
  function genSessionBBS( $sessionId )
  {
    $bbsTopicsXML = getSessionBBSTopics( $sessionId );
    echo XSLTransform( $bbsTopicsXML, 'CoursePage.xsl');
    echo '<br />';
    genNewThreadBBS( $sessionId );
  }

//----------------------Begin View Code----------------------------------//

  genViewHeader( "Course View Page" );
  genPageHeader( array( "Main Page", "Course View" ),
                 array( "/views/UserHomePage.php", "/views/CoursePage.php?ns_session=" . $_GET['ns_session'] ));

  // Course information
  genHeadingBar( "Course Info" );
	$sessionId = $_GET['ns_session'];
	$metaXML = getSessionMetadata($sessionId);
	echo XSLTransform($metaXML,'CoursePage.xsl');
	echo '</br></br>';

  // Drop course option
	echo "<form action=\"/controllers/DropCourse.php\" method=\"GET\">"
	   . "			<button class=\"drop fbFont\" name=\"ns_session\" value=\"$sessionId\" onclick=\"return confirm(\'Really? Drop the course?\');\">"
		 . "			Drop"
		 . "	</button>"
		 . "</form>";
	echo "</br></br>";
	
	// Session Wall
	genHeadingBar( "Session Shoutouts" );
	$wall = getSessionWall($sessionId);
	echo XSLTransform($wall,'CoursePage.xsl');

  // SessionBBS
  genHeadingBar( "Session Bulletin Board" );
  genSessionBBS( $sessionId );
  echo '<br /><br />';

  // Classmates in the course
	genHeadingBar( "Classmates" );
	$membersXML = getSessionMembers($user_id, $sessionId);
	echo XSLTransform($membersXML,'CoursePage.xsl');

  // Close out page
  genViewFooter();
?>


