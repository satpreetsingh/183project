<xml version="1.0" encoding="UTF-8">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
	<head>
		<title>NoteShare User Hompage</title>
    <script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="/view/noteshare.css" />
	</head>
	<body>

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

// All pages require the session controller
	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.Controller.php';

	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/UserHomePage.Controller.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . 'view/xsltView.php';

/*
  $userDetails = $facebook->api_client->users_getInfo( $user_id, 'last_name, first_name');
  if( $userDetails != null )
  {
    echo "<p>Hello, " . $userDetails[0]['first_name'] . "!</p>";
  } else {
    echo $user_id;
  }
*/
    echo '<h1 class="title facebookText">NoteShare (Beta)</h1>';
		echo '<div xmlns:fb="http://www.facebook.com/2008/fbml"><fb:name uid="' . $user_id . '" useyou="false"></fb:name></div>';
	
		$coursesXML = getHomePageSessionListDAL( $user_id );
	
    echo '<table><tr><td><h2 class="sectionHeading">Course Enrollment:</h2></td></tr></table>';
		echo '<ul>';
    //echo $coursesXML;
		echo XSLTransform($coursesXML,'view/userHomePageView.xsl');
		echo "</ul>";

		?>
		
		<a href="http://apps.facebook.com/notesharesep/view/AddCourse.php" target="_top">Join Another Course</a>
		

	<script type="text/javascript">  
		FB_RequireFeatures(["XFBML"], 
			function(){ FB.Facebook.init("20f5b69813b87ffd25e42744b326a112",
				"xd_receiver.htm"); }); 
  </script> 
	</body>
</html>
