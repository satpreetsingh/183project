<?php

/**
 * NoteshareDatabase.php
 *
 * This file contains all of the functions to interact with our own SQL database.
 * The results from these functions are strings of XML data to be interpeted in the view.
 *
 */
 

/**
 * This function opens the database connection
 * @author(s) Joseph Trapani/Nathan Denklau
 * @version 1.0
 * @return string connection
 */
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
  

/**
 * This function closes the database connection
 * @author(s) Joseph Trapani/Nathan Denklau
 * @version 1.0
 * @param string $result results from SQL querey to release
 * @param string $conn connection to close
 */
  function closeDB($result, $conn)
  {
    // Free the result memory.
//$answer=mysql_free_result($result) or die("Error: ". mysql_error(). " with ". $result);
//Echo $answer;
    mysql_free_result($result);

    // Close the database connection.
    mysql_close($conn);
  }
  

/**
 * This function gets all of the universities within the database and returns them in XML
 * @author Nathan Denklau
 * @version 1.0
 * @return XML university data
 */
  function getUniversityDAL()
  {
    $conn = openDB();

    $query = "Select * From University";
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

  
/**
 * This function gets all the departments at a given university
 * @author Nathan Denklau
 * @version 1.0
 * @param integer $univ_id university ID number
 * @return XML department data
 */
  function getDepartmentsDAL($univ_id)
  {
    $conn = openDB();

    $query = "Select * From Department Where University_Ptr = ".$univ_id;
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


/**
 * This function gets all the courses in a given department
 * @author Nathan Denklau
 * @version 1.0
 * @param integer $dept_id department ID number
 * @return XML course data
 */
  function getCoursesDAL($dept_id)
  {
    $conn = openDB();

    $query = "Select * From Course Where Department_Ptr = ".$dept_id;
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


/**
 * This function gets all the sessions for a given course
 * @author Nathan Denklau
 * @version 1.0
 * @param integer $course_id course ID number
 * @return XML session data
 */  
  function getSessionsDAL($course_id)
  {
    $conn = openDB();

    $query = "Select * From Session Where COURSE_PTR = ".$course_id;
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


/**
 * This function gets all the courses which the given user is currently enrolled in.
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $user_id user ID number
 * @return XML user's current enrolled session list
 */ 
  function getHomePageSessionListDAL($user_id)
  {
    
    $conn = openDB();

    $query = "Select Session.Id   As Session_Id, " .
		      "Session.Name As Session_Name, " .
                    "Course.Name  As Course_Name " .
                    "University.Name As Uni_Name" .  

	      "From SessionEnrollment " . 

	      "Inner Join Session On Session.Id = SessionEnrollment.Session_Ptr " . 
	      "Inner Join Course On Course.Id = Session.Course_Ptr " 
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
      $list->appendChild($sessionuseritem);
	
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
  

/**
 * This function returns all the details of the session (specific term/semester course information) 
 * and course (general course information).
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $session_id session ID number
 * @return XML session meta data
 */ 
  function getSessionMetadataDAL($session_id)
  {
    
    $conn = openDB();

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
      $EndResult->appendChild($sessionmetadata);
	
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
 

/**
 * This function gets all the users from a given course's session.
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $session_id sessionID number
 * @return XML user's current enrolled session list
 */
  function getSessionMembersDAL($session_id)
  {
    
    $conn = openDB();

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


/**
 * This function returns the result of adding a user to a session's enrollment.   
 * @author Joseph Trapani
 * @version 2.0
 * @param integer $user_id user ID number, integer $session_id session ID number
 * @return boolean result
 */ 
function checkUserInSessionDAL($user_id, $session_id)
  {  

     $conn1 = openDB();
   
     // Check if the user is already actively enrolled in the session.	
     $query1 = "Select SessionEnrollment.ID " . 
	        "From SessionEnrollment " .

	        "Where (SessionEnrollment.User_Ptr    = " . $user_id    . ") And " . 
	   	       "(SessionEnrollment.Session_Ptr = " . $session_id . ") And " .
		       "(SessionEnrollment.Left_Date Is Null);";	


     $result1 = mysql_query($query1);
	
     // User is actively enrolled in the session, return 1.
     if (mysql_num_rows($result1) > 0) {
       return 1;
     }
     else {
       return 0;
     }	
	 
	 closeDB($result1, $conn1);

		  
  }


/**
 * This function returns the result of adding a user to a user table.   
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $user_id user ID number
 * @return boolean result if user exists or has been added.
 */ 
  function checkFirstUserInteractionDAL($user_id)
  {
    
    $conn1 = openDB();


    // Check if the user is already an active member of the User table.	
    $query1  = "Select User.User_ID " . 
	        "From User " .
	        "Where (User.User_ID = " . $user_id . ") And " . 
	              "(User.Left_Date Is Null);";	


    $result1 = mysql_query($query1);
   

    // User doesn't exist as an active NoteShareSEP user.  
    if (mysql_num_rows($result1) <= 0) {
      
      closeDB($result1, $conn1);
      
      $conn = openDB();
 
      // Add the new user ID to the user table.
      $query = "Insert Into User (User_ID, " . 
		    	             "Join_Date" .
		                    ") Values (" . $user_id      . ", " .
  				                 "'" . date("Y-m-d") . "'); ";	


      $result = mysql_query($query);
      
      return 1;

    }

    else {
      closeDB($result1, $conn1); 
      return 1;
    }

  }


/**
 * This function returns the result of adding a user to a session's enrollment.   
 * @author Joseph Trapani
 * @version 2.0
 * @param integer $user_id user ID number, integer $session_id session ID number
 * @return XML adding of user sessionenrollment data
 */ 
  function addUserSessionDAL($user_id, $session_id)
  {
    // Check if the user is already an active member of the User table.	
    checkFirstUserInteractionDAL($user_id);


    // If the user is NOT actively enrolled in the session, so insert a new row.
    if (checkUserInSessionDAL($user_id, $session_id) == 0) {
	
      $conn = openDB();

      // Add the user from a given course's session.
      $query = "Insert Into SessionEnrollment (SessionEnrollment.User_Ptr, ." . 
		                                "SessionEnrollment.Session_Ptr, " .
 			                         "SessionEnrollment.Join_Date" .
			   ") Values ("   . $user_id      . ", " . 
			                    $session_id   . ", " .
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
    }

    // The user was already a member of the session.  
    else {

      $doc = new DOMDocument('1.0');

      $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
      $doc->appendChild($style);
      $EndResult = $doc->createElement('AddUserSessionResult');
      $doc->appendChild($EndResult);


      $AddUserSessionResult= $doc->createElement('AddUserSessionResult');
      $doc->appendChild($AddUserSessionResult);
	
      $AddUserSession_Name = $doc->createTextNode(1);
      $AddUserSessionResult->appendChild($AddUserSession_Name);
    

      $out = $doc->saveXML();
    }

    return $out;


  }


/**
 * This function returns the result after removing a user from a given course's session.
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $user_id user ID number, integer $session_id session ID number
 * @return XML removal of user sessionenrollment data
 */   
  function removeUserSessionDAL($user_id, $session_id)
  {
    
    $conn = openDB();
 
    $query = "Update SessionEnrollment Set " . 
		      "SessionEnrollment.Left_Date = '" . date("Y-m-d") . "' " .

	      "Where (SessionEnrollment.User_Ptr = " . $user_id    . ") And " . 
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

    closeDB($result, $conn);

    return $out;
  }


/**
 * This function adds a user to the application.
 * @author Jon Hall/Joseph Trapani
 * @version 2.0
 * @param integer $user_id userID number
 */
	function addUserDAL($user_id)
	{
		$conn = openDB();
 
    	$query = "Insert Into User (User_ID, Join_Date) Values (" . $user_id . ", '" . date("Y-m-d") . "');";
    	
    	mysql_query($query);
    
        closeDB($result, $conn);
    	
    	return;
	}
	
/**
 * This function removes a user from the app.
 * @author Jon Hall/Joseph Trapani
 * @version 2.0
 * @param integer $user_id userID number
 */
	function removeUserDAL($user_id)
	{
		$conn = openDB();
 
    	$query = "Delete From User Where (User_ID= " . $user_id . ");";
    	
    	mysql_query($query);
    	
    	closeDB($result, $conn);

		return;
	}

?>
