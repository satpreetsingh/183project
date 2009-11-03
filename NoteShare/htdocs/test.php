<head>
<?php
  include "./controllers/Session.Controller.php";
  //include "./controllers/AddCourse.Controller.php";
  include "./model/NoteshareDatabase.php";
  include "./view/xsltView.php";

  function openEditor( $action )
  {
    echo '' .
    '<form action="' . $action . '" method="post">
       <table class="formTable">';
  }

  function genCmbBox( $label, $name, $action, $optionString )
  {
//            <select id="' . $name . '" name="' . $name . '" onchange="'.$action.'( this )">';
    echo '' .
    '   <tr>
          <th class="detached_label">
            <label>' . $label . ' : </label>
          </th>
          <td class="editorkit_row">
            <select id="' . $name . '" name="' . $name . '" onchange="'.$action.'(); return false;">';
    if( $optionString != null )
    {
      echo $optionString;
      echo '<option value="-1"></option>';
    }
    echo ''.
    '       </select>
          </td>
          <td class="right_padding">
          </td>
        </tr>';
  }

  function closeEditor()
  {
    echo '</table></form>';
  }
?>

<!-----------------------------Actual Content---------------------------------->
<script src="http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript">
</script>
<script src="./controllers/AddCourse.Controller.js" type="text/javascript">
</script>

<script type="text/javascript">
   FB.init("20f5b69813b87ffd25e42744b326a112", "/xd_receiver.htm");
</script>

</head>

<body onload='FB.CanvasClient.scrollTo(0,0);'>
<a href="index.php" id="mainPage">Main Page</a> > <a href="test.php">Here</a>
<br>
<br>
<br>
<div id="testBox" style="border: 4px dashed black;"><br></div>

<?php
  $XML = getUniversityDAL( );
  $universities = XSLTransform( $XML, './view/AddCourse.View.xsl' );
  echo openEditor( "?notSureYet" );
  echo genCmbBox( "University", university, getDepartments, $universities );
  echo genCmbBox( "Department", department, getCourses, null);
  echo genCmbBox( "Course", course, getSessions, null);
  echo genCmbBox( "Session", session, "", null);
  echo closeEditor();
?>

</body>
