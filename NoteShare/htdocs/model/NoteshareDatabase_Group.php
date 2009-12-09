<?php

/**
 * This function creates a new study group associated to a session.
 * @author Joseph Trapani
 * @version 2.0
 * @param integer $study_group_id study group ID number, string $name name string, string $desc desc string
 * @return XML Group data
 */

function createStudyGroupDAL($user_id, $session_id, $name, $desc)
{
  $conn = openDB();

  // Alter passed variables for mysql injections
  $name = mysql_real_escape_string( $name );
  $desc = mysql_real_escape_string( $desc );
  $query = "Insert Into StudyGroup (NAME, Session_Ptr, ACTIVE, DESCRIPTION) " . 
		    "Values ('" . $name . "'," . $session_id . ",1,'" . $desc . "');"; 
  $result = mysql_query($query);

  // Check if SQL succeeded
  if (!$result) {
    return 0;
  }
  else {
    return 1;
  }

}



/**
 * This function gets all the Study Groups for a given session
 * @author Joseph Trapani
 * @version 2.0
 * @param integer $session_id session ID number, integer $user_id user id number, bit $basic basic bit
 * @return XML Group data
 */

//Sat's version
function getStudyGroupsDAL($user_id, $session_id)
{
  $conn = openDB();

  $query1 = "SELECT * FROM StudyGroup AS SG WHERE SG.Session_Ptr=". $session_id; 
  $query2 = "SELECT * FROM StudyGroupEnrollment AS SGE WHERE 
             SGE.LEFT_DATE IS NULL AND SGE.User_Ptr=" . $user_id;
  $result1 = mysql_query($query1); // All StudyGroups for Session
  $result2 = mysql_query($query2); // SGs in which user is enrolled
  
  $doc = new DOMDocument('1.0');
  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);

  $list = $doc->createElement('GroupList');
  $doc->appendChild($list);

  $uid = $doc->createElement('userID'); 
  $uid->appendChild($doc->createTextNode($user_id)); 
  $list->appendChild($uid);
  
  $sid = $doc->createElement('sessionID'); 
  $sid->appendChild($doc->createTextNode($session_id)); 
  $list->appendChild($sid);
  
  while($row1 = mysql_fetch_assoc($result1)) {

    $Group = $doc->createElement('Group');
    $list->appendChild($Group);  
    $id_attr = $doc->createAttribute('Id');
    $Group->appendChild($id_attr);
  
    $id_text = $doc->createTextNode($row1['ID']);
    $id_attr->appendChild($id_text);
    $desc_attr = $doc->createAttribute('description');
    $Group->appendChild($desc_attr);

    $desc_text = $doc->createTextNode($row1['DESCRIPTION']);
    $desc_attr->appendChild($desc_text);
    $member_attr = $doc->createAttribute('member');
    $Group->appendChild($member_attr);

    $member_text = $doc->createTextNode("False");

    if( mysql_num_rows( $result2 ))
    {
      mysql_data_seek($result2, 0);
      while ($row2 = mysql_fetch_assoc($result2)) {
        if ($row2['SG_Ptr'] == $row1['ID']) {	
            // If SG_ID is present in $results2, then enrolled
            $member_text = $doc->createTextNode("True");
        }
      }
    }
    $member_attr->appendChild($member_text);  
    $Group->appendChild($doc->createTextNode($row1['NAME'])); 

  }

  $out = $doc->saveXML();

  closeDB ($result, $conn);

  return $out;

}

//Joe's version
function getStudyGroupsDAL2($session_id, $user_id, $basic = 0)
{
  $conn = openDB();

  $query = "Select StudyGroup.ID, StudyGroup.Name, StudyGroupEnrollment.User_Ptr " .
           "From StudyGroup " . 
           "Left Outer Join StudyGroupEnrollment On (StudyGroupEnrollment.SG_Ptr = StudyGroup.ID) And " .
							  "(StudyGroupEnrollment.User_Ptr = " . $user_id . ") " .
          "Where (StudyGroup.Session_Ptr = " . $session_id . ") " .
          "Group By StudyGroup.ID, StudyGroup.Name, StudyGroupEnrollment.User_Ptr "; 


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

  if ($basic = 0) {	
    $user_attr = $doc->createAttribute('User_Ptr');
    $Group->appendChild($user_attr);

    $user_text = $doc->createTextNode($row['UserId']);
    $user_attr->appendChild($user_text);
  }	

    $Group_name = $doc->createTextNode($row['NAME']);
    $Group->appendChild($Group_name);
  }

  $out = $doc->saveXML();

  closeDB ($result, $conn);

  return $out;

}


/**
 * This function gets all the study groups which the given user is currently enrolled in.
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $user_id user ID number
 * @return XML user's current enrolled study group list
 */
