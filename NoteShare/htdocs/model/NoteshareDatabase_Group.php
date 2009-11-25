<?php

  /**
   * Creates and returns the start of a DOM XML-style response which should hold
   *  the XML translation of the database query result.
   *
   * @version 4.0
  **/
  function openDOM()
  {
    $newDOM = new DOMDocument('1.0');
    $style = $newDOM->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
    $newDOM->appendChild($style);

    return $newDOM;
  }

  function openSuperSet( $DOMdoc, $setName )
  {
    $superSet = $DOMdoc->createElement( $setName );
    $DOMDoc->appendChild( $superSet );

    return $superSet;
  }

  function addElement( $DOMdoc, $parent, $elementName )
  {
    $newElement = $DOMdoc->createElement( $elementName );
    $parent->appendChild( $newElement );

    return $newElement;
  }

  function addAttribute( $DOMdoc, $parent, $attributeName, $attributeValue )
  {
    $newAttr = $DOMdoc->createAttribute( $attributeName );
    $parent->appendChild( $newAttr);

    addValue( $DOMdoc, $newAttr, $attributeValue );
  }

  function addValue( $DOMdoc, $parent, $value )
  {
    $newValue = $DOMdoc->createTextNode( $value );
    $parent->appendChild( $newValue );
  }

/**
 * This function returns all the details of the study group(specific term/semester course information) 
 * and course (general course information).
 * @author Joseph Trapani
 * @version 4.0
 * @param integer $group_id group ID number
 * @return XML group meta data
 */ 
function getGroupMetadataDAL ($group_id)
{
  // open the database connection
  $conn = openDB();

  // gather the query result
  $query = "SELECT StudyGroup.ID          as      StudyGroup_Id," .
                  "StudyGroup.NAME        as      StudyGroup_Name," .
                  "StudyGroup.DESCRIPTION as      StudyGroup_Desc," .
                  "StudyGroup.ACTIVE      as      StudyGroup_Active," .
                  "Group.ID             as      Group_Id," .
                  "Group.NAME           as      Group_Name," .
                  "Group.START_DATE     as      Group_Start," .
                  "Group.END_DATE       as      Group_End," .
                  "Course.ID              as      Course_Id," .
                  "Course.NAME            as      Course_Name," .
                  "Course.DESCRIPTION     as      Course_Desc," .
                  "Course.ACTIVE          as      Course_Active  " .
           "FROM   StudyGroup, Group, Course " .
           "WHERE  StudyGroup.Group_Ptr = Group.ID " .
              "AND Group.Course_Ptr = Course.ID " .
              "AND StudyGroup.ID = " . $group_id . ";";
  $result = mysql_query($query);
  echo "Result: " . $result . "\n";
  //$row = mysql_fetch_assoc( $result );
  //echo var_dump( $row );
/*
  // construct the XML document
  $doc = openDOM();
  $resultList = openSuperSet( $doc, "GroupMetaDataList" );

  while($row = mysql_fetch_assoc($result)) {

    $groupMetaData = addElement( $doc, $resultList, 'GroupMetaData' );

    addAttribute( $doc, $groupMetaData, 'Id', $row['StudyGroup_Id'] );
    addAttribute( $doc, $groupMetaData, 'Desc', $row['StudyGroup_Desc']);
    addAttribute( $doc, $groupMetaData, 'Group_Id', $row['Group_Id']);
    addAttribute( $doc, $groupMetaData, 'Group_Name', $row['Group_Name']);
    addAttribute( $doc, $groupMetaData, 'Course_Id', $row['Course_Id']);
    addAttribute( $doc, $groupMetaData, 'Course_Name', $row['Course_Name']);

    addValue( $doc, $groupMetaData, $row['StudyGroup_Name']);
  }

  $out = $doc->saveXML();

  closeDB($result, $conn);
*/
  //return $out;
}

/**
 * This function returns a note posting with the physical file location.
 * @author Joseph Trapani
 * @version 4.0
 * @param integer $group_id study group ID, integer $id unique ID number of post, integer $return_latest return latest X records 
 */
