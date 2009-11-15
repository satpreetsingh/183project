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

  function genCourseEnrollment( $userID )
  {
	  $coursesXML = getHomePageSessionList( $userID );
	  genHeadingBar( "Course Enrollment" );
	  echo XSLTransform($coursesXML,'view/userHomePageView.xsl');
  }

// All pages require the session controller
 	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.Controller.php';

	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/UserHomePage.Controller.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . 'view/xsltView.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . 'view/View.php';

  genViewHeader( "NoteShare User Homepage" );
	genPageHeader(	array( "Main Page" ),
				          array( "view/UserHomePage.php" ));

	echo '<fb:name uid="' . $user_id . '" useyou="false"></fb:name><br>';
	echo '<fb:profile-pic uid="' . $user_id . '"></fb:profile-pic>';
	echo '<br /><br />';

  genCourseEnrollment( $user_id );
?>
	<a class="fbFont" href="http://apps.facebook.com/notesharesep/view/AddCourse.php" target="iframe_canvas">Join Another Course</a>

	<script>
		function postQuery( sessionID ) 
		{
			var query = window.location.search.substring(1);
			query = "http://apps.facebook.com/notesharesep/view/CoursePageView.php?" + query + "&session=" + sessionID;
			top.location = query;
		}
	</script>

	<script type="text/javascript">
		FB_RequireFeatures(["XFBML"],
			function(){ FB.Facebook.init("20f5b69813b87ffd25e42744b326a112",
				"xd_receiver.htm"); });
	</script>
	</body>
</html>
