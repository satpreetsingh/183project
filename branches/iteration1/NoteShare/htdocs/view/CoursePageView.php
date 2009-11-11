<?php
  /**
   * CoursePageView.php
   * Provides information and functionality of a course session. Right now, partisipants.
  **/


  /**
   * Generates a text area with the specified name and intial value
   *
   * @version 1.0
   * @param string $name the name of the text area
   * @param string $initalValue the initial value of the text area
   * @return prints a text area on the view
  **/
  function genTextBox( $name, $initialValue )
  {
    echo '<textarea name="' . $name . '" class="sessionBBS fbFont">' .
         $initialValue . '</textarea>';
  }

  /**
   * Displays the posting link for the session bulletin board system
   *
   * @return displays the link for posting
  **/
  function genPostBBS( )
  {
    echo '<div style="text-align: right;"><a href="" class="fbFont">Post</a></div>';
  }

		require_once $_SERVER['DOCUMENT_ROOT'] . '../php/facebook.php';
		require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.Controller.php';

		include $_SERVER['DOCUMENT_ROOT'] . 'controllers/CourseHomePage.Controller.php';
		include $_SERVER['DOCUMENT_ROOT'] . 'view/xsltView.php';
	  include $_SERVER['DOCUMENT_ROOT'] . 'view/View.php';

echo '<xml version="1.0" encoding="UTF-8">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>NoteShare User Hompage</title>
    <script src="http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="/view/noteshare.css" />
    <script type="text/javascript">
      function initPage() {
        FB_RequireFeatures(["XFBML"],
          function(){ FB.Facebook.init("20f5b69813b87ffd25e42744b326a112",
          "xd_receiver.htm"); });
      }
    </script>
  </head>
  <body onload="initPage(); return false;">';

    genHeader( array( "Main Page", "Course View" ),
                array( "view/UserHomePage.php", "view/CoursePageView.php?session=" . $_GET['session'] ));

    // Course information
    genHeadingBar( "Course Info" );
		$sessionId = $_GET['noteshare_session'];
		$metaXML = getSessionMetadata($sessionId);
		echo XSLTransform($metaXML,'view/coursePageView.xsl');
		echo '</br></br>';

    // Drop course option
		echo "<form action=\"DropCourse.php\" method=\"GET\">"
		. "			<button class=\"drop fbFont\" name=\"session\" value=\"$sessionId\" onclick=\"return confirm(\'Really? Drop the course?\');\">"
		. "			Drop"
		. "	</button>"
		. "</form>";
		echo "</br></br>";

    // SessionBBS
    genHeadingBar( "Session Bulletin Board" );
    $bbsXML = getSessionBBS( $sessionId );
    echo XSLTransform( $bbsXML, 'view/coursePageView.xsl');
    genTextBox( "sessionBBS", "" );
    genPostBBS( );
    echo '<br /><br />';

    // Classmates in the course
		genHeadingBar( "Classmates" );
		$membersXML = getSessionMembers($user_id, $sessionId);
		echo XSLTransform($membersXML,'view/coursePageView.xsl');

    echo '[<fb:name uid="' . $user_id . '" useyou="false"></fb:name>]<br>';
    echo '<a href="#" onclick="status()"> Status </a>';
?>

  <script type="text/javascript">
    function status() {
      alert( FB.Facebook.apiClient.get_session() );
    }

    FB_RequireFeatures(["XFBML"],
      function(){ FB.Facebook.init("20f5b69813b87ffd25e42744b326a112",
        "xd_receiver.htm"); });
  </script>
  </body>
</html>


