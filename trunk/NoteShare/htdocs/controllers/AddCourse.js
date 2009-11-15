  var AJAX_URL_BASE = '/controllers/AddCourse.php';

  function resetBackground( obj )
  {
    obj.style.background = "#3b5998";
  }

  function getDepartments()
  {
    var cmbUni = document.getElementById( 'ns_university' );
    var selUni = cmbUni.selectedIndex;
    var universityID = cmbUni.options[ selUni ].value;

    // user selected blank option
    if( universityID == -1 )
    {
          document.getElementById( 'ns_department' ).innerHTML = "";
          document.getElementById( 'ns_course' ).innerHTML = "";
          document.getElementById( 'ns_session' ).innerHTML = "";
          document.getElementById( 'ns_university' ).style.background = "#FF0000";
    }
    // user selected add a university
    else if( universityID == 0 )
    {
          document.getElementById( 'ns_department' ).innerHTML = "";
          document.getElementById( 'ns_course' ).innerHTML = "";
          document.getElementById( 'ns_session' ).innerHTML = "";
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
          document.getElementById( 'ns_department' ).innerHTML = xmlhttp.responseText;
          document.getElementById( 'ns_department' ).style.background = "#eceff6";
          document.getElementById( 'ns_course' ).innerHTML = "";
          document.getElementById( 'ns_session' ).innerHTML = "";
          document.getElementById( 'debug' ).innerHTML = xmlhttp.responseText;
        }
      }
      xmlhttp.open( "GET", AJAX_URL_BASE + "?function_name=getDepartments&ns_universityID="+universityID);
      xmlhttp.send( null );
    }
  }

 function getCourses()
  {
    var cmbDept = document.getElementById( 'ns_department' );
    var selDept = cmbDept.selectedIndex;
    var deptID = cmbDept.options[ selDept ].value;

    // user selected blank option
    if( deptID == -1 )
    {
      document.getElementById( 'ns_course' ).innerHTML = "";
      document.getElementById( 'ns_session' ).innerHTML = "";
      document.getElementById( 'ns_department' ).style.background = "#FF0000";
    }
    // user selected add course
    else if( deptID == 0 )
    {
      document.getElementById( 'ns_course' ).innerHTML = "";
      document.getElementById( 'ns_session' ).innerHTML = "";
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
          document.getElementById( 'ns_course' ).innerHTML = xmlhttp.responseText;
          document.getElementById( 'ns_course' ).style.background = "#eceff6";
          document.getElementById( 'ns_session' ).innerHTML = "";
        }
      }
      xmlhttp.open( "GET", AJAX_URL_BASE + "?function_name=getCourses&ns_departmentID="+deptID );
      xmlhttp.send( null );
    }
  }

 function getSessions()
  {
    var cmbCourse = document.getElementById( 'ns_course' );
    var selCourse = cmbCourse.selectedIndex;
    var courseID = cmbCourse.options[ selCourse ].value;

    // user selected blank
    if( courseID == -1 )
    {
      document.getElementById( 'ns_session' ).innerHTML = "";
      document.getElementById( 'ns_course' ).style.background = "#FF0000";
    }
    else if( courseID == 0 )
    {
      document.getElementById( 'ns_session' ).innerHTML = "";
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
          document.getElementById( 'ns_session' ).innerHTML = xmlhttp.responseText;
          document.getElementById( 'ns_session' ).style.background = "#eceff6";
        }
      }
      xmlhttp.open( "GET", AJAX_URL_BASE + "?function_name=getSessions&ns_courseID="+courseID );
      xmlhttp.send( null );
    }
  }
