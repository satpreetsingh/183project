<?php

  function openDB()
  {
    // Set the MySQL information.
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = 'b4n4n4s';
    $dbname = 'NoteShareSEP';

    // Connect to the MySQL service.
    $conn = mysql_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql' . mysql_error());

    // Select the database NoteShareSEP.
    mysql_select_db ($dbname);

    return $conn;
  }

  function closeDB($result, $conn)
  {
    // Free the result memory.
    mysql_free_result($result);

    // Close the database connection.
    mysql_close($conn);
  }

  function getUniversityDAL()
  {
    $conn = openDB();

    $query = "SELECT * FROM University";
    $result = mysql_query($query);


    $doc = new DOMDocument('1.0');

    $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
    $doc->appendChild($style);
    $list = $doc->createElement('universityList');
    $doc->appendChild($list);

    while($row = mysql_fetch_assoc($result))
    {
      $university = $doc->createElement('University');
      $list->appendChild($university);

      $id_attr = $doc->createAttribute('Id');
      $university->appendChild($id_attr);

      $id_text = $doc->createTextNode($row['ID']);
      $id_attr->appendChild($id_text);

      $univ_name = $doc->createTextNode($row['NAME']);
      $university->appendChild($univ_name);
    }

    $out = $doc->saveXML();

    closeDB($result, $conn);

    return $out;
  }

  function getDepartmentsDAL($univ_id)
  {
    $conn = openDB();

    $query = "SELECT * FROM Department WHERE UNIVERSITY_PTR = ".$univ_id;
    $result = mysql_query($query);

    $doc = new DOMDocument('1.0');

    $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
    $doc->appendChild($style);
    $list = $doc->createElement('deptList');
    $doc->appendChild($list);

    while($row = mysql_fetch_assoc($result))
    {
      $dept = $doc->createElement('Dept');
      $list->appendChild($dept);

      $id_attr = $doc->createAttribute('Id');
      $dept->appendChild($id_attr);

      $id_text = $doc->createTextNode($row['ID']);
      $id_attr->appendChild($id_text);

      $dept_name = $doc->createTextNode($row['NAME']);
      $dept->appendChild($dept_name);
    }

    $out = $doc->saveXML();

    closeDB($result, $conn);

    return $out;
  }

  function getCoursesDAL($dept_id)
  {
    $conn = openDB();

    $query = "SELECT * FROM Course WHERE DEPARTMENT_PTR = ".$dept_id;
    $result = mysql_query($query);

    $doc = new DOMDocument('1.0');

    $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
    $doc->appendChild($style);
    $list = $doc->createElement('courseList');
    $doc->appendChild($list);

    while($row = mysql_fetch_assoc($result))
    {
      $course = $doc->createElement('Course');
      $list->appendChild($course);
	
      $id_attr = $doc->createAttribute('Id');
      $course->appendChild($id_attr);
	
      $id_text = $doc->createTextNode($row['ID']);
      $id_attr->appendChild($id_text);
	
      $course_name = $doc->createTextNode($row['NAME']);
      $course->appendChild($course_name);
    }

    $out = $doc->saveXML();

    closeDB($result, $conn);

    return $out;
  }

  function getSessionsDAL($course_id)
  {
    $conn = openDB();

    $query = "SELECT * FROM Session WHERE COURSE_PTR = ".$course_id;
    $result = mysql_query($query);

    $doc = new DOMDocument('1.0');

    $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
    $doc->appendChild($style);
    $list = $doc->createElement('sessionList');
    $doc->appendChild($list);

    while($row = mysql_fetch_assoc($result))
    {
      $session = $doc->createElement('Session');
      $list->appendChild($session);
	
      $id_attr = $doc->createAttribute('Id');
      $session->appendChild($id_attr);
	
      $id_text = $doc->createTextNode($row['ID']);
      $id_attr->appendChild($id_text);
	
      $session_name = $doc->createTextNode($row['NAME']);
      $session->appendChild($session_name);
    }

    $out = $doc->saveXML();

    closeDB($result, $conn);

    return $out;
  }




  function getHomePageSessionListDAL($user_id)
  {
    
    $conn = openDB();

    // Select all the courses which the given user is currently enrolled in.
    $query = "Select Session.Id   As Session_Id, " .
		      "Session.Name As Session_Name, " .
                    "Course.Name  As Course_Name " .  

	      "From SessionEnrollment " . 

	      "Inner Join Session On Session.Id = SessionEnrollment.Session_Ptr " . 
	      "Inner Join Course On Course.Id = Session.Course_Ptr " .
	      "Where (SessionEnrollment.User_Ptr = " . $user_id . ") And " .
		     "(SessionEnrollment.Left_Date Is Null)";	


    $result = mysql_query($query);



    $doc = new DOMDocument('1.0');

    $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
    $doc->appendChild($style);
    $list = $doc->createElement('UserSessionList');
    $doc->appendChild($list);



    while($row = mysql_fetch_assoc($result))
    {
      $sessionuseritem = $doc->createElement('SessionUserItem');
      $doc->appendChild($sessionuseritem);
	
      $id_attr = $doc->createAttribute('Id');
      $sessionuseritem->appendChild($id_attr);
	
      $id_text = $doc->createTextNode($row['Session_Id']);
      $id_attr->appendChild($id_text);
	
      $SessionItem_Name = $doc->createTextNode($row['Course_Name'] . " - " . $row['Session_Name']);
      $sessionuseritem->appendChild($SessionItem_Name);
    }

    $out = $doc->saveXML();

    closeDB($result, $conn);

    return $out;
  }
  



  function getSessionMetadataDAL($session_id)
  {
    
    $conn = openDB();

    // For a session, return all the details of the session (specific term/semester course information) 
    // and course (general course information).
    $query = "Select Session.Id          As Session_Id,        " .
		      "Session.Name        As Session_Name,      " .
                    "Session.Start_Date  As Start_Date,        " .
   		      "Session.End_Date    As End_Date,          " .
                    "Course.Name         As Course_Name,       " .  
		      "Course.Description  As Course_Description " .

	      "From Session " . 
	      "Inner Join Course On Course.Id = Session.Course_Ptr " . 

	      "Where Session.Id = " . $session_id;	


    $result = mysql_query($query);



    $doc = new DOMDocument('1.0');

    $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
    $doc->appendChild($style);
    $EndResult = $doc->createElement('SessionMetaDataResult');
    $doc->appendChild($EndResult);



    while($row = mysql_fetch_assoc($result))
    {
      $sessionmetadata= $doc->createElement('SessionMetaData');
      $doc->appendChild($sessionmetadata);
	
      $id_attr = $doc->createAttribute('Id');
      $sessionmetadata->appendChild($id_attr);
	
      $id_text = $doc->createTextNode($row['Session_Id']);
      $id_attr->appendChild($id_text);


      $startdate_attr = $doc->createAttribute('Start_Date');
      $sessionmetadata->appendChild($startdate_attr);
	
      $startdate_text = $doc->createTextNode($row['Start_Date']);
      $startdate_attr->appendChild($startdate_text);


      $enddate_attr = $doc->createAttribute('End_Date');
      $sessionmetadata->appendChild($enddate_attr);
	
      $enddate_text = $doc->createTextNode($row['End_Date']);
      $enddate_attr->appendChild($enddate_text);


      $desc_attr = $doc->createAttribute('Desc');
      $sessionmetadata->appendChild($desc_attr);
	
      $desc_text = $doc->createTextNode($row['Course_Description']);
      $desc_attr->appendChild($desc_text);	


      $SessionMetaData_Name = $doc->createTextNode($row['Course_Name'] . " - " . $row['Session_Name']);
      $sessionmetadata->appendChild($SessionMetaData_Name);

    }

    $out = $doc->saveXML();

    closeDB($result, $conn);

    return $out;
  }
 

  function getSessionMembersDAL($session_id)
  {
    
    $conn = openDB();

    // Select ALL the users from a given course's session.
    $query = "Select Session.Id                 As Session_Id,  " .
                    "SessionEnrollment.User_Ptr As User_Ptr     " .

	      "From Session " . 
	      "Inner Join SessionEnrollment On SessionEnrollment.Id = Session.Course_Ptr " . 

	      "Where (Session.Id = " . $session_id . ");";	


    $result = mysql_query($query);



    $doc = new DOMDocument('1.0');

    $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
    $doc->appendChild($style);
    $list = $doc->createElement('SessionUserList');
    $doc->appendChild($list);



    while($row = mysql_fetch_assoc($result))
    {
      $SessionUserItem= $doc->createElement('SessionUserItem');
      $doc->appendChild($SessionUserItem);
	
      $id_attr = $doc->createAttribute('Id');
      $SessionUserItem->appendChild($id_attr);
	
      $id_text = $doc->createTextNode($row['Session_Id']);
      $id_attr->appendChild($id_text);

      $SessionUserItem_Name = $doc->createTextNode($row['User_Ptr']);
      $SessionUserItem->appendChild($SessionUserItem_Name);
    }

    $out = $doc->saveXML();

    closeDB($result, $conn);

    return $out;
  }


  function addUserSessionDAL($user_id, $session_id)
  {
    
    $conn = openDB();

    // Remove the user from a given course's session.
    $query = "Insert SessionEnrollment (SessionEnrollment.User_Ptr, " . 
					    "SessionEnrollment.Session_Ptr, " .
 					    "SessionEnrollment.Join_Date " .
					    ") Values (" . $user_id    . ", " .
						            $session_id . ", " .
							 "'" . date("Y-m-d") . "'); ";	


    $result = mysql_query($query);



    $doc = new DOMDocument('1.0');

    $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
    $doc->appendChild($style);
    $EndResult = $doc->createElement('AddUserSessionResult');
    $doc->appendChild($EndResult);


    $AddUserSessionResult= $doc->createElement('AddUserSessionResult');
    $doc->appendChild($AddUserSessionResult);
	
    $AddUserSession_Name = $doc->createTextNode($result);
    $AddUserSessionResult->appendChild($AddUserSession_Name);
    

    $out = $doc->saveXML();

    return $out;
  }




  function removeUserSessionDAL($user_id, $session_id)
  {
    
    $conn = openDB();

    // Remove the user from a given course's session.
    $query = "Update SessionEnrollment Set " . 
		      "SessionEnrollment.Left_Date = '" . date("Y-m-d") . "' " .

	      "Where (SessionEnrollment.User_Ptr    = " . $user_id    . ") And " . 
		     "(SessionEnrollment.Session_Ptr = " . $session_id . ") And " .
		     "(SessionEnrollment.Left_Date Is Null);";	


    $result = mysql_query($query);



    $doc = new DOMDocument('1.0');

    $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
    $doc->appendChild($style);
    $EndResult = $doc->createElement('RemoveUserSessionResult');
    $doc->appendChild($EndResult);


    $RemoveUserSessionResult= $doc->createElement('RemoveUserSessionResult');
    $doc->appendChild($RemoveUserSessionResult);
	
    $RemoveUserSession_Name = $doc->createTextNode($result);
    $RemoveUserSessionResult->appendChild($RemoveUserSession_Name);
    

    $out = $doc->saveXML();

    return $out;
  }
?>
