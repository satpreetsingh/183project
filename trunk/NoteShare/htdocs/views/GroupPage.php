<?php
	/**
	* /view/GroupPage.php
	* Provides information and functionality of a course study group.
	*
  * Expected $_GET Vars:
  *   int ns_session      = Course ID
  *   int nsStudyGroup    = Study Group ID
  *
	* @version 4.0
	**/

	require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/GroupHomePage.php';
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
  	echo '<textarea name="' . $name . '" class="groupBBS fbFont">' .
	    $initialValue . '</textarea>';
	}

	/**
	* Gathers and displays the contents of this groups's BBS topics.
	* @return displays table of group's BBS topics
	**/
	function genGroupBBS( $sessionId, $groupId, $user_id )
	{
	  $bbsTopicsXML = getGroupBBSTopics( $sessionId, $groupId, $user_id );
	  echo XSLTransform( $bbsTopicsXML, 'GroupPage.xsl');
	  echo '<a href="http://apps.facebook.com/notesharesep/views/GroupBBSTopics.php?nsStudyGroup=' . $groupId . '&ns_session=' . $sessionId . '" target="_top" class="fbFont left">View all Topics</a>';
	}

	/**
	* Gathers and displays the contents of this group's notes.
	*
	* @return displays table of group's notes
	**/
	function genGroupNotes( $sessionId, $groupId, $user_id )
	{
  	$notesXML = getGroupNotes( $groupId, $user_id );
	  echo XSLTransform( $notesXML, 'GroupPage.xsl' );
  	echo '<a href="http://apps.facebook.com/notesharesep/views/GroupNotes.php?nsStudyGroup=' . $groupId . '&ns_session=' . $sessionId . '" target="_top" class="fbFont left">View all Notes</a>';
	}

	/**
	* Gathers and displays the contents of the group's wall.
	*
	* @return displays the group's wall
	**/
	function genGroupWallArea( $sessionId, $groupId, $user_id, $facebook )
	{
	  echo
		  "<form action=\"/controllers/AddWallPost.php\">" .
      " <input type=\"hidden\" name=\"ns_session\" value=\"" . $sessionId . "\">" .
		  "	<textarea name=\"post_body\"></textarea>" .
		  "	<button class=\"drop\" name=\"nsStudyGroup\" value=\"" . $groupId . "\">Share</button>" .
		  "</form>";
    echo "<br />\n";

	  // Group Wall
		$wall = getGroupWall( $groupId, $facebook );
		echo XSLTransform( $wall,'GroupPage.xsl' );
	}

  function genGroupMembers( $user_id, $groupId, $facebook )
  {
	  $membersXML = getGroupMembers($user_id, $groupId, $facebook);
	  echo XSLTransform($membersXML,'GroupPage.xsl');
  }

//----------------------Begin View Code----------------------------------//

	genViewHeader( "Group View Page" );
	genPageHeader( array( "Main Page", "Course View", "Group View" ),
				   array( "/views/UserHomePage.php", 
                  "/views/CoursePage.php?ns_session=" . $_GET['ns_session'],
                  "/views/GroupPage.php?nsStudyGroup=" . $_GET['nsStudyGroup'] . "&ns_session=" . $_GET['ns_session']));

	// Gather page variables
	$sessionId = $_GET['ns_session'];
	$groupId = $_GET['nsStudyGroup'];

  // Group metadata
	$metaXML = getGroupMetadata($groupId);
	//echo $metaXML;
  echo XSLTransform($metaXML,'GroupPage.xsl');
	echo '<br /><br />';

	/*
	// Join/Drop Course
	echo 'DROP or JOIN';
		echo "<form action=\"/controllers/DropGroup.php\" method=\"GET\">"
		   . "			<button class=\"drop fbFont\" name=\"ns_session\" value=\"$sessionId\" onclick=\"return confirm(\'Really? Leave the group?\');\">"
			 . "			Drop"
			 . "	</button>"
			 . "</form>";
		echo "</br></br>";
	*/

	// Wall Area
	genHeadingBar( "Study-Group Wall" );
	genGroupWallArea( $sessionId, $groupId, $user_id, $facebook );

	// Uploaded Notes?
	genHeadingBar( "Group Notes", "Add New Note Set", "/views/NewNote.php?nsStudyGroup=" . $groupId . "&ns_session=" . $sessionId );
	genGroupNotes( $sessionId, $groupId, $user_id );
	echo '<br /><br />';

	// GroupBBS
	genHeadingBar( "Group Bulletin Board", "Create New Thread", "/views/NewThread_Group.php?nsStudyGroup=" . $groupId . "&ns_session=" . $sessionId);
	genGroupBBS( $sessionId, $groupId, $user_id );
	echo '<br /><br />';

	// Study-Group Members
	genHeadingBar( "Study-Group Members" );
  genGroupMembers( $user_id, $groupId, $facebook );

	// Close out page
	genViewFooter();
?>