function getHomePageStudyGroupListDAL ($user_id) 
{
  
  $conn = openDB();

  $query = "Select StudyGroup.Id AS StudyGroup_Id, " .
  	              "StudyGroup.Name AS StudyGroup_Name, " .
                  "Session.Name AS Session_Name, " .
		              "Course.Name As Course_Name, " .
		              "University.Name As University_Name, " .
                  "StudyGroup.Session_Ptr As Session_Id " .
			      "From StudyGroupEnrollment " .
      			"Inner Join StudyGroup On (StudyGroup.Id = StudyGroupEnrollment.SG_Ptr) " .	
      			"Inner Join Session On    (Session.Id = StudyGroup.Session_Ptr) " . 
      			"Inner Join Course On     (Course.Id = Session.Course_Ptr) " .
			      "Inner Join Department On (Department.Id = Course.Department_Ptr) " . 
      			"Inner Join University On (University.Id = Department.University_Ptr) " . 
      			"Where (StudyGroupEnrollment.User_Ptr = " . $user_id . ") And " .
            			"(StudyGroupEnrollment.Left_Date Is Null) " .
		      	"Group By University.Name, Course.Name, Session.Name, StudyGroup.Name, StudyGroup.Id "	.  
      			"Order By University.Name, Course.Name, Session.Name, StudyGroup.Name";	

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
    $id_text = $doc->createTextNode($row['StudyGroup_Id']);
    $id_attr->appendChild($id_text);

    $sid_attr = $doc->createAttribute('SessionId');
    $Groupuseritem->appendChild($sid_attr);
    $sid_text = $doc->createTextNode($row['Session_Id']);
    $sid_attr->appendChild($sid_text);

    $uni_attr = $doc->createAttribute('University_Name');
    $Groupuseritem->appendChild($uni_attr);
    $uni_text = $doc->createTextNode($row['University_Name']);
    $uni_attr->appendChild($uni_text);

    $course_attr = $doc->createAttribute('Course_Name');
    $Groupuseritem->appendChild($course_attr);
    $course_text = $doc->createTextNode($row['Course_Name']);
    $course_attr->appendChild($course_text);

    $session_attr = $doc->createAttribute('Session_Name');
    $Groupuseritem->appendChild($session_attr);
    $session_text = $doc->createTextNode($row['Session_Name']);
    $session_attr->appendChild($session_text);

    $GroupItem_Name = $doc->createTextNode($row['StudyGroup_Name']);
    $Groupuseritem->appendChild($GroupItem_Name);
  }

  $out = $doc->saveXML();
  closeDB($result, $conn);
  return $out;
}


/**
 * This function returns all the details of the Study Group (specific term/semester course information) 
 * and course (general course information).
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $study_group_id Study Group ID number
 * @return XML Group meta data
 */

function getStudyGroupMetadataDAL ($study_group_id)
{  
  $conn = openDB();
  $query =  "Select StudyGroup.Id          As StudyGroup_Id,     " .
            "StudyGroup.Name        As StudyGroup_Name,   " .
            "StudyGroup.Description As StudyGroup_Desc,	 " . 
            "Session.Name	       As Session_Name,      " . 
            "Session.Start_Date     As Start_Date,        " .
   		    "Session.End_Date       As End_Date,          " .
            "Course.Name            As Course_Name,       " .  
		    "Course.Description     As Course_Description " .
			"From StudyGroup " . 
			"Inner Join Session On (Session.Id = StudyGroup.Session_Ptr) " .
			"Inner Join Course On  (Course.Id = Session.Course_Ptr) " . 
			"Where (StudyGroup.Id = " . $study_group_id . ");";
	
  $result = mysql_query($query);


  $doc = new DOMDocument('1.0');
  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $EndResult = $doc->createElement('StudyGroupMetaDataResult');
  $doc->appendChild($EndResult);

  while($row = mysql_fetch_assoc($result)) {    

    $StudyGroupmetadata= $doc->createElement('StudyGroupMetaData');
    
    $EndResult->appendChild($StudyGroupmetadata);
    $id_attr = $doc->createAttribute('Id');
    $StudyGroupmetadata->appendChild($id_attr);
    
    $id_text = $doc->createTextNode($row['StudyGroup_Id']);
    $id_attr->appendChild($id_text);
    $startdate_attr = $doc->createAttribute('Start_Date');
    $StudyGroupmetadata->appendChild($startdate_attr);
    
    $startdate_text = $doc->createTextNode($row['Start_Date']);
    $startdate_attr->appendChild($startdate_text);
    $enddate_attr = $doc->createAttribute('End_Date');
    $StudyGroupmetadata->appendChild($enddate_attr);
    
    $enddate_text = $doc->createTextNode($row['End_Date']);
    $enddate_attr->appendChild($enddate_text);
    $desc_attr = $doc->createAttribute('Desc');
    $StudyGroupmetadata->appendChild($desc_attr);
    
    $desc_text = $doc->createTextNode($row['Course_Description']);
    $desc_attr->appendChild($desc_text);	
    $SGdesc_attr = $doc->createAttribute('StudyGroup_Description');
    $StudyGroupmetadata->appendChild($SGdesc_attr);
    
    $SGdesc_text = $doc->createTextNode($row['StudyGroup_Desc']);
    $SGdesc_attr->appendChild($SGdesc_text);	
    $StudyGroupMetaData_Name = $doc->createTextNode($row['Course_Name'] . " - " . $row['Session_Name'] . " - " . $row['StudyGroup_Name']);
    $StudyGroupmetadata->appendChild($StudyGroupMetaData_Name);
  }

  $out = $doc->saveXML();
  closeDB($result, $conn);
  return $out;
}


