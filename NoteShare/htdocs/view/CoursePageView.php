<xml version="1.0" encoding="UTF-8">

<!--
CoursePageView.php
Provides information and functionality of a course session. Right now, partisipants.
-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml"> 
	<head>
		<title>NoteShare Course Hompage</fb:title>
		<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="/view/noteshare.css" />
	</head>
	<body>

	<?php

		require_once $_SERVER['DOCUMENT_ROOT'] . '../php/facebook.php';
		require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.Controller.php';
	
		include $_SERVER['DOCUMENT_ROOT'] . 'controllers/CourseHomePage.Controller.php';
		include $_SERVER['DOCUMENT_ROOT'] . 'view/xsltView.php';
	
    echo '<p class="heading"><a class="fbFont" href="http://apps.facebook.com/notesharesep/view/UserHomePage.php" target="_top">Main Page</a>';
    echo ' | ';
    echo '<a class="fbFont" href="http://apps.facebook.com/notesharesep/view/CoursePageView.php" target="_top">Course Page</a></p>';

    echo '<table class="sessionMetadata">';
    echo '  <tr><td class="fbFont sessionTitleBar">Course Info</td></tr>';
    echo '</table>';

		$sessionId = $_GET['session'];
		$metaXML = getSessionMetadata($sessionId);
		echo XSLTransform($metaXML,'view/coursePageView.xsl');
		echo '</br></br>';

		echo "<form action=\"DropCourse.php\" method=\"GET\">"
		. "			<button class=\"drop\" name=\"session\" value=\"$sessionId\" onclick=\"return confirm(\'Really? Drop the course?\');\">"
		. "			Drop"
		. "	</button>"
		. "</form>";
		
		echo "</br></hr></br>";
	
		$membersXML = getSessionMembers($user_id, $sessionId);
	
		echo '<table><tr><td class="header fbFont">News</td></tr></table>';
//		echo '<table><tr><td class="header fbFont">Classmates:</td></tr></table>';
    //echo $membersXML;
		echo XSLTransform($membersXML,'view/coursePageView.xsl');
	

	?>
		<script type="text/javascript">  
			FB_RequireFeatures(["XFBML"], 
				function(){ FB.Facebook.init("20f5b69813b87ffd25e42744b326a112", 
					"./xd_receiver.htm"); }); 
		</script> 
	</body>
</html>


