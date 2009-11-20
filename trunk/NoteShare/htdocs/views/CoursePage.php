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
   * Inserts the user id of the viewer into the xml response
  **/
  function insertUserId( $bbsPosts, $user_id )
  {
    $temp = explode( "\n", $bbsPosts );
    $response = $temp[0] . $temp[1] . $temp[2] . '<userId>' . $user_id . "</userId>\n";
    for( $i = 3; $i < count( $temp ); $i++ )
    {
      $response = $response . $temp[$i];
    }

    return $response;
  }

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
         '<a href="http://apps.facebook.com/notesharesep/views/NewThread.php?ns_session=' . $sessionId . '" target="_top" class="fbFont">' .
         'Create New Thread</a></div>';
  }

  /**
   * Displays the posting link for the session note system
   *
   * @return displays the link for posting
  **/
  function genNewThreadNotes( $sessionId )
  {
echo "test";
  }

  /**
   * Gathers and displays the contents of this session's BBS topics.
   *
   * @return displays table of session's BBS topics
  **/
  function genSessionBBS( $sessionId, $user_id )
  {
    $bbsTopicsXML = getSessionBBSTopics( $sessionId );
    $bbsTopicsXML = formatXMLString( $bbsTopicsXML );
    $bbsTopicsXML = insertUserId( $bbsTopicsXML, $user_id );
    echo XSLTransform( $bbsTopicsXML, 'CoursePage.xsl');
    echo '<br />';
    genNewThreadBBS( $sessionId );
  }

  /**
   * Gathers and displays the contents of this session's notes.
   *
   * @return displays table of session's notes
  **/
  function genSessionNotes( $user_id, $sessionId )
  {
    $notesXML = getSessionNotes( $sessionId );
    $notesXML = formatXMLString( $notes );
    $notesXML = insertUserId( $notes, $user_id );
    echo XSLTransform( $notesXML, 'CoursePage.xsl' );
    echo '<br />';
    genNewThreadNotes( $sessionID );

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

  // Uploaded Notes?
  genHeadingBar( "Course Notes", "Add New Note Set", "/views/SessionNotes.php?ns_session=" . $sessionId );
  genSessionNotes( $user_id, $sessionId );
  echo '<br /><br />';

  // SessionBBS
  genHeadingBar( "Session Bulletin Board" );
  genSessionBBS( $sessionId, $user_id );
  echo '<br /><br />';

  // Classmates in the course
	genHeadingBar( "Classmates" );
	$membersXML = getSessionMembers($user_id, $sessionId);
	echo XSLTransform($membersXML,'CoursePage.xsl');

  // Close out page
  genViewFooter();
?>


