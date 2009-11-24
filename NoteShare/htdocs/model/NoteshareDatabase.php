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
function openDB ()
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
function closeDB ($result, $conn)
{
  // Free the result memory.
  if( $result )
  {
    mysql_free_result($result);
  }

  // Close the database connection.
  mysql_close($conn);
}
  

/**
 * This function gets all of the universities within the database and returns them in XML
 * @author Nathan Denklau
 * @version 1.0
 * @return XML university data
 */
function getUniversityDAL ()
{
  $conn = openDB();

  $query = "Select * From University";
  $result = mysql_query($query);


  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $list = $doc->createElement('universityList');
  $doc->appendChild($list);

  while($row = mysql_fetch_assoc($result)) {

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

  closeDB ($result, $conn);

  return $out;
}


/**
 * This function gets all the departments at a given university
 * @author Nathan Denklau
 * @version 1.0
 * @param integer $univ_id university ID number
 * @return XML department data
 */
function getDepartmentsDAL ($univ_id)
{
  $conn = openDB();

  $query = "Select * From Department Where University_Ptr = ".$univ_id;
  $result = mysql_query($query);

  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $list = $doc->createElement('deptList');
  $doc->appendChild($list);

  while($row = mysql_fetch_assoc($result)) {
  
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
 
  closeDB ($result, $conn);

  return $out;
}


/**
 * This function gets all the courses in a given department
 * @author Nathan Denklau
 * @version 1.0
 * @param integer $dept_id department ID number
 * @return XML course data
 */
function getCoursesDAL ($dept_id)
{
  $conn = openDB();

  $query = "Select * From Course Where Department_Ptr = ".$dept_id;
  $result = mysql_query($query);

  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $list = $doc->createElement('courseList');
  $doc->appendChild($list);

  while($row = mysql_fetch_assoc($result)) {

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

  closeDB ($result, $conn);

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

  while($row = mysql_fetch_assoc($result)) {

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

  closeDB ($result, $conn);

  return $out;
}


/**
 * This function gets all the courses which the given user is currently enrolled in.
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $user_id user ID number
 * @return XML user's current enrolled session list
 */ 
function getHomePageSessionListDAL ($user_id) 
{
  
  $conn = openDB();

  $query = "Select Session.Id   		   As Session_Id,             " .
  	           "Session.Name            As Session_Name,           " . 
                  "Course.Name             As Course_Name,            " .  
		    "University.Name         As University_Name         " .


	    "From SessionEnrollment " . 

	    "Inner Join Session On Session.Id = SessionEnrollment.Session_Ptr " . 
	    "Inner Join Course On Course.Id = Session.Course_Ptr " .
	    "Inner Join Department On Department.Id = Course.Department_Ptr " . 
	    "Inner Join University On University.ID = Department.University_Ptr " . 
	    "Where (SessionEnrollment.User_Ptr = " . $user_id . ") And " .
		   "(SessionEnrollment.Left_Date Is Null) " . 
	    "Order By University.Name, Course.Name, Session.Name";	


  $result = mysql_query($query);

  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $list = $doc->createElement('UserSessionList');
  $doc->appendChild($list);

  while($row = mysql_fetch_assoc($result)) {
 
    $sessionuseritem = $doc->createElement('SessionUserItem');
    $list->appendChild($sessionuseritem);
	
    $id_attr = $doc->createAttribute('Id');
    $sessionuseritem->appendChild($id_attr);
	
    $id_text = $doc->createTextNode($row['Session_Id']);
    $id_attr->appendChild($id_text);

    $uni_attr = $doc->createAttribute('University_Name');
    $sessionuseritem->appendChild($uni_attr);
	
    $uni_text = $doc->createTextNode($row['University_Name']);
    $uni_attr->appendChild($uni_text);

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
function getSessionMetadataDAL ($session_id)
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

	    "Where (Session.Id = " . $session_id . ");";	



  $result = mysql_query($query);



  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $EndResult = $doc->createElement('SessionMetaDataResult');
  $doc->appendChild($EndResult);


  while($row = mysql_fetch_assoc($result)) {
    
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
function getSessionMembersDAL ($session_id, $get_members = 0)
{
    
  $conn = openDB();



  // Only select the latest X members.
  if ($get_members <> 0) {
    

    $query = "Select Session.Id                 As Session_Id,  " .
                    "SessionEnrollment.User_Ptr As User_Ptr     " .

             "From Session " . 
	      "Inner Join SessionEnrollment On (SessionEnrollment.Id = Session.Course_Ptr) And " . 
		  				   "(SessionEnrollment.Left_Date Is Null) " . 

             "Where (Session.Id = " . $session_id . ") " . 
             "Order By ID Desc " . 
             "Limit 0," . $latest_posts . ";";	
  } 
  else { 
    $query = "Select Session.Id                 As Session_Id,  " .
                    "SessionEnrollment.User_Ptr As User_Ptr     " .

             "From Session " . 
	      "Inner Join SessionEnrollment On (SessionEnrollment.Id = Session.Course_Ptr) And " . 
	  			  		   "(SessionEnrollment.Left_Date Is Null) " . 

             "Where (Session.Id = " . $session_id . ") "; 
  }


  $result = mysql_query($query);



  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $list = $doc->createElement('SessionUserList');
  $doc->appendChild($list);



  while($row = mysql_fetch_assoc($result)) {

    $SessionUserItem = $doc->createElement('SessionUserItem');
    $list->appendChild($SessionUserItem);
	
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
function checkUserInSessionDAL ($user_id, $session_id)
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
    closeDB($result1, $conn1);
    return 1;
  } 
  else {
    closeDB($result1, $conn1);
    return 0;
  }	
	 
  
		  
}


/**
 * This function returns the result of adding a user to a user table.   
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $user_id user ID number
 * @return boolean result if user exists or has been added.
 */ 
function checkFirstUserInteractionDAL ($user_id)
{
    
  $conn1 = openDB();


  // Check if the user is already an active member of the User table.	
  $query1  = "Select User.User_ID " . 
             "From User " .
	      "Where (User.User_ID = " . $user_id . ") And " . 
	            "(User.Left_Date Is Null);";	


  $result1 = mysql_query($query1);
   

  // User doesn't exist as an active NoteShareSEP user.  
  if ( mysql_num_rows($result1) <= 0) {
      
    closeDB($result1, $conn1);
      
    $conn = openDB();
 
    // Add the new user ID to the user table.
    $query = "Insert Into User (User_ID, " . 
                               "Join_Date" .
		                 ") Values (" . $user_id      . ", " .
  			                   "'" . date("Y-m-d") . "'); ";	


    $result = mysql_query($query);

    closeDB($result, $conn );
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
function addUserSessionDAL ($user_id, $session_id)
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
    closeDB( null, $conn);
  }

  // The user was already a member of the session.  
  else {

    $doc = new DOMDocument('1.0');

    $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
    $doc->appendChild($style);
    $EndResult = $doc->createElement ('AddUserSessionResult');
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
function removeUserSessionDAL ($user_id, $session_id)
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
 
  return $out;
}


/**
 * This function adds a user to the application.
 * @author Jon Hall/Joseph Trapani
 * @version 2.0
 * @param integer $user_id userID number
 */
function addUserDAL ($user_id)
{
  $conn = openDB();
 
  $query = "Insert Into User (User_ID, Join_Date) Values (" . $user_id . ", '" . date("Y-m-d") . "');";
   	
  mysql_query($query);
    
  closeDB ($result, $conn);
    	
  return;
}
	
/**
 * This function removes a user from the application by setting a left date.
 * @author Jon Hall/Joseph Trapani
 * @version 2.0
 * @param integer $user_id userID number
 */
function removeUserDAL ($user_id)
{
  $conn = openDB();
 
  $query = "Update User Set Left_Date = '" . date("Y-m-d") . "' " .
           "Where (User_ID = " . $user_id . ");";
    	
  mysql_query($query);

  return;
}
	
/**
 * This function is designed to post a message to a session BBS
 * @author Jon Hall
 * @version 1.0
 * @param string $header, string $body, integer $user_id userID number, integer $session_id sessionID number, integer $prev_post id of parent post
 */
function addSessioBBSPostDAL( $header, $body, $user_id, $session_id, $prev_post)
{
	$conn = openDB();
	
	// Escape header and body
	$header = mysql_real_escape_string($header);
	$body = mysql_real_escape_string($body);
	
	$query = "Insert Into SessionBBS (Header, Body, Post_Date, User_ptr, Session_ptr, Prev_Post_ptr) " .
			"Values (\'$header\', \'$body\', ". date("Y-m-d H:m:s") .", $user_id, $session_id, $prev_post)";
			
	mysql_query($query);
	
	mysql_close($conn);
}

/**
 * This function is designed to get the parent of a session wall
 * @author Jon Hall
 * @version 1.0
 * @param integer $session_id sessionID number
 * @return integer
 */
function getSessionWallParentDAL($session_id)
{
	$conn = openDB();
	
	/****** Get the session wall parent ******/
	$wall_parent = '0';
	
	$query = "Select ID From SessionBBS " .
		  "Where ((Session_ptr = $session_id) Aand " .
			  "(User_ptr = Null) And " .
			  "(Header = \'#SESSION WALL#\'))";
			
	$result = mysql_query($query);
	
	// Check existance
	if( $row = mysql_fetch_assoc($result) )
	{
		$wall_parent = $row(ID);
		mysql_free_result($result);
	}
	// No session wall parent
	else
	{
		// Insert one
		$query = "Insert into SessionBBS (Header, Body, Post_Date, Session_ptr) " .
				"Values (\'#SESSION WALL#\', \'Here be dragons\', ". date("Y-m-d H:m:s") .",$session_id)";
				
		mysql_query($query);
		
		// Get the Id that was generated
		$query = "Select ID From SessionBBS Where (Session_ptr=\'$session_id\' and " .
			"User_ptr=null and Header=\'#SESSION WALL#\')";
			
		$result = mysql_query($query);
		
		$row = mysql_fetch_assoc($result);
		$wall_parent = $row(ID);
		
		mysql_free_result($result);
	}
	
	 mysql_close($conn);
	 
	 return $wall_parent;
}
/**
 * This function is designed to post a message to a wall
 * @author Jon Hall
 * @version 1.0
 * @param integer $user_id userID number, integer $session_id sessionID number, string $body post contents
 */
function addSessionWallPostDAL($user_id, $session_id, $body)
{
	
	$wall_parent = getSessionWallParentDAL($session_id);
	
	addSessioBBSPostDAL('', $body, $user_id, $session_id, $wall_parent);
}

/**
 * This function is designed to get posts that are on a session wall
 * @author Jon Hall
 * @version 1.0
 * @param interger $session_id sessionID number
 * @return string XML WallPosts
 */
function getSessionWallPostsDAL($session_id)
{
	// Get parent
	$wall_parent = getSessionWallParentDAL($session_id);
	
	$conn = openDB();
	
	$query = "Select * " .
		  "From SessionBBS " .
 		  "Where (Session_Ptr = $session_id) And " .
                      "(Prev_Post_Ptr = $wall_parent) " .
	         "Order By Post_Date Desc";
	
	$result =  mysql_query($query);
	
	$doc = new DOMDocument('1.0');
	$wall_posts = $doc->createElement("sessionWallPosts");
	
	
	while($row = mysql_fetch_assoc($result))
	{
		$post = $doc->createElement("post");
		
		// Add user attribute
		$user_id = $doc->createAttribute("user");
		$user_id->appendChild($doc->createTextNode($row['User_ptr']));
		$post->appendChild($user_id);
		
		// Add time attribute
		$date_Array = strptime($row['POST_DATE'],"Y-m-d H:m:s");
		$time_Stamp = mktime($date_Array['tm_hour'], $date_Array['tm_min'],
				$date_Array['tm_sec'], $date_Array['tm_mon'], 
				$date_Array['tm_mday'], $date_Array['tm_year'] );
		$time = $doc->createAttribute("time");
		$time->appendChild($doc->createTextNode($time_Stamp));
		$post->appendChild($time);
		
		// Add the post body
		$post->appendChild($doc->createTextNode($row['BODY']));
		
		// Append the post to wall_posts
		$wall_posts->appendChild($post);
	}
	
	$doc->appendChild($wall_posts);
	
	$closeDB($result,$conn);
	
	return $doc->saveXML();
}


/**
 * This function adds a note posting and physical upload location.
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $user_id userID number, integer $session_id session ID, varchar $header header post title, varchar $body message body, varchar $file_path path to uploaded file,  varchar $original_file_name original file name, float $file_size size of file (in MB)
 */
function addSessionNoteDAL ($user_id, $session_id, $header, $body, $file_path, $original_file_name, $file_size)
{
  $conn = openDB();
 
  $query = "Insert Into SessionNotes (User_Ptr, " .
					  "Session_Ptr, " .
					  "Post_Date, " . 
					  "Header, " .
					  "Body, " . 
					  "Path, " .	
  					  "Original_File_Name, " . 
					  "File_Size" . 
					 ") Values (" . 
					  $user_id . ", " . 
		                       $session_id . ", '" . 
					  date ("Y-m-d H:i:s") . "', '" . 
					  $header . "', '" . 
		                       $body . "', '" . 					  
					  $file_path . "', '" . 
		                       $original_file_name . "', " . 
					  $file_size . ");";
    	
  $result = mysql_query($query);
   	
  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $EndResult = $doc->createElement('getSessionNoteResult');
  $doc->appendChild($EndResult);


  $getSessionNoteResult = $doc->createElement('getSessionNoteResult');
  $doc->appendChild($getSessionNoteResult);

  $getSessionNote_Name = $doc->createTextNode($result);
  $getSessionNoteResult->appendChild($getSessionNote_Name);
   

  $out = $doc->saveXML();

  return $out;

}

/**
 * This function returns a note posting with the physical file location.
 * @author Joseph Trapani
 * @version 2.0
 * @param integer $session_id session ID, integer $id unique ID number of post, integer $return_latest return latest X records 
 */
function getSessionNoteDAL ($session_id, $id = 0, $latest_posts = 0)
{
  $conn = openDB();
  

  // Allow the function to return a specific post OR a group of posts for a class.
  $WhereClause = "Where (SessionNotes.Removal_Date Is Null) And " .
			  "(SessionNotes.Session_Ptr = " . $session_id . ")";

  if ($id <> 0) { 
    $WhereClause = $WhereClause . " And (ID = " . $id . ");";
  } 

  // Only select the latest X posts.
  if ($latest_posts <> 0) {
    $query = "Select * " .            
             "From SessionNotes " .  
              $WhereClause ." And " .
             "(SessionNotes.Path Is Not Null) And " .
             "(SessionNotes.Prev_Post_Ptr Is Null) " .		
             "Order By ID Desc " .
 	      "Limit 0," . $latest_posts . ";";	    
  }
  else {
    $query = "Select * " .            
             "From SessionNotes " .  
              $WhereClause;
  }

  
  $result = mysql_query($query);
   	
  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $SessionNote = $doc->createElement('getSessionNotes');
  $doc->appendChild($SessionNote);


  while ($row = mysql_fetch_assoc($result)) {
  
    $getSessionNote = $doc->createElement('getSessionNote');
    $SessionNote->appendChild($getSessionNote);


    $id_attr = $doc->createAttribute('Id');
    $getSessionNote->appendChild($id_attr);
   
    $id_text = $doc->createTextNode($row['ID']);
    $id_attr->appendChild($id_text);


    $user_attr = $doc->createAttribute('User_ID');
    $getSessionNote->appendChild($user_attr);
   
    $user_text = $doc->createTextNode($row['User_ptr']);
    $user_attr->appendChild($user_text);


    $session_attr = $doc->createAttribute('SessionId');
    $getSessionNote->appendChild($session_attr);
   
    $session_text = $doc->createTextNode($row['Session_ptr']);
    $session_attr->appendChild($session_text);


    $header_attr = $doc->createAttribute('Header');
    $getSessionNote->appendChild($header_attr);
   
    $header_text = $doc->createTextNode($row['HEADER']);
    $header_attr->appendChild($header_text);


    $body_attr = $doc->createAttribute('Body');
    $getSessionNote->appendChild($body_attr);
   
    $body_text = $doc->createTextNode($row['BODY']);
    $body_attr->appendChild($body_text);


    $originalfilename_attr = $doc->createAttribute('Original_File_Name');
    $getSessionNote->appendChild($originalfilename_attr);
   
    $originalfilename_text = $doc->createTextNode($row['ORIGINAL_FILE_NAME']);
    $originalfilename_attr->appendChild($originalfilename_text);


    $filesize_attr = $doc->createAttribute('File_Size');
    $getSessionNote->appendChild($filesize_attr);
   
    $filesize_text = $doc->createTextNode($row['File_Size']);
    $filesize_attr->appendChild($filesize_text);


    $Server_Path_attr = $doc->createAttribute('Server_Path');
    $getSessionNote->appendChild($Server_Path_attr);
   
    $filesize_text = $doc->createTextNode($row['PATH']);
    $filesize_attr->appendChild($filesize_text);



    $getSessionNote_Name = $doc->createTextNode("http://noteshare.homelinux.net/" . substr ($row['PATH'], 53));
    $getSessionNote->appendChild($getSessionNote_Name);  

  }

  $out = $doc->saveXML();

  closeDB ($result, $conn);
 
  return $out;

}


/**
 * This function returns the result after removing a note from public view, then removes the physical note on the server.
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $id note's database ID number
 * @return XML removal of note data
 */   
function removeSessionNoteDAL ($id)
{
    
  $conn = openDB();
 
  $query = "Update SessionNotes Set " . 
                  "SessionNotes.Removal_Date = '" . date("Y-m-d") . "' " .

	    "Where (SessionNotes.ID = " . $id . ");";	

  $result = mysql_query($query);



  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $EndResult = $doc->createElement('RemoveSessionNoteResult');
  $doc->appendChild($EndResult);


  $RemoveSessionNoteResult= $doc->createElement('RemoveSessionNoteResult');
  $doc->appendChild($RemoveSessionNoteResult);

  $RemoveSessionNote_Name = $doc->createTextNode($result);
  $RemoveSessionNoteResult->appendChild($RemoveSessionNote_Name);
   

  $out = $doc->saveXML();


  // Remove the note from the server.
  $query = "Select SessionNotes.PATH " . 
 	    "From SessionNotes " . 
	    "Where (SessionNotes.ID = " . $id . ");";	

  $result = mysql_query($query);

  while($row = mysql_fetch_assoc($result)) {
   
    If ($row['PATH'] <> '') {
    
      
        unlink($row['PATH']);

    }

  }

  closeDB ($result, $conn);


  return $out;
}


/**
 * This function returns the result of adding a session bulletin board post to a
 * a session's bulletin board.
 * @version 1.0
 * @param integer $user_id user ID number
 * @param integer $session_id session ID number
 * @param string  $header title of the post
 * @param string  $body content of the post
 * @param integer $parentID parent post of the thread (could be null)
 * @return XML adding of session bulletin board post data
 */
function addSessionBBSPostDAL ($user_id, $session_id, $header, $body, $parentID)
{
  $conn = openDB();

  if($parentID == null)
  {
    $parentID = 'null';
  }

  // Add the user from a given course's session.
  $query = "Insert Into SessionBBS (SessionBBS.User_Ptr, " .
                                   "SessionBBS.Session_Ptr, " .
 	                            "SessionBBS.Header, " .
                                   "SessionBBS.Body, " .
                                   "SessionBBS.Post_Date, " .
                                   "SessionBBS.Prev_Post_Ptr " .
                        ") Values ("   . $user_id      . ", " .
	                                  $session_id   . ", " .
                                   "'" . $header       . "', " .
                                   "'" . $body         . "', " .
	                            "'" . date("Y-m-d H:i:s") . "', " .
                                         $parentID     . "); ";

  $result = mysql_query($query);

  closeDB (null, $conn);

  return $result;
}


/**
 * This function gets all of the BBS thread topics for a given session.
 * @version 3.0
 * @param integer $session_id session ID number
 * @return XML BBS thread topics of session bulletin board post data, integer $return_latest return latest X records 
 */
function getSessionBBSTopicsDAL ($session_id, $latest_posts = 0)
{
  $conn = openDB();

  if ($session_id == null)
  {
    return 'null';
  }

  // Only select the latest X posts.
  // Add the user from a given course's session.
  if ($latest_posts <> 0) {
    
    $query = "Select  * " . 
	      "From SessionBBS " .
             "Where (SessionBBS.Removal_Date Is Null) And " .
		     "(SessionBBS.Prev_Post_Ptr Is Null) And " .
                   "(SessionBBS.Session_Ptr = " . $session_id . " ) " .
	      "Order By ID Desc " . 
             "Limit 0," . $latest_posts . ";";
  }
  else {
   
    $query = "Select * " . 
	      "From SessionBBS " .
             "Where (SessionBBS.Removal_Date Is Null) And " .
		     "(SessionBBS.Prev_Post_Ptr Is Null) And " .
                   "(SessionBBS.Session_Ptr = " . $session_id . " );";
  }


  $result = mysql_query($query);

  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);

  $EndResult = $doc->createElement("SessionBBSTopics");
  $doc->appendChild($EndResult);

  while ($row = mysql_fetch_assoc($result)) {

    // create the SessionBBSTopic Tag <SessionBBSTopic>
    $sessionBBSTopic = $doc->createElement('SessionBBSTopic');
    $EndResult->appendChild($sessionBBSTopic);
           
    // Add the Id attribute Id=""
    $sessionBBSTopic_id = $doc->createAttribute('Id');
    $sessionBBSTopic->appendChild($sessionBBSTopic_id);
    $sessionBBSTopic_id_text = $doc->createTextNode($row['ID']);
    $sessionBBSTopic_id->appendChild($sessionBBSTopic_id_text);

    // Add the PostDate attribute PostDate=""
    $sessionBBSTopic_date = $doc->createAttribute('PostDate');
    $sessionBBSTopic->appendChild($sessionBBSTopic_date);
    $sessionBBSTopic_date_text = $doc->createTextNode($row['POST_DATE']);
    $sessionBBSTopic_date->appendChild($sessionBBSTopic_date_text);

    // Add the UserId attribute UserId=""
    $sessionBBSTopic_user = $doc->createAttribute('UserId');
    $sessionBBSTopic->appendChild($sessionBBSTopic_user);
    $sessionBBSTopic_user_text = $doc->createTextNode($row['User_Ptr']);
    $sessionBBSTopic_user->appendChild($sessionBBSTopic_user_text);

    // Add the SessionId attribute SessionId
    $sessionBBSTopic_session = $doc->createAttribute('SessionId');
    $sessionBBSTopic->appendChild($sessionBBSTopic_session);
    $sessionBBSTopic_session_text = $doc->createTextNode($row['Session_Ptr']);
    $sessionBBSTopic_session->appendChild($sessionBBSTopic_session_text);

    // Fill in the topic sessionBBSTopic (subject) <>sessionBBSTopic</>
    $sessionBBSTopic_text = $doc->createTextNode($row['HEADER']);
    $sessionBBSTopic->appendChild($sessionBBSTopic_text);
  }

  $out = $doc->saveXML();

  closeDB ($result, $conn);

  return $out;
}


/**
 * This function returns the thread specified by the parent thread id
 *
 * @version 2.0
 * @param integer $parentId parent thread id number
 * @return XML of thread topic including all children posts
 */
function getSessionBBSPostsDAL ($parentId)
{
  $conn = openDB();

  // Query for threads that are either the parent thread or decend from the
  //  parent thread
  $query = "Select * " . 
	    "From SessionBBS " .
           "Where (SessionBBS.Removal_Date Is Null) And " .
                 "((SessionBBS.Prev_Post_Ptr = " . $parentId . ") Or " .
                  "(SessionBBS.ID = " . $parentId . " ));";

  $result = mysql_query($query);

  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $sessionBBSThread = $doc->createElement('SessionBBSThread');
  $doc->appendChild($sessionBBSThread);

  while ($row = mysql_fetch_assoc($result)) {

    // Attach header attribute to SessionBBSThread from the first post.
    if (!$row['Prev_Post_Ptr'])
    {
      $sessionBBSThread_header = $doc->createAttribute('Header');
      $sessionBBSThread->appendChild($sessionBBSThread_header);
      $sessionBBSThread_header_text = $doc->createTextNode($row['HEADER']);
      $sessionBBSThread_header->appendChild($sessionBBSThread_header_text);
    }

    // create the SessionBBSTopic Tag <SessionBBSTopic>
    $sessionBBSPost = $doc->createElement('SessionBBSPost');
    $sessionBBSThread->appendChild($sessionBBSPost);

    // Add the Id attribute Id=""
    $sessionBBSPost_id = $doc->createAttribute('Id');
    $sessionBBSPost->appendChild($sessionBBSPost_id);
    $sessionBBSPost_id_text = $doc->createTextNode($row['ID']);
    $sessionBBSPost_id->appendChild($sessionBBSPost_id_text);

    // Add the PostDate attribute PostDate=""
    $sessionBBSPost_date = $doc->createAttribute('PostDate');
    $sessionBBSPost->appendChild($sessionBBSPost_date);
    $sessionBBSPost_date_text = $doc->createTextNode($row['POST_DATE']);
    $sessionBBSPost_date->appendChild($sessionBBSPost_date_text);

    // Add the UserId attribute UserId=""
    $sessionBBSPost_user = $doc->createAttribute('UserId');
    $sessionBBSPost->appendChild($sessionBBSPost_user);
    $sessionBBSPost_user_text = $doc->createTextNode($row['User_Ptr']);
    $sessionBBSPost_user->appendChild($sessionBBSPost_user_text);

    // Add the SessionId attribute SessionId
    $sessionBBSPost_session = $doc->createAttribute('SessionId');
    $sessionBBSPost->appendChild($sessionBBSPost_session);
    $sessionBBSPost_session_text = $doc->createTextNode($row['Session_Ptr']);
    $sessionBBSPost_session->appendChild($sessionBBSPost_session_text);

    // Fill in the Post sessionBBSPost (subject) <>sessionBBSPost</>
    $sessionBBSPost_text = $doc->createTextNode($row['BODY']);
    $sessionBBSPost->appendChild($sessionBBSPost_text);
  }

  $out = $doc->saveXML();

  closeDB ($result, $conn);

  return $out;

}


/**
 * This function returns the result after removing a bbs post from public view.
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $id bbs post's database ID number
 * @return XML removal of bbs data
 */   
function removeSessionBBSDAL ($id)
{
    
  $conn = openDB();
 
  $query = "Update SessionBBS Set " . 
                  "SessionBBS.Removal_Date = '" . date("Y-m-d") . "' " .

	    "Where (SessionBBS.ID = " . $id . ");";	

  $result = mysql_query($query);



  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $EndResult = $doc->createElement('RemoveSessionBBSResult');
  $doc->appendChild($EndResult);


  $RemoveSessionBBSResult= $doc->createElement('RemoveSessionBBSResult');
  $doc->appendChild($RemoveSessionBBSResult);

  $RemoveSessionBBS_Name = $doc->createTextNode($result);
  $RemoveSessionBBSResult->appendChild($RemoveSessionBBS_Name);
   

  $out = $doc->saveXML();

  return $out;
}


?>
