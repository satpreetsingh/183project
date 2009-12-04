  var AJAX_URL_BASE = '/controllers/AddCourse.php';
var ns_backup = new Object();
var ns_discription = false;

  function resetBackground( obj )
  {
    obj.style.background = "#3b5998";
  }
  
function showRevert(show)
{
	if (show == null)
		show = true;
		
	revertBut = document.getElementById("ns_revert");
	
	if( show )
		revertBut.style.visibility = "visible"
	else
		revertBut.style.visibility = "hidden"
}
function textifyComboBox( containerName )
{
	if(!(containerName in ns_backup) )
	{
		var cmbCont = document.getElementById(containerName+ "_container");
		ns_backup[containerName] = cmbCont.cloneNode(true);
		
		newElem = document.createElement("input");
		newElem.setAttribute("type","text");
		newElem.setAttribute("class","addText");
		newElem.setAttribute("name",containerName + "_add");
		newElem.setAttribute("id",containerName + "_add");
		newElem.setAttribute("value","");
		
		cmbCont.replaceChild(newElem,document.getElementById(containerName));
	}
}

function restoreComboBox( containerName, clear)
{
	if (clear == null) clear = false;
	
	if(containerName in ns_backup)
	{
		var cmbCont = document.getElementById(containerName+ "_container");
		cmbCont.parentNode.replaceChild(ns_backup[containerName], cmbCont);
		if(clear)
			document.getElementById(containerName).innerHTML = "";
		delete ns_backup[containerName];
	}
}

function restoreAll(clear)
{
	if(clear == null) clear = false;
	
	restoreComboBox("ns_university", false);
	restoreComboBox("ns_department", clear);
	restoreComboBox("ns_course", clear);
	restoreComboBox("ns_session", clear);
	removeDescriptionToTable();
	showRevert(false);
	
	return false;
}

function addDescriptionToTable()
{
	if(!ns_discription)
	{
		var nsTable = document.getElementById("ns_table").children[0];
		var buttonRow = document.getElementById("ns_button_row");
	
		var newRow = document.createElement("tr");
		newRow.setAttribute("class","combo");
		newRow.setAttribute("id","ns_desc_row");
	
		var header = document.createElement("th");
		header.setAttribute("class","combo");
		header.innerHTML = '<label class="fbFont large">Description : </label>'
		newRow.appendChild(header);
	
		var data = document.createElement("td");
		data.setAttribute("class","combo");
		data.setAttribute("id","ns_desc_container");
		data.innerHTML = '<textarea id="ns_desc_add" name="ns_desc_add" class="addText" rows="3"></textarea>'
		newRow.appendChild(data);
	 
		 nsTable.removeChild(buttonRow);
		 nsTable.appendChild(newRow);
		 nsTable.appendChild(buttonRow);
		 
		 ns_discription = true;
	}
}

function removeDescriptionToTable()
{
	if(ns_discription)
	{
		var nsTable = document.getElementById("ns_table").children[0];
		var descRow = document.getElementById("ns_desc_row");
	 
		 nsTable.removeChild(descRow);
			 
		 ns_discription = false;
	}
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
        textifyComboBox("ns_university");
		textifyComboBox("ns_department");
		textifyComboBox("ns_course");
		textifyComboBox("ns_session");
		addDescriptionToTable();
		showRevert();
		
		return;
    }
    else
    {
    	
      restoreAll()
    
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
          document.getElementById( 'debug' ).innerHTML = "Shoot: " + xmlhttp.responseText;
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
      	textifyComboBox("ns_department");
		textifyComboBox("ns_course");
		textifyComboBox("ns_session");
		addDescriptionToTable();
		showRevert();
		
		return;
    }
    else
    {
    
		restoreAll(true)
		
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
      	textifyComboBox("ns_course");
		textifyComboBox("ns_session");
		addDescriptionToTable();
		showRevert();
		
		return;
    }
    else
    {

		restoreAll(true)
		
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
  
function changedSession()
{
	var cmbSession = document.getElementById( 'ns_session' );
    var selSession = cmbSession.selectedIndex;
    var sessionID = cmbSession.options[ selSession ].value;
    // If add course was selected
	if( sessionID == 0 )
	{	
		textifyComboBox("ns_session");
		showRevert();
		//addDescriptionToTable();
	}
}

function validate()
{
	fields = new Array();
	fields[0] = document.getElementById("ns_university_add");
	fields[1] = document.getElementById("ns_department_add");
	fields[2] = document.getElementById("ns_course_add");
	fields[3] = document.getElementById("ns_session_add");
	
	for(field in fields)
	{
		if( fields[field] != null && fields[field].value == "" )
		{	
			alert("All fields must be full!");
			fields[field].focus();
			return false;
		}
	}
	
	return true;
}
