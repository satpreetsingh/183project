<?php

include $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';
include $_SERVER['DOCUMENT_ROOT'] . 'view/xsltView.php';

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
    // pass control to functions as dictated by function_name parameter
  switch( $_GET['function_name'] )
  {
      // functions directed towards noteshare database
      case getUniversity:
        $XML = getUniversityDAL();
        $HTML = XSLTransform( $XML, 'AddCourse.View.xsl' );
        if( $HTML == null )
        {
          echo "ERR:FAIL PARSE.";
        } else {
          echo $HTML;
        }
        break;
      case getDepartments:
        $XML = getDepartmentsDAL( $_GET['universityID'] );
        $HTML = XSLTransform( $XML, 'AddCourse.View.xsl' );
        if( $HTML == null )
        {
          echo "ERR: FAILED PARSE." . $XML;
        }
        else
        {
          echo $HTML;
        }
        break;

      case getCourses:
        $XML = getCoursesDAL( $_GET['departmentID'] );
        $HTML = XSLTransform( $XML, 'AddCourse.View.xsl' );
        if( $HTML == null )
        {
          echo "ERR: FAILED PARSE.";
        }
        else
        {
          echo $HTML;
        }
        break;

      case getSessions:
        $XML = getSessionsDAL( $_GET['courseID'] );
        $HTML = XSLTransform( $XML, 'AddCourse.View.xsl' );
        if( $HTML == null )
        {
          echo "ERR: FAILED PARSE.";
        }
        else
        {
          echo $HTML;
        }
        break;

      // functions directed towards facebook database

      // error condition ( function not found )
      default:
        echo "ERR: UNKNOWN FUNCTION - " . $_GET['function_name'];
  }  
// END: AJAX CODE RESPONSE
?>
