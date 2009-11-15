<?php

  require_once $_SERVER['DOCUMENT_ROOT'] . '/model/NoteshareDatabase.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/views/View.php';
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
        $XML = getDepartmentsDAL( $_GET['universityID'] );
        break;

      case getCourses:
        $XML = getCoursesDAL( $_GET['departmentID'] );
        break;

      case getSessions:
        $XML = getSessionsDAL( $_GET['courseID'] );
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
    return $HTML;
  }
// END: AJAX CODE RESPONSE

  function getUniversity()
  {
    return getUniversityDAL();
  }
?>
