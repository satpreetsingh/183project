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

  require_once $_SERVER['DOCUMENT_ROOT'] . 'views/View.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/AddCourse.php';

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
    '<form action="http://apps.facebook.com/notesharesep/controllers/AddCourse.php" method="GET" target="_top"
       <table id="ns_table" class="formTable">';
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
          <td class="combo" id="'.$name.'_container">
            <select class="combo" id="' . $name . '" name="' . $name . '" onchange="'.$action.'(); return false;" onmouseover="resetBackground(this); return false;">'
            . $optionString .
            '</select>
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
    '  <tr id="ns_button_row">
         <th></th>
           <td class="editorkit_buttonset">
             <input type="submit" class="submit" value="Add" name="Add" onclick="return validate();"/>   
             <span class="cancel_link">
               <span>or</span>
               <a href="http://apps.facebook.com/notesharesep/views/UserHomePage.php" target="_top">Cancel</a>
             </span>
           </td>
           <td class="right_padding">
           	<button id="ns_revert" class="submit" onclick="restoreAll(); return false;" style="visibility:hidden;" >
           		Revert
           	</button>
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

  // generate standard view header
  genViewHeader( "Add Course" );

  // Generate the heading of the page
  genPageHeader( array( "Main Page",
                        "AddCourse" ),
                 array( "/views/UserHomePage.php",
                        "/views/AddCourse.php" ));
  echo '<br>';
  echo '<script src="http://noteshare.homelinux.net/controllers/AddCourse.js" type="text/javascript"></script>';

  // Get the universities and shove the two additional options in there
  $XML = getUniversity( );
  $universities = XSLTransform( $XML, 'AddCourse.xsl' );

  // Begin the form for the combo boxes
  echo openEditor( );
  echo genCmbBox( "University", ns_university, getDepartments, $universities );
  echo genCmbBox( "Department", ns_department, getCourses, null);
  echo genCmbBox( "Course", ns_course, getSessions, null);
  echo genCmbBox( "Session", ns_session, changedSession, null);
  echo genButtons();
  echo closeEditor();

  echo '<div id="debug" name="debug" style="border: 1px dashed #000000;">debug</div>';

  genViewFooter();
?>

