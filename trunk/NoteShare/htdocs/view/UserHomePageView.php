<?php
/*
UserHomePageView.php
Provides a portal to other aspects of our site. Course participation and course add.
*/

		require_once $_SERVER['DOCUMENT_ROOT'] . '../php/facebook.php';
		require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.Controller.php';
	
		include $_SERVER['DOCUMENT_ROOT'] . 'controllers/UserHomePage.Controller.php';
		include $_SERVER['DOCUMENT_ROOT'] . 'view/xsltView.php';
	
    echo '<xml version="1.0" encoding="UTF-8">
          <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
          <html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml"> 
        	<head>
        		<title>NoteShare User Hompage</fb:title>
          		<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>
        	</head>
          <body>';


		echo "<h1><fb:name uid=\"$user_id\" useyou=\"false\" /></h1>";
	
		$coursesXML = GetCourseListXML($user_id);
	
		echo "<h2>Course Enrollment:</h2>";
		echo "<ul>";
		echo XSLTransform($coursesXML,'view/userHomePageView.xsl');
		echo "</ul>";
	
		?>
		
		<a href="">Join Another Course</a>
		
		<script type="text/javascript">  
			FB_RequireFeatures(["XFBML"], 
				function(){ FB.Facebook.init("20f5b69813b87ffd25e42744b326a112", 
					"./xd_receiver.htm"); }); 
		</script> 
	</body>
</html>