function getGroupNoteDAL ($group_id, $id = 0, $latest_posts = 0)
{
  $conn = openDB();
  

  // Allow the function to return a specific post OR a group of posts for a study group.
  $WhereClause = "Where (StudyGroupNotes.Removal_Date Is Null) And " .
			  "(StudyGroupNotes.SG_Ptr = " . $group_id . ")";

  if ($id <> 0) { 
    $WhereClause = $WhereClause . " And (ID = " . $id . ");";
  } 

  // Only select the latest X posts.
  if ($latest_posts <> 0) {
    $query = "Select * " .            
             "From StudyGroupNotes " .  
              $WhereClause ." And " .
             "(StudyGroupNotes.Path Is Not Null) And " .
             "(StudyGroupNotes.Prev_Post_Ptr Is Null) " .		
             "Order By ID Desc " .
 	      "Limit 0," . $latest_posts . ";";	    
  }
  else {
    $query = "Select * " .            
             "From StudyGroupNotes " .  
              $WhereClause;
  }
  $result = mysql_query($query);

  // Format XML response
  $doc = openDOM();
  $resultList = openSuperSet( 'getGroupNotes' );

  while ($row = mysql_fetch_assoc($result)) {

    $getGroupNote = addElement( $doc, $resultList, 'getGroupNote' );

    addAttribute( $doc, $getGroupNote, 'Id', $row['ID'] );
    addAttribute( $doc, $getGroupNote, 'User_ID', $row['User_ptr'] );
    addAttribute( $doc, $getGroupNote, 'GroupId', $row['SG_ptr'] );
    addAttribute( $doc, $getGroupNote, 'Header', $row['HEADER'] );
    addAttribute( $doc, $getGroupNote, 'Body', $row['BODY'] );
    addAttribute( $doc, $getGroupNote, 'Original_File_Name', $row['ORIGINAL_FILE_NAME'] );
    addAttribute( $doc, $getGroupNote, 'File_Size', $row['File_Size'] );
    addAttribute( $doc, $getGroupNote, 'Server_Path', $row['PATH'] );

    addValue( $doc, $getGroupNote, "http://noteshare.homelinux.net/" . substr ($row['PATH'], 53));
  }

  $out = $doc->saveXML();

  closeDB ($result, $conn);

  return $out;
}

/**
 * This function gets all of the BBS thread topics for a given group.
 * @version 4.0
 * @param integer $group_id group ID number
 * @return XML BBS thread topics of group bulletin board post data, integer $return_latest return latest X records 
 */
function getGroupBBSTopicsDAL ($group_id, $latest_posts = 0)
{
  $conn = openDB();

  if ($group_id == null)
  {
    return 'null';
  }

  // Only select the latest X posts.
  // Add the user from a given course's Group.
  if ($latest_posts <> 0) {
    
    $query = "Select  * " . 
	      "From GroupBBS " .
             "Where (GroupBBS.REMOVAL_DATE Is Null) And " .
		     "(GroupBBS.Prev_Post_Ptr Is Null) And " .
                   "(GroupBBS.SG_Ptr = " . $group_id . " ) " .
	      "Order By ID Desc " . 
             "Limit 0," . $latest_posts . ";";
  }
  else {
   
    $query = "Select * " . 
	      "From GroupBBS " .
             "Where (GroupBBS.Removal_Date Is Null) And " .
		     "(GroupBBS.Prev_Post_Ptr Is Null) And " .
                   "(GroupBBS.SG_Ptr = " . $groupn_id . " );";
  }


  $result = mysql_query($query);

  $doc = openDOM();
  $resultList = openSuperSet( 'GroupBBSTopics' );

  while ($row = mysql_fetch_assoc($result)) {

    $groupBBSTopic = addElement( $doc, $resultList, 'GroupBBSTopic' );

    addAttribute( $doc, $groupBBSTopic, 'Id', $row['ID'] );
    addAttribute( $doc, $groupBBSTopic, 'PostDate', $row['POST_DATE'] );
    addAttribute( $doc, $groupBBSTopic, 'UserId', $row['User_Ptr'] );
    addAttribute( $doc, $groupBBSTopic, 'groupId', $row['SG_Ptr'] );

    addValue( $doc, $groupBBSTopic, $row['HEADER'] );
  }

  $out = $doc->saveXML();

  closeDB ($result, $conn);

  return $out;
}

/**
 * This function gets all of the universities within the database and returns them in XML
 * @author Nathan Denklau
 * @version 1.0
 * @return XML university data
 */
/*
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
*/

/**
 * This function gets all the departments at a given university
 * @author Nathan Denklau
 * @version 1.0
 * @param integer $univ_id university ID number
 * @return XML department data
 */
/*
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
*/

