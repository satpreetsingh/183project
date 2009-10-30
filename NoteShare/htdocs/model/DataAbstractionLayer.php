<?php
/*-----------------------------------------------------------------------------
 File:         DataAbstractionLayer.php
 Description:  The DataAbstractionLayer should be exactly what it sounds like.
               This "class" provides an abstraction for the controllers to the
               Facebook and Noteshare databases.

               Functionality surrounding the Facebook and Noteshare databases
               can be found in FacebookDatabase.php and NoteshareDatabase.php
               respectively.

 UseCases:     <Later?>
 Requirements: <Later?>
 Components:   DataAbstractionLayer (from the model)
-------------------------------------------------------------------------------
 Modified On:   10/25/09
 Notes:         Modified existing source into file heirarchy.
                Placed noteshare and facebook database code into separate files.
                Might consider placing XML generation into this file as opposed
                  to just placing in both sub-files (code-duplication).
                Also function_name mapping should probably be dictated within
                  the individual sub-components, but placing an extra POST var
                  in the ajax querry would require that the controllers know
                  which database has the desired information.  This is
                  undesired since this will break the DAL's whole purpose.

 Modified By:   ????
 Modified On:   ????
 Notes:         Initial creation.
-----------------------------------------------------------------------------*/

  include 'FacebookDatabase.php';         // facebook database functions
  include 'NoteshareDatabase.php';        // noteshare database functions

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
  // grab user id
  $user = isset($_POST['fb_sig_user']) ? $_POST['fb_sig_user'] : null;

  // only act if user is logged in
  if( isset( $user ))
  {
    // pass control to functions as dictated by function_name parameter
    switch( $_GET['function_name'] )
    {
      // functions directed towards noteshare database
      case getUniversity:
        echo getUniversity();
        break;

      // functions directed towards facebook database


      // error condition ( function not found )
      default:
        echo "ERR: UNKNOWN FUNCTION";
    }
  }
  // else: user is not logged in
  else
  {
    echo "ERR: REQUIRE LOGIN";
  }
// END: AJAX CODE RESPONSE
?>
