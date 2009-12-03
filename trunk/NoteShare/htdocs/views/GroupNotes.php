<?php
  /**
   * CoursePageView.php
   * Provides information and functionality of a course session.
   * Right now, partisipants.
  **/
	require_once $_SERVER['DOCUMENT_ROOT'] . 'views/View.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/GroupNotes.php';


//--------------------------View Functions--------------------------------//
  /**
   * Gathers and displays the contents of this session's notes.
   *
   * @return displays table of session's notes
  **/
  function genGroupNotes( $user_id, $groupId )
  {
    $notesXML = getStudyGroupNotes( $groupId, $user_id );
    echo XSLTransform( $notesXML, 'GroupNotes.xsl' );
  }

//----------------------Begin View Code----------------------------------//

  genViewHeader( "Group Notes Page" );
  genPageHeader( array( "Main Page", 
                        "Course View",
                        "Group View",
                        "Group Notes" ),
                 array( "/views/UserHomePage.php",
                        "/views/CoursePage.php?ns_session=" . $_GET['ns_session'],
                        "/views/GroupPage.php?ns_session=" . $_GET['ns_session'] . "&nsStudyGroup=" . $_GET['nsStudyGroup'],
                        "/views/GroupNotes.php?ns_session=" . $_GET['ns_session'] . "&nsStudyGroup=" . $_GET['nsStudyGroup'] ));

  $sessionId = $_GET['ns_session'];
  $groupId = $_GET['nsStudyGroup'];

  // Uploaded Notes?
  genHeadingBar( "Group Notes", "Add New Note Set", "/views/NewNote_Group.php?ns_session=" . $sessionId . "&nsStudyGroup=" . $groupId );
  genGroupNotes( $user_id, $groupId );
  echo '<br /><br />';

  // Close out page
  genViewFooter();
?>


