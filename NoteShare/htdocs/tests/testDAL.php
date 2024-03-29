<?php
  /**
   * Contains a barage of tests for iterations 1,2,3,4.  This excerisizes the
   *   DAL functions to ensure proper insertion into and removal from the
   *   database.
   *
   * This is a script intended to be run from the command line via 'php'  This
   *   will not currently render properly into a web page.
  **/

//----< Main Tests >----------------------------------------------------------//
  $_SERVER = array( "DOCUMENT_ROOT" => "/var/www/localhost/htdocs/" );
  require_once( $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php' );
  require_once( $_SERVER['DOCUMENT_ROOT'] . '../php/facebook.php' );

   // API key for our application, needed for facebook session
  $appapikey = '20f5b69813b87ffd25e42744b326a112';

  // Secret key that's also needed for a facebook session
  $appsecret = '9c30a702413dccd1856b58d2fab4c992';

  // Create the facebook session
  $facebook = new Facebook($appapikey, $appsecret, true);

  // Require that the user be logged in to use the page
  $user_id = $facebook->require_login();

  // Testing Variables
  $_TESTVARS = array( "userId" => 14821122,
                      "sessionId" => 1,
                      "groupId" => 3,
                      "heading" => "Test Heading",
                      "body" => "Test Body");

  //test_iteration2( $_TESTVARS );
  //test_iteration4( $_TESTVARS );
  $result = getSessionMembersDAL( $_TESTVARS['sessionId'],
                                  $_TESTVARS['userId'],
                                  $facebook );
  echo "RESULT=" . $result;

//----< Iteration 1 Tests >---------------------------------------------------//
//----< Iteration 2 Tests >---------------------------------------------------//
  function test_iteration2( $_TESTVARS )
  {
    test_getSessionWallParent( $_TESTVARS );
    test_addSessionWallPostDAL( $_TESTVARS );
    test_addSessionBBSPostDAL();
  }

  function test_getSessionWallParent( $_TESTVARS )
  {
    echo "Testing getSessionWallParentDAL( " . $sessionId . " )\n";
    $response = getSessionWallParentDAL( $sessionId );
    echo "Response: " . $response . "\n";
  }

  function test_addSessionWallPostDAL( $_TESTVARS )
  {
    echo "Testing addSessionWallPostDAL( " . $userId . ", " 
           . $sessionId . ")\n";
    $response = addSessionWallPostDAL( $userId, $sessionId, "Test Post." );
    echo "Response: " . $response . "\n";
  }

  function test_addSessionBBSPostDAL( $_TESTVARS )
  {
    echo "Testing addSessionBBSPostDAL( " . $userId . ", " . $sessionId
           . ")\n";
    $response = addSessionBBSPostDAL( $userId, $sessionId, "Test Header",
                  "Test Body", null );
    echo "Reponse: " . $response . "\n";
  }
//----< Iteration 3 Tests >---------------------------------------------------//

//----< Iteration 4 Tests >---------------------------------------------------//
  function test_iteration4( $_TESTVARS )
  {
    test_createStudyGroupDAL( $_TESTVARS );
  }

  function test_getStudyGroupsDAL( $_TESTVARS )
  {
    $response = getStudyGroupsDAL( $_TESTVARS['userId'], $_TESTVARS['sessionId'] );
    echo "Response: " . $response . "\n";
  }

  function test_getStudyGroupWallParentDAL( $_TESTVARS )
  {
    $response = getStudyGroupWallParentDAL( $_TESTVARS['groupId'] );
    echo "Response: " . $response . "\n";
  }

  function test_createStudyGroupDAL( $_TESTVARS )
  {
    $response = createStudyGroupDAL( $_TESTVARS['sessionId'], $_TESTVARS['heading'],
                                      $_TESTVARS['body'] );
    echo "Response: " . $response . "\n";
  }

//----< Iteration 5 Tests >---------------------------------------------------//
?>
