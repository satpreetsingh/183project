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
   * Gathers and displays the contents of this session's BBS topics.
   *  USE DOM to insert USER_ID
   * @return displays table of session's BBS topics
  **/
  function genSessionBBS( $sessionId, $user_id )
  {
    $bbsTopicsXML = getSessionBBSTopics( $sessionId, $user_id );
    echo XSLTransform( $bbsTopicsXML, 'CoursePage.xsl');
    echo '<a href="http://apps.facebook.com/notesharesep/views/SessionBBSTopics.php?ns_session=' . $sessionId . '" target="_top" class="fbFont left">View all Topics</a>';
  }

  /**
   * Gathers and displays the contents of this session's notes.
   *
   * @return displays table of session's notes
  **/
  function genSessionNotes( $user_id, $sessionId )
  {
    $notesXML = getSessionNotes( $sessionId, $user_id );
    //echo $notesXML;
    echo XSLTransform( $notesXML, 'CoursePage.xsl' );
    echo '<a href="http://apps.facebook.com/notesharesep/views/SessionNotes.php?ns_session=' . $sessionId . '" target="_top" class="fbFont left">View all Notes</a>';
  }
  
   /**
   * Gathers and displays the contents of the session's wall.
   *
   * @return displays the session's wall
  **/
function genSessionWallArea($user_id, $sessionId )
{
	genHeadingBar( "Session Shoutouts" );
	echo
		"<form action=\"/controllers/AddWallPost.php\">" .
		"	<textarea name=\"post_body\"></textarea>" .
		"	<button class=\"drop\" name=\"ns_session\" value=\"$sessionId\">Share</button>" .
		"</form>";
  	// Session Wall
	$wall = getSessionWall($sessionId);
	echo XSLTransform($wall,'CoursePage.xsl');
}


//----------------------Begin View Code----------------------------------//

  genViewHeader( "Course View Page" );
  genPageHeader( array( "Main Page", "Course View" ),
                 array( "/views/UserHomePage.php", "/views/CoursePage.php?ns_session=" . $_GET['ns_session'] ));

  // Course information
  //genHeadingBar( "Course Info" );
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

	genSessionWallArea($user_id, $sessionId);

  // Uploaded Notes?
  genHeadingBar( "Course Notes", "Add New Note Set", "/views/NewNote.php?ns_session=" . $sessionId );
  genSessionNotes( $user_id, $sessionId );
  echo '<br /><br />';

  // SessionBBS
  genHeadingBar( "Session Bulletin Board", "Create New Thread", "/views/NewThread.php?ns_session=" . $sessionId );
  genSessionBBS( $sessionId, $user_id );
  echo '<br /><br />';

  // Classmates in the course
	genHeadingBar( "Classmates" );
	$membersXML = getSessionMembers($user_id, $sessionId);
  echo $membersXML;
	echo XSLTransform($membersXML,'CoursePage.xsl');

  // Close out page
  genViewFooter();
?>


