<?php
  include "./controllers/Session.Controller.php";
  include "./controllers/AddCourse.Controller.php";
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
    echo '' .
    '   <tr>
          <th class="detached_label">
            <label>' . $label . ' : </label>
          </th>
          <td class="editorkit_row">
            <select id="' . $name . '" name="' . $name . '" onchange="'.$action.'()">';
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
<head>
<script src="http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>
<script type="text/javascript">
   FB.init("20f5b69813b87ffd25e42744b326a112", "/xd_receiver.htm");
</script>

<!----- Controller portion ------->
<script type="text/javascript">
  function getDepartments()
  {
    var cmbUni = document.getElementById( 'university' );
    var selUni = cmbUni.selectedIndex;
    var universityID = cmbUni.options[ selUni ].value;
    //document.getElementById( 'testBox' ).innerHTML = universityID;

    var xmlhttp;
    if( window.XMLHttpRequest)
    {
      xmlhttp = new XMLHttpRequest();
    }
    else if ( window.ActiveXObject )
    {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    else
    {
      alert( "XMLHTTP!?" );
    }

    if( xmlhttp == null )
      document.getElementById( 'testBox' ).innerHTML = "NULL!";

    xmlhttp.onreadystatechange=function()
    {
      if( xmlhttp.readyState == 4 )
      {
        document.getElementById( 'department' ).innerHTML = xmlhttp.responseText;
      }
    }
    xmlhttp.open( "GET", "./view/ajaxView.php?function_name=getDepartments&universityID="+universityID, true );
    xmlhttp.send( null );
  }

  function getCourses()
  {
    var cmbDept = document.getElementById( 'department' );
    var selDept = cmbDept.selectedIndex;
    var deptID = cmbDept.options[ selDept ].value;
    //document.getElementById( 'testBox' ).innerHTML = universityID;

    var xmlhttp;
    if( window.XMLHttpRequest)
    {
      xmlhttp = new XMLHttpRequest();
    }
    else if ( window.ActiveXObject )
    {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    else
    {
      alert( "XMLHTTP!?" );
    }

    if( xmlhttp == null )
      document.getElementById( 'testBox' ).innerHTML = "NULL!";

    xmlhttp.onreadystatechange=function()
    {
      if( xmlhttp.readyState == 4 )
      {
        document.getElementById( 'course' ).innerHTML = xmlhttp.responseText;
      }
    }
    xmlhttp.open( "GET", "./view/ajaxView.php?function_name=getCourses&departmentID="+deptID, true );
    xmlhttp.send( null );
  }


  function getSessions()
  {
    var cmbCourse = document.getElementById( 'course' );
    var selCourse = cmbCourse.selectedIndex;
    var sessionID = cmbCourse.options[ selCourse ].value;
    //document.getElementById( 'testBox' ).innerHTML = universityID;

    var xmlhttp;
    if( window.XMLHttpRequest)
    {
      xmlhttp = new XMLHttpRequest();
    }
    else if ( window.ActiveXObject )
    {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    else
    {
      alert( "XMLHTTP!?" );
    }

    if( xmlhttp == null )
      document.getElementById( 'testBox' ).innerHTML = "NULL!";

    xmlhttp.onreadystatechange=function()
    {
      if( xmlhttp.readyState == 4 )
      {
        document.getElementById( 'session' ).innerHTML = xmlhttp.responseText;
      }
    }
    xmlhttp.open( "GET", "./view/ajaxView.php?function_name=getSessions&courseID="+courseID, true );
    xmlhttp.send( null );
  }

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
