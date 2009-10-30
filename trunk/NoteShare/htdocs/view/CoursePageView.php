<?php

	require_once $_SERVER['DOCUMENT_ROOT'] . '../php/facebook.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.Controller.php';
	
	include $_SERVER['DOCUMENT_ROOT'] . 'controllers/CourseHomePage.Controller.php';
	include $_SERVER['DOCUMENT_ROOT'] . 'view/xsltView.php';
	
?>


<fb:title>NoteShare Course Hompage</fb:title>

<?php
	$sessionId = $_POST['session'];
	$metaXML = getSessionMetadata($sessionId);
	
	echo XSLTransform($metaXML,'coursePageView.xsl');
	
	echo "</br></hr></br>";
	
	$membersXML = getSessionMembers($user_id, $sessionId);
	
	echo "<h3>Course Participants:</h3>";
	echo XSLTransform($memberXML,'coursePageView.xsl');
	
?>

</body>