/**
 * This function gets all the users from a given session's study group.
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $study_group_id study group ID number, integer $user_id number, object $facebook, integer $get_members number
 * @return XML user's current enrolled study group list
 */

function getStudyGroupMembersDAL ($study_group_id, $user_id, $facebook, $get_members = 0)
{
    
  $conn = openDB();



  // Only select the latest X members.
  if ($get_members <> 0) {
    

    $query = "Select StudyGroup.Id                 As StudyGroup_Id,  " .
                    "StudyGroupEnrollment.User_Ptr As User_Ptr        " .

             "From StudyGroup " . 
	      "Inner Join StudyGroupEnrollment On (StudyGroupEnrollment.SG_Ptr = StudyGroup.ID) And " . 
		  				      "(StudyGroupEnrollment.Left_Date Is Null) " . 

             "Where (StudyGroup.Id = " . $study_group_id . ") " . 
             "Order By StudyGroupEnrollment.ID Desc " . 
             "Limit 0," . $get_members . ";";	
  } 
  else { 
    $query = "Select StudyGroup.Id                 As StudyGroup_Id,  " .
                    "StudyGroupEnrollment.User_Ptr As User_Ptr        " .

             "From StudyGroup " . 
	      "Inner Join StudyGroupEnrollment On (StudyGroupEnrollment.SG_Ptr = StudyGroup.ID) And " . 
	  			  		      "(StudyGroupEnrollment.Left_Date Is Null) " . 

             "Where (StudyGroup.Id = " . $study_group_id . ") "; 
  }


  $result = mysql_query($query);



  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $list = $doc->createElement('StudyGroupUserList');
  $doc->appendChild($list);



  while($row = mysql_fetch_assoc($result)) {

    $StudyGroupUserItem = $doc->createElement('StudyGroupUserItem');
    $list->appendChild($StudyGroupUserItem);
	

    $id_attr = $doc->createAttribute('Id');
    $StudyGroupUserItem->appendChild($id_attr);
	
    $id_text = $doc->createTextNode($row['StudyGroup_Id']);
    $id_attr->appendChild($id_text);


    // Use Facebook API to check if the study group member is a friend 1(friend)/0(not friend) 
    $check = $facebook->api_client->friends_areFriends($user_id,$row['User_Ptr']);    

    $friend_attr = $doc->createAttribute('isFriend');
    $StudyGroupUserItem->appendChild($friend_attr);
    $friend_text = $doc->createTextNode($check[0]['are_friends']);
    $friend_attr->appendChild($friend_text);

    // get user facebook information
    $user_details = $facebook->api_client->users_getInfo($row['User_Ptr'], 'last_name, first_name, pic_square');

    // Add the Facebook User name attribute UserName=""
    $StudyGroupUserItem_userName = $doc->createAttribute('UserName');
    $StudyGroupUserItem->appendChild($StudyGroupUserItem_userName);
    $StudyGroupUserItem_userName_text = $doc->createTextNode( $user_details[0]['first_name'] . ' ' . $user_details[0]['last_name'] );
    $StudyGroupUserItem_userName->appendChild($StudyGroupUserItem_userName_text );

    // Add the facebook profile pic url
    $StudyGroupUserItem_pic = $doc->createAttribute('PicURL');
    $StudyGroupUserItem->appendChild($StudyGroupUserItem_pic);
    $StudyGroupUserItem_picURL = $doc->createTextNode( $user_details[0]['pic_square'] );
    $StudyGroupUserItem_pic->appendChild($StudyGroupUserItem_picURL );

    // Return the Facebook User ID to the controller.
    $StudyGroupUserItem_Name = $doc->createTextNode($row['User_Ptr']);
    $StudyGroupUserItem->appendChild($StudyGroupUserItem_Name);
  }

  $out = $doc->saveXML();

  closeDB ($result, $conn);

  return $out;

}

/**
 * This function returns the result of adding a user to a study group's enrollment.   
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $user_id user ID number, integer $study_group_id study group ID number
 * @return boolean result
 */ 
