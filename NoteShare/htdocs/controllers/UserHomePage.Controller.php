<?php

include '/var/www/localhost/htdocs/model/NoteshareDatabase.php';

function GetCourseList()
{
	//Called from the DAL
	//Facebook API needed!
	return getHomePageSessionList (66000948);

}

function GetCourseListXML( $userid )
{
	//Called from the DAL
	//Facebook API needed!

  // Why is the facebook api needed?
	return "<?xml version=\"1.0\"?>
	<sessionList>
	<session id=\"11111\" 
		course=\"Software Engineering Project\"
		department=\"Electrical and Computer Engineering\"
		university=\"University of Iowa\">
		Fall 2009
	</session>
	<session id=\"22222\" 
		course=\"Web Programming\"
		department=\"Computer Science\"
		university=\"University of Iowa\">
		Fall 2009
	</session>
	<session id=\"33333\"
		course=\"Distributed System\"
		department=\"Computer Science\"
		university=\"University of Iowa\">
		Fall 2009
	</session>
  </sessionList>";
}

//getSessions(1);

//GetCourseList();

?>