/**
 * This function gets all the courses in a given department
 * @author Nathan Denklau
 * @version 1.0
 * @param integer $dept_id department ID number
 * @return XML course data
 */
/*
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
*/

/**
 * This function gets all the Groups for a given course
 * @author Nathan Denklau
 * @version 1.0
 * @param integer $course_id course ID number
 * @return XML Group data
 */
/*
function getGroupsDAL($course_id)
{
  $conn = openDB();

  $query = "Select * From Group Where COURSE_PTR = ".$course_id;
  $result = mysql_query($query);

  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $list = $doc->createElement('GroupList');
  $doc->appendChild($list);

  while($row = mysql_fetch_assoc($result)) {

    $Group = $doc->createElement('Group');
    $list->appendChild($Group);
	
    $id_attr = $doc->createAttribute('Id');
    $Group->appendChild($id_attr);
	
    $id_text = $doc->createTextNode($row['ID']);
    $id_attr->appendChild($id_text);
	
    $Group_name = $doc->createTextNode($row['NAME']);
    $Group->appendChild($Group_name);
  }

  $out = $doc->saveXML();

  closeDB ($result, $conn);

  return $out;
}
*/

/**
 * This function gets all the courses which the given user is currently enrolled in.
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $user_id user ID number
 * @return XML user's current enrolled Group list
 */
/*
function getHomePageGroupListDAL ($user_id) 
{
  
  $conn = openDB();

  $query = "Select Group.Id   		   As Group_Id,             " .
  	           "Group.Name            As Group_Name,           " . 
                  "Course.Name             As Course_Name,            " .  
		    "University.Name         As University_Name         " .


	    "From GroupEnrollment " . 

	    "Inner Join Group On Group.Id = GroupEnrollment.Group_Ptr " . 
	    "Inner Join Course On Course.Id = Group.Course_Ptr " .
	    "Inner Join Department On Department.Id = Course.Department_Ptr " . 
	    "Inner Join University On University.ID = Department.University_Ptr " . 
	    "Where (GroupEnrollment.User_Ptr = " . $user_id . ") And " .
		   "(GroupEnrollment.Left_Date Is Null) " . 
	    "Order By University.Name, Course.Name, Group.Name";	


  $result = mysql_query($query);

  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $list = $doc->createElement('UserGroupList');
  $doc->appendChild($list);

  while($row = mysql_fetch_assoc($result)) {
 
    $Groupuseritem = $doc->createElement('GroupUserItem');
    $list->appendChild($Groupuseritem);
	
    $id_attr = $doc->createAttribute('Id');
    $Groupuseritem->appendChild($id_attr);
	
    $id_text = $doc->createTextNode($row['Group_Id']);
    $id_attr->appendChild($id_text);

    $uni_attr = $doc->createAttribute('University_Name');
    $Groupuseritem->appendChild($uni_attr);
	
    $uni_text = $doc->createTextNode($row['University_Name']);
    $uni_attr->appendChild($uni_text);

    $GroupItem_Name = $doc->createTextNode($row['Course_Name'] . " - " . $row['Group_Name']);
    $Groupuseritem->appendChild($GroupItem_Name);
   
  }

  $out = $doc->saveXML();

  closeDB($result, $conn);

  return $out;
}
*/

/**
 * This function returns all the details of the Group (specific term/semester course information) 
 * and course (general course information).
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $Group_id Group ID number
 * @return XML Group meta data
 */
/*
function getGroupMetadataDAL ($Group_id)
{
    
  $conn = openDB();

  $query = "Select Group.Id          As Group_Id,        " .
                  "Group.Name        As Group_Name,      " .
                  "Group.Start_Date  As Start_Date,        " .
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
*/

/**
 * This function gets all the users from a given course's session.
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $session_id sessionID number
 * @return XML user's current enrolled session list
 */
/*
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
*/

/**
 * This function returns the result of adding a user to a Grou's enrollment.   
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $user_id user ID number, integer $Group_id Group ID number
 * @return boolean result
 */ 