function checkUserInStudyGroupDAL ($user_id, $study_group_id)
{  

  $conn1 = openDB();
   
  // Check if the user is already actively enrolled in the session.	
  $query1 = "Select StudyGroupEnrollment.ID " . 
            "From StudyGroupEnrollment " .

	     "Where (StudyGroupEnrollment.User_Ptr    = " . $user_id    . ") And " . 
	           "(StudyGroupEnrollment.SG_Ptr      = " . $study_group_id . ") And " .
		    "(StudyGroupEnrollment.Left_Date Is Null);";	


  $result1 = mysql_query($query1);
	
  // User is actively enrolled in the study group, return 1.
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
 * This function adds a user to a study group.
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $user_id, integer $session_id, integer $study_group_id
 */

function addStudyGroupUserGroupDAL ($user_id, $session_id, $study_group_id)
{


  // If the user is NOT actively enrolled in the study group, so insert a new row.
  if (checkUserInSessionDAL ($user_id, $session_id) == 1) 
  {

    if (checkUserInStudyGroupDAL ($user_id, $study_group_id) == 0) 
    { 
	
      $conn = openDB();

      // Add the user from a given session's study group.
      $query = "Insert Into StudyGroupEnrollment (StudyGroupEnrollment.User_Ptr, ." . 
                                                 "StudyGroupEnrollment.SG_Ptr, " .
 	  	                                   "StudyGroupEnrollment.Join_Date" .
  			 ") Values ("   . $user_id      . ", " . 
  			                  $study_group_id   . ", " .
			            "'" . date("Y-m-d") . "'); ";	



      $result = mysql_query($query);



      $doc = new DOMDocument('1.0');

      $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
      $doc->appendChild($style);
      $EndResult = $doc->createElement('AddUserStudyGroupResult');
      $doc->appendChild($EndResult);


      $AddUserStudyGroupResult= $doc->createElement('AddUserStudyGroupResult');
      $doc->appendChild($AddUserStudyGroupResult);

      $AddUserStudyGroup_Name = $doc->createTextNode(1);
      $AddUserStudyGroupResult->appendChild($AddUserStudyGroup_Name);

      $out = $doc->saveXML();
      closeDB( null, $conn);
    }


    // The user was already a member of the study group.  
    else {

      $doc = new DOMDocument('1.0');

      $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
      $doc->appendChild($style);
      $EndResult = $doc->createElement ('AddUserStudyGroupResult');
      $doc->appendChild($EndResult);


      $AddUserStudyGroupResult= $doc->createElement('AddUserStudyGroupResult');
      $doc->appendChild($AddUserStudyGroupResult);

      $AddUserStudyGroup_Name = $doc->createTextNode(1);
      $AddUserStudyGroupResult->appendChild($AddUserStudyGroup_Name);
    

      $out = $doc->saveXML();
    }
  }
  
  // The user must join a session before joining a study group.
  else { 

    $doc = new DOMDocument('1.0');

    $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
    $doc->appendChild($style);
    $EndResult = $doc->createElement ('AddUserStudyGroupResult');
    $doc->appendChild($EndResult);


    $AddUserStudyGroupResult= $doc->createElement('AddUserStudyGroupResult');
    $doc->appendChild($AddUserStudyGroupResult);

    $AddUserStudyGroup_Name = $doc->createTextNode(0);
    $AddUserStudyGroupResult->appendChild($AddUserStudyGroup_Name);
    

    $out = $doc->saveXML();

  }

  return $out;

}


/**
 * This function returns the result after removing a user from a given session's study group.
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $user_id user ID number, integer $study_group_id study group ID number
 * @return XML removal of user studygroupenrollment data
 */   
function removeUserStudyGroupDAL ($user_id, $study_group_id)
{
    
  $conn = openDB();
 
  $query = "Update StudyGroupEnrollment Set " . 
                  "StudyGroupEnrollment.Left_Date = '" . date("Y-m-d") . "' " .

	    "Where (StudyGroupEnrollment.User_Ptr = " . $user_id        . ") And " . 
		   "(StudyGroupEnrollment.SG_Ptr   = " . $study_group_id . ") And " .
		   "(StudyGroupEnrollment.Left_Date Is Null);";	

  $result = mysql_query($query);



  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $EndResult = $doc->createElement('RemoveUserStudyGroupResult');
  $doc->appendChild($EndResult);


  $RemoveUserStudyGroupResult= $doc->createElement('RemoveUserStudyGroupResult');
  $doc->appendChild($RemoveUserStudyGroupResult);

  $RemoveUserStudyGroup_Name = $doc->createTextNode($result);
  $RemoveUserStudyGroupResult->appendChild($RemoveUserStudyGroup_Name);
   

  $out = $doc->saveXML();
 
  return $out;
}



/**
 * This function is designed to post a message to a study_group BBS
 * @author Jon Hall
 * @version 1.0
 * @param string $header, string $body, integer $user_id userID number, integer $study_group_id study_groupID number, integer $prev_post id of parent post
 */
function addStudyGroupBBSPostDAL2( $header, $body, $user_id, $study_group_id, $prev_post)
{
	$conn = openDB();	

	// Escape header and body
	$header = mysql_real_escape_string($header);
	$body = mysql_real_escape_string($body);

	$query = "Insert Into StudyGroupBBS (Header, Body, Post_Date, User_ptr, SG_Ptr, Prev_Post_ptr) " .
			"Values ('$header', '$body', '". date("Y-m-d H:i:s") ."', ". 
			$user_id . ", " . $study_group_id . ", " . $prev_post . ")";		
	mysql_query($query);
	mysql_close($conn);
}

/**
 * This function is designed to get the parent of a study_group wall
 * @author Jon Hall
 * @version 1.0
 * @param integer $study_group_id study_groupID number
 * @return integer
 */
function getStudyGroupWallParentDAL($study_group_id)
{
	$conn = openDB();
	/****** Get the study_group wall parent ******/
	$wall_parent = '0';
	$query = "Select ID From StudyGroupBBS " .
		  "Where ((SG_Ptr = " . $study_group_id .") And " .
			  "(User_Ptr is Null) And " .
			  "(HEADER = '#STUDY GROUP WALL#'));";

	$result = mysql_query($query);
	// Check existance
	if( $row = mysql_fetch_assoc($result) )
	{
		$wall_parent = $row['ID'];
		mysql_free_result($result);
	}
	// No study_group wall parent
	else
	{
		// Insert one
		$query = "Insert into StudyGroupBBS (Header, Body, Post_Date, SG_Ptr) " .
				"Values ('#STUDY GROUP WALL#', 'Here be dragons', '". 
				date("Y-m-d H:i:s") ."',". $study_group_id . ")";
		mysql_query($query);

		// Get the Id that was generated
		$query = "Select ID From StudyGroupBBS Where (SG_Ptr = '". $study_group_id . "' and " .
				 "User_ptr is null and Header='#STUDY GROUP WALL#')";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		$wall_parent = $row['ID'];
		mysql_free_result($result);
	}
	mysql_close($conn);
	return $wall_parent;
}

/**
 * This function is designed to post a message to a wall
 * @author Jon Hall
 * @version 1.0
 * @param integer $user_id userID number, integer $study_group_id study_groupID number, string $body post contents
 */
function addStudyGroupWallPostDAL($user_id, $study_group_id, $body)
{
	
	$wall_parent = getStudyGroupWallParentDAL($study_group_id);

	addStudyGroupBBSPostDAL($user_id, $study_group_id, '', $body, $wall_parent);
}

/**
 * This function is designed to get posts that are on a study_group wall
 * @author Jon Hall
 * @version 1.0
 * @param interger $study_group_id study_groupID number
 * @return string XML WallPosts
 */
function getStudyGroupWallPostsDAL($study_group_id, $facebook)
{
	// Get parent
	$wall_parent = getStudyGroupWallParentDAL($study_group_id);
	$conn = openDB();
	$query = "Select * " .
		  "From StudyGroupBBS " .
 		  "Where (SG_Ptr = " . $study_group_id . ") And " .
          "(Prev_Post_Ptr = " . $wall_parent . ") " .
	      "Order By Post_Date Desc " .
	      "Limit 0, 5";
	
	$result =  mysql_query($query);
	echo mysql_error();
	$doc = new DOMDocument('1.0');
	$wall_posts = $doc->createElement("study_groupWallPosts");
	
	
	while($row = mysql_fetch_assoc($result))
	{
		$post = $doc->createElement("post");
		
		// Add user attribute
		$user_id = $doc->createAttribute("user");
		$user_id->appendChild($doc->createTextNode($row['User_Ptr']));
		$post->appendChild($user_id);
		
		// Add time attribute
		/*$date_Array = strptime($row['POST_DATE'],"%Y-%m-%d %H:%M:%S");

		$time_Stamp = mktime($date_Array['tm_hour'], $date_Array['tm_min'],
				$date_Array['tm_sec'], $date_Array['tm_mon']+1, 
				$date_Array['tm_mday'], $date_Array['tm_year']+1900 );
    */
		$time = $doc->createAttribute("time");
		$time->appendChild($doc->createTextNode($row['POST_DATE']));
		$post->appendChild($time);

    // Add the Facebook User name attribute UserName=""
    $user_details = $facebook->api_client->users_getInfo($row['User_Ptr'], 'last_name, first_name, pic_square');
    $post_userName = $doc->createAttribute('UserName');
    $post->appendChild($post_userName);
    $post_userName_text = $doc->createTextNode( $user_details[0]['first_name'] . ' ' . $user_details[0]['last_name'] );
    $post_userName->appendChild($post_userName_text );

    // Add the facebook profile pic url
    $post_pic = $doc->createAttribute('PicURL');
    $post->appendChild($post_pic);
    $post_picURL = $doc->createTextNode( $user_details[0]['pic_square'] );
    $post_pic->appendChild($post_picURL );

		// Add the post body
		$post->appendChild($doc->createTextNode($row['BODY']));
		
		// Append the post to wall_posts
		$wall_posts->appendChild($post);
	}
	
	$doc->appendChild($wall_posts);
	
	closeDB($result,$conn);
	
	return stripslashes($doc->saveXML());
}

/**
 * This function returns the result of adding a study_group bulletin board post to a
 * a study_group's bulletin board.
 * @version 1.0
 * @param integer $user_id user ID number
 * @param integer $study_group_id study_group ID number
 * @param string  $header title of the post
 * @param string  $body content of the post
 * @param integer $parentID parent post of the thread (could be null)
 * @return XML adding of study_group bulletin board post data
 */
function addStudyGroupBBSPostDAL ($user_id, $study_group_id, $header, $body, $parentID)
{
  $conn = openDB();

  if($parentID == null)
  {
    $parentID = 'null';
  }

  // alter passed variables for mysql injections
  $header = mysql_real_escape_string( $header );
  $body = mysql_real_escape_string( $body );

  // Add the user from a given course's study_group.
  $query = "Insert Into StudyGroupBBS (StudyGroupBBS.User_Ptr, " .
                                      "StudyGroupBBS.SG_Ptr, " .
 	                               "StudyGroupBBS.Header, " .
                                      "StudyGroupBBS.Body, " .
                                      "StudyGroupBBS.Post_Date, " .
                                      "StudyGroupBBS.Prev_Post_Ptr " .
                        ") Values ("   . $user_id      . ", " .
	                                  $study_group_id   . ", " .
                                   "'" . $header       . "', " .
                                   "'" . $body         . "', " .
	                            "'" . date("Y-m-d H:i:s") . "', " .
                                         $parentID     . "); ";

  $result = mysql_query($query);

  closeDB (null, $conn);

  return $result;
}


/**
 * This function gets all of the BBS thread topics for a given study_group.
 * @version 3.0
 * @param integer $study_group_id study_group ID number
 * @return XML BBS thread topics of study_group bulletin board post data, integer $return_latest return latest X records 
 */
function getStudyGroupBBSTopicsDAL ($study_group_id, $latest_posts = 0)
{
  $conn = openDB();

  if ($study_group_id == null)
  {
    return 'null';
  }

  // Only select the latest X posts.
  // Add the user from a given course's study_group.
  if ($latest_posts <> 0) {
    
    $query = "Select  * " . 
	      "From StudyGroupBBS " .
             "Where (StudyGroupBBS.REMOVAL_DATE Is Null) And " .
		     "(StudyGroupBBS.Prev_Post_Ptr Is Null) And " .
                   "(StudyGroupBBS.SG_Ptr = " . $study_group_id . " ) And " .
         "(StudyGroupBBS.User_Ptr IS NOT NULL) " .
	      "Order By ID Desc " . 
             "Limit 0," . $latest_posts . ";";
  }
  else {
   
    $query = "Select * " . 
	      "From StudyGroupBBS " .
             "Where (StudyGroupBBS.REMOVAL_DATE Is Null) And " .
		     "(StudyGroupBBS.Prev_Post_Ptr Is Null) And " .
         "(StudyGroupBBS.SG_Ptr = " . $study_group_id . " );";
  }


  $result = mysql_query($query);

  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);

  $EndResult = $doc->createElement("StudyGroupBBSTopics");
  $doc->appendChild($EndResult);

  while ($row = mysql_fetch_assoc($result)) {

    // create the StudyGroupBBSTopic Tag <StudyGroupBBSTopic>
    $study_groupBBSTopic = $doc->createElement('StudyGroupBBSTopic');
    $EndResult->appendChild($study_groupBBSTopic);

    // Add the Id attribute Id=""
    $study_groupBBSTopic_id = $doc->createAttribute('Id');
    $study_groupBBSTopic->appendChild($study_groupBBSTopic_id);
    $study_groupBBSTopic_id_text = $doc->createTextNode($row['ID']);
    $study_groupBBSTopic_id->appendChild($study_groupBBSTopic_id_text);

    // Add the PostDate attribute PostDate=""
    $study_groupBBSTopic_date = $doc->createAttribute('PostDate');
    $study_groupBBSTopic->appendChild($study_groupBBSTopic_date);
    $study_groupBBSTopic_date_text = $doc->createTextNode($row['POST_DATE']);
    $study_groupBBSTopic_date->appendChild($study_groupBBSTopic_date_text);

    // Add the UserId attribute UserId=""
    $study_groupBBSTopic_user = $doc->createAttribute('UserId');
    $study_groupBBSTopic->appendChild($study_groupBBSTopic_user);
    $study_groupBBSTopic_user_text = $doc->createTextNode($row['User_Ptr']);
    $study_groupBBSTopic_user->appendChild($study_groupBBSTopic_user_text);

    // Add the StudyGroupId attribute StudyGroupId
    $study_groupBBSTopic_study_group = $doc->createAttribute('StudyGroupId');
    $study_groupBBSTopic->appendChild($study_groupBBSTopic_study_group);
    $study_groupBBSTopic_study_group_text = $doc->createTextNode($row['SG_Ptr']);
    $study_groupBBSTopic_study_group->appendChild($study_groupBBSTopic_study_group_text);

    // Fill in the topic study_groupBBSTopic (subject) <>study_groupBBSTopic</>
    $study_groupBBSTopic_text = $doc->createTextNode($row['HEADER']);
    $study_groupBBSTopic->appendChild($study_groupBBSTopic_text);
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
function getStudyGroupBBSPostsDAL ($parentId, $facebook)
{
  $conn = openDB();

  // Query for threads that are either the parent thread or decend from the
  //  parent thread
  $query = "Select * " . 
	    "From StudyGroupBBS " .
           "Where (StudyGroupBBS.Removal_Date Is Null) And " .
                 "((StudyGroupBBS.Prev_Post_Ptr = " . $parentId . ") Or " .
                  "(StudyGroupBBS.ID = " . $parentId . " ));";

  $result = mysql_query($query);

  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $study_groupBBSThread = $doc->createElement('StudyGroupBBSThread');
  $doc->appendChild($study_groupBBSThread);

  while ($row = mysql_fetch_assoc($result)) {

    // Attach header attribute to StudyGroupBBSThread from the first post.
    if (!$row['Prev_Post_Ptr'])
    {
      $study_groupBBSThread_header = $doc->createAttribute('Header');
      $study_groupBBSThread->appendChild($study_groupBBSThread_header);
      $study_groupBBSThread_header_text = $doc->createTextNode($row['HEADER']);
      $study_groupBBSThread_header->appendChild($study_groupBBSThread_header_text);
    }

    if( $row['HEADER'] == '#STUDY GROUP WALL#' )
    {
    }
    else
    {
      // create the StudyGroupBBSTopic Tag <StudyGroupBBSTopic>
      $study_groupBBSPost = $doc->createElement('StudyGroupBBSPost');
      $study_groupBBSThread->appendChild($study_groupBBSPost);

      // Add the Id attribute Id=""
      $study_groupBBSPost_id = $doc->createAttribute('Id');
      $study_groupBBSPost->appendChild($study_groupBBSPost_id);
      $study_groupBBSPost_id_text = $doc->createTextNode($row['ID']);
      $study_groupBBSPost_id->appendChild($study_groupBBSPost_id_text);

      // Add the PostDate attribute PostDate=""
      $study_groupBBSPost_date = $doc->createAttribute('PostDate');
      $study_groupBBSPost->appendChild($study_groupBBSPost_date);
      $study_groupBBSPost_date_text = $doc->createTextNode($row['POST_DATE']);
      $study_groupBBSPost_date->appendChild($study_groupBBSPost_date_text);

      // Add the UserId attribute UserId=""
      $study_groupBBSPost_user = $doc->createAttribute('UserId');
      $study_groupBBSPost->appendChild($study_groupBBSPost_user);
      $study_groupBBSPost_user_text = $doc->createTextNode($row['User_Ptr']);
      $study_groupBBSPost_user->appendChild($study_groupBBSPost_user_text);

      // Add the StudyGroupId attribute StudyGroupId
      $study_groupBBSPost_study_group = $doc->createAttribute('StudyGroupId');
      $study_groupBBSPost->appendChild($study_groupBBSPost_study_group);
      $study_groupBBSPost_study_group_text = $doc->createTextNode($row['SG_Ptr']);
      $study_groupBBSPost_study_group->appendChild($study_groupBBSPost_study_group_text);

      // Add the Facebook User name attribute UserName=""
      $user_details = $facebook->api_client->users_getInfo($row['User_Ptr'], 'last_name, first_name, pic_square');
      $study_groupBBSPost_userName = $doc->createAttribute('UserName');
      $study_groupBBSPost->appendChild($study_groupBBSPost_userName);
      $study_groupBBSPost_userName_text = $doc->createTextNode( $user_details[0]['first_name'] . ' ' . $user_details[0]['last_name'] );
      $study_groupBBSPost_userName->appendChild($study_groupBBSPost_userName_text );

      // Add the facebook profile pic url
      $study_groupBBSPost_pic = $doc->createAttribute('PicURL');
      $study_groupBBSPost->appendChild($study_groupBBSPost_pic);
      $study_groupBBSPost_picURL = $doc->createTextNode( $user_details[0]['pic_square'] );
      $study_groupBBSPost_pic->appendChild($study_groupBBSPost_picURL );

      // Fill in the Post study_groupBBSPost (subject) <>study_groupBBSPost</>
      $study_groupBBSPost_text = $doc->createTextNode($row['BODY']);
      $study_groupBBSPost->appendChild($study_groupBBSPost_text);
    }
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
function removeStudyGroupBBSDAL ($id)
{
    
  $conn = openDB();
 
  $query = "Update StudyGroupBBS Set " . 
                  "StudyGroupBBS.Removal_Date = '" . date("Y-m-d") . "' " .

	    "Where (StudyGroupBBS.ID = " . $id . ");";	

  $result = mysql_query($query);



  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $EndResult = $doc->createElement('RemoveStudyGroupBBSResult');
  $doc->appendChild($EndResult);


  $RemoveStudyGroupBBSResult= $doc->createElement('RemoveStudyGroupBBSResult');
  $doc->appendChild($RemoveStudyGroupBBSResult);

  $RemoveStudyGroupBBS_Name = $doc->createTextNode($result);
  $RemoveStudyGroupBBSResult->appendChild($RemoveStudyGroupBBS_Name);
   

  $out = $doc->saveXML();

  return $out;
}


/**
 * This function adds a note posting and physical upload location.
 * @author Joseph Trapani
 * @version 1.0
 * @param integer $user_id userID number, integer $study_group_id study group ID, varchar $header header post title, varchar $body message body, varchar $file_path path to uploaded file,  varchar $original_file_name original file name, float $file_size size of file (in MB)
 */
function addStudyGroupNoteDAL ($user_id, $study_group_id, $header, $body, $file_path, $original_file_name, $file_size)
{
  $conn = openDB();

  // Alter passed variables for mysql injections
  $header = mysql_real_escape_string( $header );
  $body = mysql_real_escape_string( $body );
  $file_path = mysql_real_escape_string( $file_path);


  $query = "Insert Into StudyGroupNotes (User_Ptr, " .
					     "SG_Ptr, " .
					     "Post_Date, " . 
					     "Header, " .
					     "Body, " . 
					     "Path, " .	
  					     "Original_File_Name, " . 
					     "File_Size" . 
					    ") Values (" . 
					     $user_id . ", " . 
		                          $study_group_id . ", '" . 
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
  $EndResult = $doc->createElement('getStudyGroupNoteResult');
  $doc->appendChild($EndResult);


  $getStudyGroupNoteResult = $doc->createElement('getStudyGroupNoteResult');
  $doc->appendChild($getStudyGroupNoteResult);

  $getStudyGroupNote_Name = $doc->createTextNode($result);
  $getStudyGroupNoteResult->appendChild($getStudyGroupNote_Name);
   

  $out = $doc->saveXML();

  return $out;

}

/**
 * This function returns a note posting with the physical file location.
 * @author Joseph Trapani
 * @version 2.0
 * @param integer $study_group_id study group ID, integer $noteid unique ID number of posted note, integer $return_latest return latest X records 
 */
function getStudyGroupNoteDAL ($study_group_id, $noteid = 0, $latest_posts = 0)
{
  $conn = openDB();
  

  // Allow the function to return a specific post OR a group of posts for a class.
  $WhereClause = "Where (StudyGroupNotes.Removal_Date Is Null) And " .
			  "(StudyGroupNotes.SG_Ptr = " . $study_group_id . ")";

  if ($noteid <> 0) { 
    $WhereClause = $WhereClause . " And (ID = " . $noteid . ") ";
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
   	
  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $StudyGroupNote = $doc->createElement('getStudyGroupNotes');
  $doc->appendChild($StudyGroupNote);


  while ($row = mysql_fetch_assoc($result)) {
  
    $getStudyGroupNote = $doc->createElement('getStudyGroupNote');
    $StudyGroupNote->appendChild($getStudyGroupNote);


    $id_attr = $doc->createAttribute('Id');
    $getStudyGroupNote->appendChild($id_attr);
   
    $id_text = $doc->createTextNode($row['ID']);
    $id_attr->appendChild($id_text);


    $user_attr = $doc->createAttribute('User_ID');
    $getStudyGroupNote->appendChild($user_attr);
   
    $user_text = $doc->createTextNode($row['User_Ptr']);
    $user_attr->appendChild($user_text);


    $StudyGroup_attr = $doc->createAttribute('StudyGroupId');
    $getStudyGroupNote->appendChild($StudyGroup_attr);
   
    $StudyGroup_text = $doc->createTextNode($row['SG_Ptr']);
    $StudyGroup_attr->appendChild($StudyGroup_text);


    $header_attr = $doc->createAttribute('Header');
    $getStudyGroupNote->appendChild($header_attr);
   
    $header_text = $doc->createTextNode($row['HEADER']);
    $header_attr->appendChild($header_text);


    $body_attr = $doc->createAttribute('Body');
    $getStudyGroupNote->appendChild($body_attr);
   
    $body_text = $doc->createTextNode($row['BODY']);
    $body_attr->appendChild($body_text);


    $originalfilename_attr = $doc->createAttribute('Original_File_Name');
    $getStudyGroupNote->appendChild($originalfilename_attr);
   
    $originalfilename_text = $doc->createTextNode($row['ORIGINAL_FILE_NAME']);
    $originalfilename_attr->appendChild($originalfilename_text);


    $filesize_attr = $doc->createAttribute('File_Size');
    $getStudyGroupNote->appendChild($filesize_attr);
   
    $filesize_text = $doc->createTextNode($row['File_Size']);
    $filesize_attr->appendChild($filesize_text);


    $Server_Path_attr = $doc->createAttribute('Server_Path');
    $getStudyGroupNote->appendChild($Server_Path_attr);
   
    $filesize_text = $doc->createTextNode($row['PATH']);
    $filesize_attr->appendChild($filesize_text);



    $getStudyGroupNote_Name = $doc->createTextNode("http://noteshare.homelinux.net/" . substr ($row['PATH'], 53));
    $getStudyGroupNote->appendChild($getStudyGroupNote_Name);  

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
function removeStudyGroupNoteDAL ($id)
{
    
  $conn = openDB();
 
  $query = "Update StudyGroupNotes Set " . 
                  "StudyGroupNotes.Removal_Date = '" . date("Y-m-d") . "' " .

	    "Where (StudyGroupNotes.ID = " . $id . ");";	

  $result = mysql_query($query);



  $doc = new DOMDocument('1.0');

  $style = $doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="test.xsl"');
  $doc->appendChild($style);
  $EndResult = $doc->createElement('RemoveStudyGroupNoteResult');
  $doc->appendChild($EndResult);


  $RemoveStudyGroupNoteResult= $doc->createElement('RemoveStudyGroupNoteResult');
  $doc->appendChild($RemoveStudyGroupNoteResult);

  $RemoveStudyGroupNote_Name = $doc->createTextNode($result);
  $RemoveStudyGroupNoteResult->appendChild($RemoveStudyGroupNote_Name);
   

  $out = $doc->saveXML();


  // Remove the note from the server.
  $query = "Select StudyGroupNotes.PATH " . 
 	    "From StudyGroupNotes " . 
	    "Where (StudyGroupNotes.ID = " . $id . ");";	

  $result = mysql_query($query);

  while($row = mysql_fetch_assoc($result)) {
   
    If ($row['PATH'] <> '') {
    
      
        unlink($row['PATH']);

    }

  }

  closeDB ($result, $conn);


  return $out;
}


?>
