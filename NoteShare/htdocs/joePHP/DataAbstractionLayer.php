<?php
Function c_GetCourseList ($userID)
{
	include 'config.php';
	include 'opendb.php';


	// Selected fields must be spelled in lowercase ALWAYS!
	$query  = "Select Course.name " .
			  "From sessionenrollment " . 
			  "Inner Join Session On Session.id = SessionEnrollment.session_ptr " . 
              "Inner Join Course On Course.id = Session.course_ptr " . 
			  "Where sessionenrollment.user_ptr = " . $userID;	

	$result = mysql_query($query);



	echo "You are enrolled in the following courses:";

	while($row = mysql_fetch_assoc($result))
	{
		echo "<BR />Name:  {$row['name']}";
	}

	//Free the result memory
	mysql_free_result($result);

	include 'closedb.php';

}
?>