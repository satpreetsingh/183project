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
   * Inserts the user id of the viewer into the xml response
  **/
  function insertUserId( $bbsPosts, $user_id )
  {
    $temp = explode( "\n", $bbsPosts );
    $response = $temp[0] . $temp[1] . $temp[2] . '<userId>' . $user_id . "</userId>\n";
    for( $i = 3; $i < count( $temp ); $i++ )
    {
      $response = $response . $temp[$i];
    }

    return $response;
  }

  /**
   * Generates the Session BBS table, displaying the parent post and all of the
   *  child posts.
   *
   * @param integer $sessionId identifier of this session, which bbs to show
   * @return HTML for Session BBS table
  **/
  function genSessionBBSTable( $parentId, $user_id )
  {
    $bbsPosts = getSessionBBSPosts( $parentId, $user_id );
    echo $bbsPosts;
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
    echo '<form action="http://apps.facebook.com/notesharesep/controllers/SessionBBS.php" method="GET" target="_top">';
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
                        "/views/SessionBBS.php?ns_session=" . $_GET['ns_session'] . "&parentId=" . $_GET['parentId'] ));

  // Gen Session BBS Table
  $parentId = $_GET['parentId'];
  $sessionId = $_GET['ns_session'];
  genSessionBBSTable( $parentId, $user_id );
  genSessionBBSPost( $parentId, $sessionId );

  // Close out page
  genViewFooter();
?>
