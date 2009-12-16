<?php
  /**
   * CoursePageView.php
   * Provides information and functionality of a course session.
   * Right now, partisipants.
  **/

	require_once $_SERVER['DOCUMENT_ROOT'] . 'views/View.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/CourseHomePage.php';


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
    $HTML = XSLTransform( $bbsTopicsXML, 'CoursePage.xsl');
    echo html_entity_decode( $HTML );
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
    echo XSLTransform( $notesXML, 'CoursePage.xsl' );
    echo '<a href="http://apps.facebook.com/notesharesep/views/SessionNotes.php?ns_session=' . $sessionId . '" target="_top" class="fbFont left">View all Notes</a>';
  }

   /**
   * Gathers and displays the contents of the session's wall.
   *
   * @return displays the session's wall
  **/
  function genSessionWallArea($user_id, $sessionId, $facebook )
  {
  	echo "\n\n<br />\n";
  	$parentId = getSessionWallParentDAL($sessionId);
  	genHeadingBar( "Course Wall", "More", "/views/SessionBBS.php?ns_session=" . $sessionId . 
			"&parentId=" . $parentId );
  	echo
		"<form action=\"/controllers/AddWallPost.php\" method=\"GET\">" .
		"	<textarea name=\"post_body\"></textarea>" .
		"	<button class=\"drop\" name=\"ns_session\" value=\"$sessionId\">Share</button>" .
		"</form>";
    echo "<br />\n";

  	// Session Wall
  	$wall = getSessionWall($user_id,$sessionId,$facebook);
	  echo html_entity_decode(XSLTransform($wall,'CoursePage.xsl'));
  }

  /**
   * Gathers the XML data for study groups from the model and does the XSL
   *   transformation.
   *
   * @version 4.0
   * @param string $user_id     Facebook user Id
   * @param string $sessionId   Noteshare session Id
   * @return echos HTML to the course page for the study group listings
  **/
  function genStudyGroups( $sessionId, $user_id )
  {
	  $studyGroupsXML = getSessionStudyGroups( $user_id, $sessionId);
	  echo XSLTransform($studyGroupsXML,'CoursePage.xsl');
  }

  /**
   * Generates the classmates html for the view by retrieving the record via a
   *   controller call.
   *
   * @version 4.0
   * @param int    $sessionId    Session id number  (noteshare)
   * @param string $user_id      Facebook id number (facebook)
   * @return Displays a list of classmates for this course
  **/
  function genClassmates( $sessionId, $user_id, $facebook )
  {
  	$membersXML = getSessionMembers( $user_id, $sessionId, $facebook );
	  echo XSLTransform($membersXML,'CoursePage.xsl');
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

	// Wall
	genSessionWallArea($user_id, $sessionId,$facebook);
	echo '<br /><br />';

  // Uploaded Notes
  genHeadingBar( "Course Notes", "Add New Note Set", "/views/NewNote.php?ns_session=" . $sessionId );
  genSessionNotes( $user_id, $sessionId );
  echo '<br /><br />';

  // SessionBBS
  genHeadingBar( "Course Bulletin Board", "Create New Thread", "/views/NewThread.php?ns_session=" . $sessionId );
  genSessionBBS( $sessionId, $user_id );
  echo '<br /><br />';

  // Classmates in the course
	genHeadingBar( "Classmates" );
  genClassmates( $sessionId, $user_id, $facebook );
	echo '<br /><br />';

  // Study groups associated with this course
  genHeadingBar( "Course Study-Groups", "Create New Study-Group", "/views/NewGroup.php?ns_session=" . $sessionId );
  genStudyGroups( $sessionId, $user_id );

  // Close out page
	genViewFooter();
?>


