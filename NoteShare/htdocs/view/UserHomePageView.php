
	<fb:title>NoteShare User Hompage</fb:title>


<?php

	require_once $_SERVER['DOCUMENT_ROOT'] . '../php/facebook.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.Controller.php';
	
	include $_SERVER['DOCUMENT_ROOT'] . 'controllers/UserHomePage.Controller.php';
	include $_SERVER['DOCUMENT_ROOT'] . 'view/xsltView.php';
	
?>



<?php

	echo "<h1>Hello, <fb:name uid=\"$user_id\" useyou=\"false\" />!</h1>";
	
	$coursesXML = GetCourseListXML($user_id);
	
	echo "<h2>Your Course Participation:</h2>";
	echo "<ul>";
	echo XSLTransform($coursesXML,'userHomePageView.xsl');
	echo "</ul>";
	
?>

