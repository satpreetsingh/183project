<?php
  /**
   * SessionBBS.View.php
   * Displays the session bbs topic and associated posts.
  **/
	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/SessionBBS.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . 'views/View.php';

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
    echo XSLTransform( $bbsPosts, 'SessionBBS.xsl' );
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
    echo '<form action="/controllers/SessionBBS.php" method="GET" target="iframe_canvas">';
    echo '<input type="hidden" name="ns_session" value="' . $sessionId . '">';
    echo '<input type="hidden" name="parentId" value="' . $parentId . '">';
    echo '<textarea id="sessionBBSPost" class="fbFont sessionBBS"' .
         'name="sessionBBSPost">Post Reply...</textarea>';
    echo '<div style="text-align: right;">' .
         ' <input type="submit" value="Post Reply" class="fbFont">';
    echo '</div></form>';
  }

  //----------------------Begin View Code----------------------------------//

  genViewHeader( "SessionBBS View" );
  genPageHeader( array( "Main Page",
                        "Course View",
                        "Course Thread" ),
                 array( "/views/UserHomePage.php", 
                        "/views/CoursePage.php?ns_session=" . $_GET['ns_session'],
                        "/views/SessionBBS.php?ns_session=" . $_GET['ns_session'] ));

  //Session BBS Table
  $parentId = $_GET['parentId'];
  $sessionId = $_GET['ns_session'];
  genSessionBBSTable( $parentId );
  genSessionBBSPost( $parentId, $sessionId );

  // Close out page
  genViewFooter();
?>
