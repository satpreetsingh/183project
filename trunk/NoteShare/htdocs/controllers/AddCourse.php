<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/views/View.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/Session.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/model/NoteshareDatabase.php';

/*
* Function:     Ajax response (not really a function per say)
* Description:  This code segment is responsible for taking in the ajax querries
*               of the various controllers and making the appropriate ajax
*               response.
* Parameters:   (Note: NOT traditional parameters)
*               $_POST:
*                 fb_sig_user -- facebook user id
*                   (automatically posted by facebook if the user is logged in)
*               $_GET:
*                 function_name -- name of function that sent the ajax querry
*                   (typically directed to response function of same name)
* Return: Appropriate response will come from a "response" function call, i.e.
*           a function with function_name that processes $_POST parameters
*         ERR: UNKNOWN FUNCTION -- if the receieved function_name parameter does
*           not match any defined function
*         ERR: REQUIRE LOGIN -- if the user is not currently logged in
*/
// START: AJAX CODE RESPONSE
  if( isset( $_GET['Add']))
  {
    addCourse( $user_id, $_GET['ns_session'] );
  }
  else
  {
    $XML = "";
    // pass control to functions as dictated by function_name parameter
    switch( $_GET['function_name'] )
    {
      case null:
        return;

      case getUniversity:
        $XML = getUniversityDAL();
        break;

      case getDepartments:
        $XML = getDepartmentsDAL( $_GET['ns_universityID'] );
        break;

      case getCourses:
        $XML = getCoursesDAL( $_GET['ns_departmentID'] );
        break;

      case getSessions:
        $XML = getSessionsDAL( $_GET['ns_courseID'] );
        break;

      // error condition ( function not found )
      default:
        echo "ERR: UNKNOWN FUNCTION - " . $_GET['function_name'];
    }

    $HTML = XSLTransform( $XML, "AddCourse.xsl" );
    if( $HTML == null )
    {
      echo "ERR:FAIL PARSE.";
    } else {
//      echo "SOMETHING";
      echo $HTML;
    }
  }
// END: AJAX CODE RESPONSE

  /**
   * Transparent function to retrieve university list from the model as XML
   *
   * @return XML university list
  **/
  function getUniversity()
  {
    return getUniversityDAL();
  }

  /**
   * Uses DAL function to add the specified course to the registered user.
   *
   * @param integer $userId Facebook user ID
   * @param integer $sessionId Session id number
  **/
  function addCourse( $userId, $sessionId )
  {
    if( $userId && $sessionId )
    {
      $out = addUserSessionDAL( $userId, $sessionId );
    }

    // API key for our application, needed for facebook session
    $appapikey = '20f5b69813b87ffd25e42744b326a112';

    // Secret key that's also needed for a facebook session
    $appsecret = '9c30a702413dccd1856b58d2fab4c992';

    // Create the facebook session
    $facebook = new Facebook($appapikey, $appsecret, true);

    $facebook->redirect( 'http://apps.facebook.com/notesharesep/views/UserHomePage.php' );
  }
?>
