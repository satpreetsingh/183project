<?php
  /**
   * SessionBBS.View.php
   * Displays the session bbs topic and associated posts.
  **/
	require_once $_SERVER['DOCUMENT_ROOT'] . 'views/View.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/GroupBBS.php';

  //--------------------------View Functions--------------------------------//
  /**
   * Generates the Session BBS table, displaying the parent post and all of the
   *  child posts.
   *
   * @param integer $sessionId identifier of this session, which bbs to show
   * @return HTML for Session BBS table
  **/
  function genGroupBBSTable( $sessionId, $parentId, $user_id, $facebook )
  {
    $bbsPosts = getStudyGroupBBSPosts( $sessionId, $parentId, $user_id, $facebook );
    $HTML = XSLTransform( $bbsPosts, 'GroupBBS.xsl' );
    echo html_entity_decode( $HTML );
    echo '<br />';
  }

  /**
   * Generates the Session BBS post box allowing the user to make an
   *   additional post to the BBS.
   *
   * @param integer $sessionId identifier of this session, which bbs to show
   * @return HTML for Session BBS post box
  **/
  function genGroupBBSPost( $parentId, $sessionId, $groupId )
  {
    echo '<form action="http://apps.facebook.com/notesharesep/controllers/GroupBBS.php" method="GET" target="_top">';
    echo '<input type="hidden" name="ns_session" value="' . $sessionId . '">';
    echo '<input type="hidden" name="nsStudyGroup" value="' . $groupId . '">';
    echo '<input type="hidden" name="parentId" value="' . $parentId . '">';
    echo '<textarea id="groupBBSPost" class="fbFont groupBBS"' .
         'name="groupBBSPost">Post Reply...</textarea>';
    echo '<div style="text-align: right;">' .
         ' <input type="submit" value="Post Reply" class="fbFont submit">';
    echo '</div></form>';
  }

  //----------------------Begin View Code----------------------------------//

  genViewHeader( "GroupBBS View" );
  genPageHeader( array( "Main Page",
                        "Course View",
                        "Group View",
                        "Group Thread" ),
                 array( "/views/UserHomePage.php", 
                        "/views/CoursePage.php?ns_session=" . $_GET['ns_session'],
                        "/views/GroupPage.php?ns_session=" . $_GET['ns_session'] . "&nsStudyGroup=" . $_GET['nsStudyGroup'],  
                        "/views/GroupBBS.php?ns_session=" . $_GET['ns_session'] . "&parentId=" . $_GET['parentId'] . "&nsStudyGroup=" . $_GET['nsStudyGroup'] ));

  // Gen Session BBS Table
  $parentId = $_GET['parentId'];
  $sessionId = $_GET['ns_session'];
  $groupId = $_GET['nsStudyGroup'];
  genGroupBBSTable( $sessionId, $parentId, $user_id, $facebook );
  genGroupBBSPost( $parentId, $sessionId, $groupId );

  // Close out page
  genViewFooter();
?>
