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

<script>
<!--
  function addControllers()
  {
    var obj = document.getElementById( 'university' );
    obj.addEventListener( 'onchange', function(e){
      eventUniversity();
      e.stopPropagation();
      e.preventDefault();
      return false;
    }, false);
  }

  function eventUniversity()
  {
    document.getElementById( 'debugOutput' ).setTextValue( "active" );
  }
//-->
</script>


