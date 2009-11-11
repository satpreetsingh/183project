<?php

/**
 * AddCourse.php
 *
 * Responsible for displaying the add course view.  Most of the visual content
 * is driven by a combination of php functions.  The combo boxes where the
 * user can select the universities, depts, courses, etc. are driven by AJAX
 * calls.  The XML response is converted to options within the combo boxes
 * via XSLT.
 *
 * The expect way to generate an add course form is this sequence:
 *  openEditor() -- once, first
 *  genCmbBox() -- several
 *  genButtons() -- once, after the combo boxes
 *  closeEditor() -- once, last
 *
 * See ajaxView.php for the AJAX implementation.
**/

  require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.Controller.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . 'view/xsltView.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . 'view/View.php';

  /**
   * Prints the initial opening tags for the add course form.  Function posts
   * to itself for actual course registration.
   *
   * @version 1.0
   * @return prints open form tags, and starts form table
  **/
  function openEditor( )
  {
    echo '' .
    '<form action="' . $PHP_SELF . '" method="post" target="_self">
       <table class="formTable">';
  }

  /**
   * Generates a combo box with the specified label, name, action
   * and option string (optional).
   *
   * @version 1.0
   * @param string $label the label to display left of the combo box
   * @param string $name the name AND id to give the combo box
   * @param string $action the function to call when a combo box option is selected
   * @param string $optionString (optional) options within the combo box, must be
   *               HTML formatted <option>'s
   * @return prints one form table row with a combo box with the specified
   *          parameters
  **/
  function genCmbBox( $label, $name, $action, $optionString )
  {
    echo '' .
    '   <tr class="combo">
          <th class="combo">
            <label class="fbFont large">' . $label . ' : </label>
          </th>
          <td class="combo">
            <select class="combo" id="' . $name . '" name="' . $name . '" onchange="'.$action.'(); return false;" onmouseover="resetBackground(this); return false;">';
    if( $optionString != null )
    {
      echo $optionString;
      echo '<option value="-1"></option>';
    }
    echo ''.
    '       </select>
          </td>
        </tr>';
  }

  /**
   * Prints the closing submission button and cancel link.  This in turn
   * submits to the location specified by openEditor()
   *
   * @version 1.0
   * @return prints the closing submission buttons
  **/
  function genButtons()
  {
    echo '' .
    '  <tr>
         <th></th>
           <td class="editorkit_buttonset">
             <input type="submit" class="add action" value="Add" name="Add"/>   
             <span class="cancel_link">
               <span>or</span>
               <a href="http://apps.facebook.com/notesharesep/view/UserHomePage.php" target="_top">Cancel</a>
             </span>
           </td>
           <td class="right_padding">
           </td>
       </tr>
    ';
  }

  /**
   * Closes the form table and the form itself.
   *
   * @version 1.0
   * @return prints close form table and close form tag
  **/
  function closeEditor()
  {
    echo '</table></form>';
  }

  /**
   * This section of code runs once the add course button has been selected.
   * This is referenced on the first page load, but the function shouldn't be
   * entered.
   *
   * The hanging else is to allow the page to generate view content if the
   * user hasn't posted.  However, if the user has posted...since we're using
   * a redirect, it was important to ensure no html content would be generated.
   * Plus, that would just be a waste anyways since no one will see it.
  **/
  if( isset( $_POST['Add'] ))
  {
    if( isset( $_POST['session'] ))
    {
      $out = addUserSessionDAL( $user_id, $_POST['session'] );
    }
    $facebook->redirect( 'http://apps.facebook.com/notesharesep/view/UserHomePage.php' );
  }
  else {
?>


<html>
<head>
  <script src="http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" href="/view/noteshare.css">
  <script src="/controllers/AddCourse.Controller.js" type="text/javascript">
  </script>

  <script type="text/javascript">
    FB.init("20f5b69813b87ffd25e42744b326a112", "xd_receiver.htm");
  </script>
</head>

<?php
  // Generate the heading of the page
  genHeader( array( "Main Page",
                    "AddCourse" ),
             array( "view/UserHomePage.php",
                    "view/AddCourse.php" ));
  echo '<br>';

  // Get the universities and shove the two additional options in there
  $XML = getUniversityDAL( );
  $universities = XSLTransform( $XML, 'view/AddCourse.View.xsl' );
  $universities = '<option value="-1" class="empty"></option>
                   <option value="0" class="new">Add University...</option>'
                  . $universities;

  // Begin the form for the combo boxes
  echo openEditor( );
  echo genCmbBox( "University", university, getDepartments, $universities );
  echo genCmbBox( "Department", department, getCourses, null);
  echo genCmbBox( "Course", course, getSessions, null);
  echo genCmbBox( "Session", session, "", null);
  echo genButtons();
  echo closeEditor();

  }
?>
</body>
</html>

