  function getDepartments()
  {
    var cmbUni = document.getElementById( 'university' );
    var selUni = cmbUni.selectedIndex;
    var universityID = cmbUni.options[ selUni ].value;
    document.getElementById( 'testBox' ).innerHTML = universityID;
  
    if( universityID == 0 )
    {
    }
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
    xmlhttp.open( "GET", "/view/ajaxView.php?function_name=getDepartments&universityID="+universityID);
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
    xmlhttp.open( "GET", "/view/ajaxView.php?function_name=getCourses&departmentID="+deptID );
    xmlhttp.send( null );
  }

 function getSessions()
  {
    var cmbCourse = document.getElementById( 'course' );
    var selCourse = cmbCourse.selectedIndex;
    var courseID = cmbCourse.options[ selCourse ].value;
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
    xmlhttp.open( "GET", "/view/ajaxView.php?function_name=getSessions&courseID="+courseID );
    xmlhttp.send( null );
  }

