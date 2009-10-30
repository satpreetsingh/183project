
	<fb:title>NoteShare User Hompage</fb:title>


<?php

	require_once '../../php/facebook.php';
	require_once '../controllers/Session.Controller.php';
	
	include '../controllers/UserHomePage.Controller.php';
	include './xsltView.php';
	
	$user_id = $facebook->require_login();
	
?>



<?php

	echo "<h1>Hello, <fb:name uid=\"$user_id\" useyou=\"false\" />!</h1>";
	
	$coursesXML = GetCourseListXML($user_id);
	
	echo "<h2>Your Course Participation:</h2>";
	echo "<ul>";
	echo XSLTransform($coursesXML,'userHomePageView.xsl');
	echo "</ul>";
	
?>

