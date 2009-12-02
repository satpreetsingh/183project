<?php


/**
 * This function is designed to post a message to a session BBS
 * @author Jon Hall
 * @version 1.0
 * @param string $header, string $body, integer $user_id userID number, integer $session_id sessionID number, integer $prev_post id of parent post
 */
 
/* 
function addSessionBBSPostDAL( $header, $body, $user_id, $session_id, $prev_post)
{
	$conn = openDB();
	
	// Escape header and body
	$header = mysql_real_escape_string($header);
	$body = mysql_real_escape_string($body);
	
	$query = "Insert Into SessionBBS (Header, Body, Post_Date, User_ptr, Session_ptr, Prev_Post_ptr) " .
			"Values ('$header', '$body', '". date("Y-m-d H:i:s") ."', ". 
			$user_id . ", " . $study_group_id . ", " . $prev_post . ")";	
	mysql_query($query);
	mysql_close($conn);
}
*/

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
		  "Where ((Session_ptr = " . $session_id . ") And " .
			  "(User_ptr is Null) And " .
			  "(Header = '#SESSION WALL#'))";
			
	$result = mysql_query($query);
	
	// Check existance
	if( $row = mysql_fetch_assoc($result) )
	{
		$wall_parent = $row['ID'];
		mysql_free_result($result);
	}
	// No session wall parent
	else
	{
		// Insert one
		$query = "Insert into SessionBBS (Header, Body, Post_Date, Session_ptr) " .
				"Values ('#SESSION WALL#', 'Here be dragons', '". date("Y-m-d H:i:s") ."', " .$session_id . ")";
				
		mysql_query($query);
		
		// Get the Id that was generated
		$query = "Select ID From SessionBBS Where (Session_ptr='" . $session_id ." ' and " .
			"User_ptr is null and Header='#SESSION WALL#')";
			
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
 * @param integer $user_id userID number, integer $session_id sessionID number, string $body post contents
 */
function addSessionWallPostDAL($user_id, $session_id, $body)
{
  if( $user_id == null || $session_id == null || $body == null )
  {
    return -1;
  }

	$wall_parent = getSessionWallParentDAL($session_id);
  if( $wall_parent == null )
  {
    return -2;
  }

	addSessionBBSPostDAL($user_id, $session_id, '', $body, $wall_parent);
  return 0;
}

/**
 * This function is designed to get posts that are on a session wall
 * @author Jon Hall
 * @version 1.0
 * @param interger $session_id sessionID number
 * @return string XML WallPosts
 */
function getSessionWallPostsDAL($session_id, $limit = 5, $facebook )
{
	// Get parent
	$wall_parent = getSessionWallParentDAL($session_id);
	$conn = openDB();
	$query = "Select * " .
		  "From SessionBBS " .
 		  "Where (Session_Ptr = $session_id) And " .
            "(Prev_Post_Ptr = $wall_parent) " .
	    "Order By Post_Date Desc " .
	    "Limit 0," . $limit;
	
	$result =  mysql_query($query);
	echo mysql_error();
	$doc = new DOMDocument('1.0');
	$wall_posts = $doc->createElement("sessionWallPosts");
	while($row = mysql_fetch_assoc($result))
	{
		$post = $doc->createElement("post");
		
		// Add user attribute
		$user_id = $doc->createAttribute("user");
		$user_id->appendChild($doc->createTextNode($row['User_Ptr']));
		$post->appendChild($user_id);
		
		// Add time attribute
		//$date_Array = strptime($row['POST_DATE'],"%Y-%m-%d %H:%M:%S");

		//$time_Stamp = mktime($date_Array['tm_hour'], $date_Array['tm_min'],
		//		$date_Array['tm_sec'], $date_Array['tm_mon']+1, 
		//		$date_Array['tm_mday'], $date_Array['tm_year']+1900 );
				
		$time = $doc->createAttribute("time");
		$time->appendChild($doc->createTextNode($row['POST_DATE']));
		$post->appendChild($time);

    // get user facebook information
    $user_details = $facebook->api_client->users_getInfo($row['User_Ptr'], 'last_name, first_name, pic_square');

    // Add the Facebook User name attribute UserName=""
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
  // handel bogus values by escaping
  if( $user_id == NULL || $session_id == NULL || $body == NULL )
  {
    return NULL;
  }

  $conn = openDB();

  // alter parent id for mysql if necessary
  if($parentID == NULL )
  {
    $parentID = 'null';
  }

  // alter passed variables for mysql injections
  $header = mysql_real_escape_string( $header );
  $body = mysql_real_escape_string( $body );

  // Add the user from a given course's session.
  $query = "Insert Into SessionBBS (SessionBBS.User_Ptr, " .
                                   "SessionBBS.Session_Ptr, " .
 	                                 "SessionBBS.HEADER, " .
                                   "SessionBBS.BODY, " .
                                   "SessionBBS.POST_DATE, " .
                                   "SessionBBS.Prev_Post_Ptr " .
                   ") Values ("   . $user_id      . ", " .
	                                  $session_id              . ", " .
                              "'" . $header                  . "', " .
                              "'" . $body                    . "', " .
	                            "'" . date("Y-m-d H:i:s")      . "', " .
                                    $parentID                . "); ";

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
                   "(SessionBBS.Session_Ptr = " . $session_id . " ) And " .
                   "(SessionBBS.HEADER != \"#SESSION WALL#\" ) " .
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
function getSessionBBSPostsDAL ($parentId, $facebook)
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

    // Add the Facebook User name attribute UserName=""
    $user_details = $facebook->api_client->users_getInfo($row['User_Ptr'], 'last_name, first_name, pic_square');
    $sessionBBSPost_userName = $doc->createAttribute('UserName');
    $sessionBBSPost->appendChild($sessionBBSPost_userName);
    $sessionBBSPost_userName_text = $doc->createTextNode( $user_details[0]['first_name'] . ' ' . $user_details[0]['last_name'] );
    $sessionBBSPost_userName->appendChild($sessionBBSPost_userName_text );

    // Add the facebook profile pic url
    $sessionBBSPost_pic = $doc->createAttribute('PicURL');
    $sessionBBSPost->appendChild($sessionBBSPost_pic);
    $sessionBBSPost_picURL = $doc->createTextNode( $user_details[0]['pic_square'] );
    $sessionBBSPost_pic->appendChild($sessionBBSPost_picURL );

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
