<?php
/*-----------------------------------------------------------------------------
 File:         addcourse.php
 Description:  An example mockup of how the Add Course dialog could look.
 UseCases:     <Later>
 Requirements: <Later>
 Components:   <Later>
------------------------------------------------------------------------------
 Modified On:   10/25/09
 Notes:         Attempting to mock out generalized interactions between the
                view, controller, and model.  In this file, all of the
                interactions will be between the view and the controller.

 Modified On:   10/07/09
 Notes:         Quick initial creation of add course page.  Used for an
                example of FBML, a little Facebook API, and a little php.
-----------------------------------------------------------------------------*/

require_once '../php/facebook.php';
require_once './controllers/Session.Controller.php';
require_once './controllers/AddCourse.Controller.php';

function generateTextBox( $label, $name )
{
  echo '' .
  '<table class="editorkit" border="0" cellspacing="0" style="width:425px">' .
  '  <tr class="width_setter">' .
  '    <th style="width:75px"></th>' .
  '    <td></td>' .
  '  </tr>' .
  '  <tr>' .
  '    <th><label>' . $label . '</label></th>' .
  '    <td class="editorkit_row">' .
  '      <input type="text" value="' . $value . '" name="' . $name . '"/>' .
  '    </td>' .
  '    <td class="right_padding">' .
  '    </td>' .
  '  </tr>' .
  '</table>';
}

function generateTextArea( $label, $name )
{
  echo '' .
  '<table class="editorkit" border="0" cellspacing="0" style="width:425px">' .
  '  <tr class="width_setter">' .
  '    <th style="width:75px"></th>' .
  '    <td></td>' .
  '  </tr>' .
  '  <tr>' .
  '    <th class="detached_label">' .
  '      <label>' . $label . '</label>' .
  '    </th>' .
  '    <td class="editorkit_row">' .
  '      <textarea rows="2" name="' . $name . '"></textarea>' .
  '    </td>' .
  '    <td class="right_padding">' .
  '    </td>' .
  '  </tr>' .
  '</table>';
}

/**
 * Name:        generateComboBox( $label, $name, $action )
 * Description: Generates a combo box with the specified parameters and
 *              more importantly, the specified action method which will handle
 *              the combo box event.
 * Parameters:  label == text that will be displayed to the left of the combo box
 *              name  == both name and ID of the input accessible in the DOM
 *              action == name of the event handler function found within the
 *                        controller
**/
function generateComboBox( $label, $name, $action )
{
  // generate top "half" of the code based off the fb to html conversion
  $toReturn = '' .
  ' <table class="editorkit" border="0" cellspacing="0" style="width:425px">
       <tr class="width_setter">
        <th style="width:75px"></th>
        <td></td>
      </tr>
      <tr>
        <th class="detached_label">
          <label>' . $label . '</label>
        </th>
       <td class="editorkit_row">
          <select name="' . $name . '" id="' . $name . '"
          onchange="var combobox = document.getElementById( \'' . $name . '\' );
                     var selected = combobox.getValue();';

  // append on the specified name for function handling
  $toReturn = $toReturn . '                   ' . $action . '( selected );' .
  '                  return false;">
          </select>
        </td>
        <td class="right_padding"></td>
      </tr>
    </table>';

  // return result
  echo $toReturn;
}// end generateComboBox( $label, $name, $action )

function fillComboBox( $name, $function_name )
{
  include './view/xstlView.php';

  var xmlResponse = 
  XSLTransform( 

}
?>


<script type="text\javascript">
<!--
  function fillComboBox( comboBoxName )
  {
    xmlResponse = getUniversitiesXML();
    htmlResponse = translateXML( xmlResponse );
    document.getElementById( "txt_University" ).setTextValue( htmlResponse );
  }

  function loadXMLDoc( dname )
  {

    if( window.XMLHttpRequest )
    {
      xhttp = new XMLHttpRequest();
    }
    else
    {
      xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.open("GET",dname,false);
    xhttp.send("");
    return xhttp.responseXML;
  }


  function translateXML( xmlResponse )
  {
    xml=loadXMLDoc( xmlResponse );
    xsl=loadXMLDoc("./view/AddCourse.View.xsl");
    // code for IE
    if (window.ActiveXObject)
    {
      ex=xml.transformNode(xsl);
    }
    // code for Mozilla, Firefox, Opera, etc.
    else if (document.implementation && document.implementation.createDocument)
    {
      xsltProcessor=new XSLTProcessor();
      xsltProcessor.importStylesheet(xsl);
      ex = xsltProcessor.transformToFragment(xml,document);
    }

    return ex;
  }
-->
</script>


<!--------------------------- Actual Page Content ----
<a href=\"index.php\">Back to User Home Page</a><br>
<br>
<br>
<H1>Add A Course</H1>
<br>
<br>
/*
<!--included so that the facebook style sheet will automatically be
    retrieved. -->
<span style="display:none"><fb:editor action=""></fb:editor></span>

<form action="" method="">
  <div id="cmb_University">
    <?php
      echo generateComboBox( 'University', 'university', 'getDepartmentsXML' );
      echo fillComboBox( 'university', 'getUniversitiesXML' );
    ?>
  </div>
  <div id="txt_University" style="background-color: #0000FF;"><br></div>
  <div id="cmb_Dept" style="background-color: #00F0FF;"><br></div>
  <div id="txt_Dept" style="background-color: #00F0FF;"><br></div>
  <div id="cmb_Course" style="background-color: #F0F0FF;"><br></div>
  <div id="txt_Course" style="background-color: #F0F0FF;"><br></div>
  <div id="cmb_Session" style="background-color: #FF0000;"><br></div>
  <div id="txt_Session" style="background-color: #FF0000;"><br></div>

  <div id="postButtons" style="background-color: #FFFF00;">
    <table class="editorkit" border="0" cellspacing="0" style="width:425px">
      <tr class="width_setter">
        <th style="width:75px"></th>
        <td></td>
      </tr>
      <tr>
        <th></th>
        <td class="editorkit_buttonset">
          <input type="submit" class="editorkit_button action" value="Add"
             name="btn_Add" />
          <span class="cancel_link">
            <span>or</span>
            <a href="index.php">Cancel</a>
          </span>
        </td>
        <td class="right_padding"></td>
      </tr>
    </table>
  </div>
</form>
---->
