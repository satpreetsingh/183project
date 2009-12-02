<?php
  /**
   * CoursePageView.php
   * Provides information and functionality of a course session.
   * Right now, partisipants.
  **/
	require_once $_SERVER['DOCUMENT_ROOT'] . 'views/View.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/SessionNotes.php';


//--------------------------View Functions--------------------------------//
  /**
   * Gathers and displays the contents of this session's notes.
   *
   * @return displays table of session's notes
  **/
  function genSessionNotes( $user_id, $sessionId )
  {
    $notesXML = getSessionNotes( $sessionId, $user_id );
    echo XSLTransform( $notesXML, 'SessionNotes.xsl' );
  }

//----------------------Begin View Code----------------------------------//

  genViewHeader( "Session Notes Page" );
  genPageHeader( array( "Main Page", 
                        "Course View",
                        "Session Notes" ),
                 array( "/views/UserHomePage.php",
                        "/views/CoursePage.php?ns_session=" . $_GET['ns_session'],
                        "/views/SessionNotes.php?ns_session=" . $_GET['ns_session'] ));

  $sessionId = $_GET['ns_session'];

  // Uploaded Notes?
  genHeadingBar( "Course Notes", "Add New Note Set", "/views/NewNote.php?ns_session=" . $sessionId );
  genSessionNotes( $user_id, $sessionId );
  echo '<br /><br />';

  // Close out page
  genViewFooter();
?>


