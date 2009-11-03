<?php
/*-----------------------------------------------------------------------------
 File:         addcourseController.php
 Description:  An example of how the addcourse controller might work.
 UseCases:     <Later>
 Requirements: <Later>
 Components:   <Later>
-------------------------------------------------------------------------------
 Modified On:   10/25/09
 Notes:         Beginning implementations of javascript responses to user
                interactions. AJAX querrying seems to be working well.

 Modified On:   10/20/09
 Notes:         Quick initial creation of addcourse controller.
-----------------------------------------------------------------------------*/

// AJAX



// END AJAX

function getDepartmentsXML( $univName )
{
	return "
	<?xml version=\"1.0\"?>
    <deptList>
    <dept id=\"0001\">Computer Science</dept>
    <dept id=\"0002\">Electrical & Computer Engineering</dept>
    <dept id=\"0003\">Mechanical Engineering (we make weapons)</dept>
    <dept id=\"0004\">Civil Engineering (we make targets)</dept>
    <dept id=\"0005\">Philosophy</dept>
    <dept id=\"0006\">Music</dept>
    <dept id=\"0007\">Spanish (No por que!)</dept>
    </deptList>
    ";
}

function getCoursesXML( $deptName )
{
	return "
	<?xml version=\"1.0\"?>
    <courseList>
    <course id=\"0001\">Operating Systems </course>
    <course id=\"0002\">Defunct Systems </course>
    <course id=\"0003\">Distributed Systems </course>
    <course id=\"0004\">Concentrated Systems </course>
    <course id=\"0005\">Ad-hoc Systems </course>
    <course id=\"0006\">Deliberate(d) Systems </course>
    </courseList>
    ";
}

function getSessionsXML( $courseName )
{
	return "
	<?xml version=\"1.0\"?>
    <sessionList>
    <session id=\"0001\">(Fall 2009)</session>
    <session id=\"0002\">(Spring 2009)</session>
    <session id=\"0003\">(Summer 2009)</session>
    <session id=\"0004\">(Fall 2008)</session>
    <session id=\"0005\">(Spring 2008)</session>
    <session id=\"0006\">(Summer 2008)</session>
    </sessionList>
    ";
}

?>

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
        document.getElementById( 'department' ).innerHTML = xmlhttp.responseTex$
      }
    }
    xmlhttp.open( "GET", "./view/ajaxView.php?function_name=getDepartments&univ$
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
    xmlhttp.open( "GET", "./view/ajaxView.php?function_name=getCourses&departme$
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
    xmlhttp.open( "GET", "./view/ajaxView.php?function_name=getSessions&courseI$
    xmlhttp.send( null );
  }

</script>
