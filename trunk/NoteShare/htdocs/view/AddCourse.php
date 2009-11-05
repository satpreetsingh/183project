<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.Controller.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . 'view/xsltView.php';

  function openEditor( )
  {
    echo '' .
    '<form action="' . $PHP_SELF . '" method="post" target="_self">
       <table class="formTable">';
  }

  function genCmbBox( $label, $name, $action, $optionString )
  {
    echo '' .
    '   <tr class="combo">
          <th class="combo">
            <label class="combo facebookText">' . $label . ' : </label>
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

  function genButtons()
  {
    echo '' .
    '  <tr>
         <th></th>
           <td class="editorkit_buttonset">
             <input type="submit" class="editorkit_button action" value="Add" name="Add"/>   
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

  function closeEditor()
  {
    echo '</table></form>';
  }

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

<a href="http://apps.facebook.com/notesharesep/view/UserHomePage.php" target="_top" id="mainPage">Main Page</a>
&gt;
<a href="http://apps.facebook.com/notesharesep/view/AddCourse.php" target="_top">
   Add Course</a>
<br>
<br>
<br>

<?php
  $XML = getUniversityDAL( );
  $universities = XSLTransform( $XML, 'view/AddCourse.View.xsl' );
  $universities = '<option value="-1" class="empty"></option>
                   <option value="0" class="new">Add University...</option>'
                  . $universities;
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

