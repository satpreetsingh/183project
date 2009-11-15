<?php
  /**
   * CoursePageView.php
   * Provides information and functionality of a course session.
   * Right now, partisipants.
  **/

//--------------------------View Functions--------------------------------//
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
  function genNewThreadBBS( )
  {
    echo '<div style="text-align: right;">' .
         '<a href="http://apps.facebook.com/notesharesep/view/SessionBBS.View.php?newPost=1" class="fbFont">' .
         'Create New Thread</a></div>';
  }

  /**
   * Gathers and displays the contents of this session's BBS topics.
   *
   * @return displays table of session's BBS topics
  **/
  function genSessionBBS()
  {
    $bbsTopicsXML = getSessionBBSTopics( $sessionId );
    echo XSLTransform( $bbsTopicsXML, 'view/coursePageView.xsl');
    echo '<br />';
    genNewThreadBBS();
  }

//----------------------Begin View Code----------------------------------//

  require_once $_SERVER['DOCUMENT_ROOT'] . '../php/facebook.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.Controller.php';

	include $_SERVER['DOCUMENT_ROOT'] . 'controllers/CourseHomePage.Controller.php';
	include $_SERVER['DOCUMENT_ROOT'] . 'view/xsltView.php';
	include $_SERVER['DOCUMENT_ROOT'] . 'view/View.php';

  genViewHeader( "Course View Page" );
  genPageHeader( array( "Main Page", "Course View" ),
                 array( "/view/UserHomePage.php", "/view/CoursePageView.php?session=" . $_GET['session'] ));

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
  genSessionBBS();
  echo '<br /><br />';

  // Classmates in the course
	genHeadingBar( "Classmates" );
	$membersXML = getSessionMembers($user_id, $sessionId);
	echo XSLTransform($membersXML,'view/coursePageView.xsl');

  echo '[<fb:name uid="' . $user_id . '" useyou="false"></fb:name>]<br>';
?>

  <script type="text/javascript">
    FB_RequireFeatures(["XFBML"],
      function(){ FB.Facebook.init("20f5b69813b87ffd25e42744b326a112",
        "xd_receiver.htm"); });
  </script>
  </body>
</html>