function checkUserInGroupDAL ($user_id, $Group_id)
{  

  $conn1 = openDB();
   
  // Check if the user is already actively enrolled in the Group.	
  $query1 = "Select GroupEnrollment.ID " . 
            "From GroupEnrollment " .

	     "Where (GroupEnrollment.User_Ptr    = " . $user_id    . ") And " . 
	           "(GroupEnrollment.Group_Ptr = " . $Group_id . ") And " .
		    "(GroupEnrollment.Left_Date Is Null);";	


  $result1 = mysql_query($query1);
	
  // User is actively enrolled in the Group, return 1.
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
/*
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
*/

/**
 * This function returns the result of adding a user to a Group's enrollment.   
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $user_id user ID number, integer $Group_id Group ID number
 * @return XML adding of user Groupenrollment data
 */
 
function addUserGroupDAL ($user_id, $Group_id)
{

  // Check if the user is already an active member of the User table.	
  checkFirstUserInteractionDAL($user_id);


  // If the user is NOT actively enrolled in the Group, so insert a new row.
  if (checkUserInGroupDAL($user_id, $Group_id) == 0) {
	
    $conn = openDB();

    // Add the user from a given course's Group.
    $query = "Insert Into GroupEnrollment (GroupEnrollment.User_Ptr, ." . 
                                            "GroupEnrollment.Group_Ptr, " .
 		                              "GroupEnrollment.Join_Date" .
			 ") Values ("   . $user_id      . ", " . 
			                  $Group_id   . ", " .
			            "'" . date("Y-m-d") . "'); ";	


    $result = mysql_query($query);



    $doc = new DOMDocument('1.0');

    $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
    $doc->appendChild($style);
    $EndResult = $doc->createElement('AddUserGroupResult');
    $doc->appendChild($EndResult);


    $AddUserGroupResult= $doc->createElement('AddUserGroupResult');
    $doc->appendChild($AddUserGroupResult);

    $AddUserGroup_Name = $doc->createTextNode($result);
    $AddUserGroupResult->appendChild($AddUserGroup_Name);

    $out = $doc->saveXML();
    closeDB( null, $conn);
  }

  // The user was already a member of the Group.  
  else {

    $doc = new DOMDocument('1.0');

    $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
    $doc->appendChild($style);
    $EndResult = $doc->createElement ('AddUserGroupResult');
    $doc->appendChild($EndResult);


    $AddUserGroupResult= $doc->createElement('AddUserGroupResult');
    $doc->appendChild($AddUserGroupResult);

    $AddUserGroup_Name = $doc->createTextNode(1);
    $AddUserGroupResult->appendChild($AddUserGroup_Name);
    

    $out = $doc->saveXML();
  }

  return $out;
}


/**
 * This function returns the result after removing a user from a given course's Group.
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $user_id user ID number, integer $Group_id Group ID number
 * @return XML removal of user Groupenrollment data
 */   
function removeUserGroupDAL ($user_id, $Group_id)
{
    
  $conn = openDB();
 
  $query = "Update GroupEnrollment Set " . 
                  "GroupEnrollment.Left_Date = '" . date("Y-m-d") . "' " .

	    "Where (GroupEnrollment.User_Ptr = " . $user_id    . ") And " . 
		   "(GroupEnrollment.Group_Ptr = " . $Group_id . ") And " .
		   "(GroupEnrollment.Left_Date Is Null);";	

  $result = mysql_query($query);



  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $EndResult = $doc->createElement('RemoveUserGroupResult');
  $doc->appendChild($EndResult);


  $RemoveUserGroupResult= $doc->createElement('RemoveUserGroupResult');
  $doc->appendChild($RemoveUserGroupResult);

  $RemoveUserGroup_Name = $doc->createTextNode($result);
  $RemoveUserGroupResult->appendChild($RemoveUserGroup_Name);
   

  $out = $doc->saveXML();
 
  return $out;
}


/**
 * This function adds a user to the application.
 * @author Jon Hall/Joseph Trapani
 * @version 1.0
 * @param integer $user_id userID number
 */
/*
function addUserDAL ($user_id)
{
  $conn = openDB();
 
  $query = "Insert Into User (User_ID, Join_Date) Values (" . $user_id . ", '" . date("Y-m-d") . "');";
   	
  mysql_query($query);
    
  closeDB ($result, $conn);
    	
  return;
}*/

/**
 * This function removes a user from the application by setting a left date.
 * @author Jon Hall/Joseph Trapani
 * @version 1.0
 * @param integer $user_id userID number
 */
/*
function removeUserDAL ($user_id)
{
  $conn = openDB();
 
  $query = "Update User Set Left_Date = '" . date("Y-m-d") . "' " .
           "Where (User_ID = " . $user_id . ");";
    	
  mysql_query($query);

  return;
}
*/
?>
