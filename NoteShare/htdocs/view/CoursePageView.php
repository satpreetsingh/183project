<?php

	require_once $_SERVER['DOCUMENT_ROOT'] . '../php/facebook.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.Controller.php';
	
	include $_SERVER['DOCUMENT_ROOT'] . 'controllers/CourseHomePage.Controller.php';
	include $_SERVER['DOCUMENT_ROOT'] . 'view/xsltView.php';
	
	$user_id = $facebook->require_login();
	
?>


<fb:title>NoteShare Course Hompage</fb:title>

<?php
	$sessionId = $_POST['session'];
	$metaXML = getSessionMetadata($sessionId);
	
	echo XSLTransform($metaXML,'coursePageView.xsl');
	
	echo "</br></hr></br>";
	
	$membersXML = getSessionMembers($sessionId);
	
	echo "<h3>Course Partisipants:</h3>";
	echo XSLTransform($metaXML,'coursePageView.xsl');
	
?>

</body>


