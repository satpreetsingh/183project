  var AJAX_URL_BASE = '/controllers/AddCourse.php';

  function resetBackground( obj )
  {
    obj.style.background = "#3b5998";
  }

  function getDepartments()
  {
    var cmbUni = document.getElementById( 'university' );
    var selUni = cmbUni.selectedIndex;
    var universityID = cmbUni.options[ selUni ].value;

    // user selected blank option
    if( universityID == -1 )
    {
          document.getElementById( 'department' ).innerHTML = "";
          document.getElementById( 'course' ).innerHTML = "";
          document.getElementById( 'session' ).innerHTML = "";
          document.getElementById( 'university' ).style.background = "#FF0000";
    }
    // user selected add a university
    else if( universityID == 0 )
    {
          document.getElementById( 'department' ).innerHTML = "";
          document.getElementById( 'course' ).innerHTML = "";
          document.getElementById( 'session' ).innerHTML = "";
    }
    else
    {
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

      xmlhttp.onreadystatechange=function()
      {
        if( xmlhttp.readyState == 4 )
        {
          document.getElementById( 'department' ).innerHTML = xmlhttp.responseText;
          document.getElementById( 'department' ).style.background = "#eceff6";
          document.getElementById( 'course' ).innerHTML = "";
          document.getElementById( 'session' ).innerHTML = "";
          document.getElementById( 'debug' ).innerHTML = xmlhttp.responseText;
        }
      }
      xmlhttp.open( "GET", AJAX_URL_BASE + "?function_name=getDepartments&universityID="+universityID);
      xmlhttp.send( null );
    }
  }

 function getCourses()
  {
    var cmbDept = document.getElementById( 'department' );
    var selDept = cmbDept.selectedIndex;
    var deptID = cmbDept.options[ selDept ].value;

    // user selected blank option
    if( deptID == -1 )
    {
      document.getElementById( 'course' ).innerHTML = "";
      document.getElementById( 'session' ).innerHTML = "";
      document.getElementById( 'department' ).style.background = "#FF0000";
    }
    // user selected add course
    else if( deptID == 0 )
    {
      document.getElementById( 'course' ).innerHTML = "";
      document.getElementById( 'session' ).innerHTML = "";
    }
    else
    {
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

      xmlhttp.onreadystatechange=function()
      {
        if( xmlhttp.readyState == 4 )
        {
          document.getElementById( 'course' ).innerHTML = xmlhttp.responseText;
          document.getElementById( 'course' ).style.background = "#eceff6";
          document.getElementById( 'session' ).innerHTML = "";
        }
      }
      xmlhttp.open( "GET", AJAX_URL_BASE + "?function_name=getCourses&departmentID="+deptID );
      xmlhttp.send( null );
    }
  }

 function getSessions()
  {
    var cmbCourse = document.getElementById( 'course' );
    var selCourse = cmbCourse.selectedIndex;
    var courseID = cmbCourse.options[ selCourse ].value;

    // user selected blank
    if( courseID == -1 )
    {
      document.getElementById( 'session' ).innerHTML = "";
      document.getElementById( 'course' ).style.background = "#FF0000";
    }
    else if( courseID == 0 )
    {
      document.getElementById( 'session' ).innerHTML = "";
    }
    else
    {
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
  
      xmlhttp.onreadystatechange=function()
      {
        if( xmlhttp.readyState == 4 )
        {
          document.getElementById( 'session' ).innerHTML = xmlhttp.responseText;
          document.getElementById( 'session' ).style.background = "#eceff6";
        }
      }
      xmlhttp.open( "GET", AJAX_URL_BASE + "?function_name=getSessions&courseID="+courseID );
      xmlhttp.send( null );
    }
  }
