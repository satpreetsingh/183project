<?php

  include $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';

function getSessionMetadata( $sessionid )
{
	return getSessionMetadataDAL( $sessionid );
  /*"<?xml version=\"1.0\"?>
	
	<sessionMetadata id=\"11111\" 
		course=\"Software Engineering Project\"
		department=\"Electrical and Computer Engineering\"
		university=\"University of Iowa\">
		This course is required for the SE Subtrack
	</sessionMetadata>
	
	";*/
}

function getSessionMembers( $userid, $sessionid )
{
// There'll probably be some changes here as we figure 
// out how to show friend thumbnail photos etc.

	return "<?xml version=\"1.0\"?>	
	
	<memberList>
		<member id=\"11111\" 
			friend=\"True\">
			Joe Trapani
		</member>
		<member id=\"11211\" 
			friend=\"True\">
			Nathan D!
		</member>
		<member id=\"12221\" 
			friend=\"True\">
			Nathan F!
		</member>
		<member id=\"12121\" 
			friend=\"False\">
		    Jack Black
		</member>	
	</memberList>
	";
}

function removeSession($sessionID)
{
//Is this ok?

    return "
	<?xml version=\"1.0\"?>	    
	<response>You have been removed from the course...</response>
	";
}

function getSessionBBSTopics( $sessionID )
{
  return getSessionBBSTopicsDAL( $sessionID );
}

function getSessionWall( $sessionID )
{
  return '' .
  '<?xml version="1.0" encoding="UTF-8"?>
   <SessionWall>
     <Post Header="Example Header"
           Date="11-09-09 12:00:01"
           User="14821122">
		   Test next week everyone!
     </Post>
		 <Comment Header="Example Header"
			   Date="11-09-09 12:00:02"
			   User="14821222">
			   Just 7 days!
		 </Comment>		
     <Post Header="Example Header2"
           Date="11-09-09 12:00:05"
           User="14821122">
		   Welcome to the new semester!
     </Post>
   </SessionWall>';
}

?>
