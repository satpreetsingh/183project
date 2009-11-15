<?php
/*-----------------------------------------------------------------------------
 File:          view/UserHomePage.php
 Description:   Displays all of the basic user homepage information.
 UseCases:      
 Requirements:  
 Components:    
-------------------------------------------------------------------------------
 Modified On:   11/03/09
 Notes:         Modified and joined the original index with the UserHomePage.
-----------------------------------------------------------------------------*/

  // Inludes
	require_once $_SERVER['DOCUMENT_ROOT'] . 'views/View.php';
 	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/UserHomePage.php';

/****< Local Functions  >******************************************************/
  /**
   * Generates the list of courses that a user is enrolled in
   *
   * @param integer $userId Facebook user Id
   * @version 1.0
   * @return HTML of the course lists
  **/
  function genCourseEnrollment( $userID )
  {
 	  genHeadingBar( "Course Enrollment", "Join a Course", "/views/AddCourse.php" );
    echo '<br />';
	  $coursesXML = getHomePageSessionList( $userID );
	  echo XSLTransform($coursesXML,'UserHomePage.xsl');
  }

/****< Page Content >**********************************************************/

  // generate view headers
  genViewHeader( "NoteShare User Homepage" );
	genPageHeader(	array( "Main Page" ),
				          array( "/views/UserHomePage.php" ));

	echo 'Hello, <fb:name uid="' . $user_id . '" useyou="false"></fb:name>!<br>';
	echo '<br /><br />';

  genCourseEnrollment( $user_id );

  // close out page
  genViewFooter();
?>
