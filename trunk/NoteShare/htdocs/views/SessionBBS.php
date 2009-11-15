<?php
  /**
   * SessionBBS.View.php
   * Displays the session bbs topic and associated posts.
  **/

  //--------------------------View Functions--------------------------------//

  /**
   * Generates the Session BBS table, displaying the parent post and all of the
   *  child posts.
   *
   * @param integer $sessionId identifier of this session, which bbs to show
   * @return HTML for Session BBS table
  **/
  function genSessionBBSTable( $parentId )
  {
    $bbsPosts = getSessionBBSPosts( $parentId );
    echo '<br />';
    echo XSLTransform( $bbsPosts, 'view/SessionBBS.View.xsl' );
    echo '<br />';
  }

  /**
   * Generates the Session BBS post box allowing the user to make an
   *   additional post to the BBS.
   *
   * @param integer $sessionId identifier of this session, which bbs to show
   * @return HTML for Session BBS post box
  **/
  function genSessionBBSPost( $parentId, $sessionId )
  {
    echo '<form action="http://apps.facebook.com/notesharesep/controllers/' .
            'SessionBBS.Controller.php" method="GET" target="iframe_canvas">';
    echo '<input type="hidden" name="noteshare_session" value="' . $sessionId . '">';
    echo '<input type="hidden" name="parentId" value="' . $parentId . '">';
    echo '<textarea id="sessionBBSPost" class="fbFont sessionBBS"' .
         'name="sessionBBSPost">Post Reply...</textarea>';
    echo '<div style="text-align: right;">' .
         ' <input type="submit" value="Post Reply" class="fbFont">';
    echo '</div></form>';
  }

  //----------------------Begin View Code----------------------------------//

	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.Controller.php';

	include $_SERVER['DOCUMENT_ROOT'] . 'controllers/SessionBBS.Controller.php';
	include $_SERVER['DOCUMENT_ROOT'] . 'view/xsltView.php';
	include $_SERVER['DOCUMENT_ROOT'] . 'view/View.php';

  genViewHeader( "SessionBBS View" );
  genPageHeader( array( "Main Page",
                        "Course View",
                        "Course Thread" ),
                 array( "view/UserHomePage.php", 
                        "view/CoursePageView.php?noteshare_session=" . $_GET['noteshare_session'],
                        "view/SessionBBS.View.php?noteshare_session=" . $_GET['noteshare_session'] ));

  //Session BBS Table
  $parentId = $_GET['parentId'];
  $sessionId = $_GET['noteshare_session'];
  genSessionBBSTable( $parentId );
  genSessionBBSPost( $parentId, $sessionId );
?>

  <script type="text/javascript">
    FB_RequireFeatures(["XFBML"],
      function(){ FB.Facebook.init("20f5b69813b87ffd25e42744b326a112",
        "xd_receiver.htm"); });
  </script>
  </body>
</html>


