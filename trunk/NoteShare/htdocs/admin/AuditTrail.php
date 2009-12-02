<?php

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





// ----------------------- PROCESS POST --------------------------

$message = "";


// If the user performed a submission 
if (isset ($_POST[UserSelection])) {

  // Grab the user ID
  $user = $_POST[UserSelection];



  $message = "<P />Display the list of joined sessions/study groups...";

  $conn = openDB();


  // Generate a list of the NoteShareSEP User's enrolled Sessions & Study Groups
  $query = "Select University.Name As uname, Department.Name As dname, Course.Name As cname, Session.Name As sname, StudyGroup.Name As sgname " . 
	    "From SessionEnrollment " .
	    "Inner Join Session On (Session.ID = SessionEnrollment.Session_Ptr) And " .
                                 "(SessionEnrollment.User_Ptr = " . $user . ")" .   
	    "Inner Join Course On (Course.ID = Session.Course_Ptr) " . 
	    "Inner Join Department On (Department.ID = Course.Department_Ptr) " . 
           "Inner Join University On (University.ID = Department.University_Ptr) " .
  	    "Left Outer Join StudyGroupEnrollment On (StudyGroupEnrollment.User_Ptr = SessionEnrollment.User_Ptr) " .
	    "Left Outer Join StudyGroup On (StudyGroup.ID = StudyGroupEnrollment.SG_Ptr);"; 

  $result = mysql_query($query);

  // Output the header.
  $message = $message . "<TABLE><TR><TD><B><Font Color = \"#00611C\">University</Font></B></TD><TD><B><Font Color = \"#00CD00\">Department</Font></B></TD><TD><B><Font Color = \"#00FF66\">Course</Font></B></TD><TD><B><Font Color = \"#2C5D3F\">Session</Font></B></TD><TD><B><Font Color = \"#629632\">Study Group</Font></B></TD></TR>";
  
  // Output the data.
  while($row = mysql_fetch_assoc($result)) {
    $message = $message . "<TR><TD><Font Color = \"#00611C\">" . $row['uname'] ."</Font></TD><TD><Font Color = \"#00CD00\">" .  $row['dname'] . "</Font></TD><TD><Font Color = \"#00FF66\">" . $row['cname'] . "</Font></TD><TD><Font Color = \"#2C5D3F\">" . $row['sname'] . "</Font></TD><TD><Font Color = \"#629632\">" . $row['sgname'] . "</Font></TD></TR>";
  }

  $message = $message . "</TABLE>";

  closeDB ($result, $conn);  






  $message = $message . "<P />Display the lists of session/study group BBS posts...";

  $conn = openDB();


  // Generate a list of the NoteShareSEP User's BBS posts Sessions & Study Groups
  $query = "Select   Course.Name  As cname, " .
		      "Session.Name As sname, " .
 		      "SessionBBS.* " .
	     "From SessionBBS " .
            "Inner Join Session On (Session.ID = SessionBBS.Session_Ptr) And " .
                                  "(SessionBBS.User_Ptr = " . $user . ") " . 
            "Inner Join Course On (Course.ID = Session.Course_Ptr);";


  $result = mysql_query($query);

  // Output the header.
  $message = $message . "<P /><P /><Table><TR><TD><B><Font Color = \"#00611C\">Session</Font></B></TD><TD><B><Font Color = \"#00CD00\">Header</Font></B></TD><TD><B><Font Color = \"#00FF66\">Body</Font></B></TD><TD><B><Font Color = \"#2C5D3F\">Post Date</Font></B></TD><TD><B><Font Color = \"#629632\">Removal Date</Font></B></TD></TR>";
 
  // Output the data.
  while($row = mysql_fetch_assoc($result)) {
    $message = $message . "<TR><TD><BR /><Font Color = \"#00611C\">" . $row['cname'] . "-" . $row['sname'] . "</Font></TD><TD><Font Color = \"#00CD00\">" .  $row['HEADER'] . "</Font></TD><TD><Font Color = \"#00FF66\">" . $row['BODY'] . "</Font></TD><TD><Font Color = \"#2C5D3F\">" . $row['POST_DATE'] . "</Font></TD><TD><Font Color = \"#629632\">" . $row['REMOVAL_DATE'] . "</Font></TD></TR>";
  }

  $message = $message . "</TABLE>";

  closeDB ($result, $conn);




  $conn = openDB();


  // Generate a list of the NoteShareSEP User's BBS posts Sessions & Study Groups
  $query = "Select   Course.Name     As cname, " .
                    "Session.Name    As sname, " .  
		      "StudyGroup.Name As sgname, " .
 		      "StudyGroupBBS.* " .
	     "From StudyGroupBBS " .
            "Inner Join StudyGroup On (StudyGroup.ID = StudyGroupBBS.SG_Ptr) And " .
                                     "(StudyGroupBBS.User_Ptr = " . $user . ") " . 
            "Inner Join Session On (Session.ID = StudyGroup.Session_Ptr) " .
            "Inner Join Course On (Course.ID = Session.Course_Ptr);";


  $result = mysql_query($query);

  // Output the header.
  $message = $message . "<P /><P /><Table><TR><TD><B><Font Color = \"#00611C\">Study Group</Font></B></TD><TD><B><Font Color = \"#00CD00\">Header</Font></B></TD><TD><B><Font Color = \"#00FF66\">Body</Font></B></TD><TD><B><Font Color = \"#2C5D3F\">Post Date</Font></B></TD><TD><B><Font Color = \"#629632\">Removal Date</Font></B></TD></TR>";
 
  // Output the data.
  while($row = mysql_fetch_assoc($result)) {
    $message = $message . "<TR><TD><BR /><Font Color = \"#00611C\">" . $row['cname'] . "-" . $row['sname'] . "-" . $row['sgname'] . "</Font></TD><TD><Font Color = \"#00CD00\">" .  $row['HEADER'] . "</Font></TD><TD><Font Color = \"#00FF66\">" . $row['BODY'] . "</Font></TD><TD><Font Color = \"#2C5D3F\">" . $row['POST_DATE'] . "</Font></TD><TD><Font Color = \"#629632\">" . $row['REMOVAL_DATE'] . "</Font></TD></TR>";
  }

  $message = $message . "</TABLE>";
  
  closeDB ($result, $conn);




  $message = $message . "<P />Display the lists of session/study group note posts...";

  $conn = openDB();


  // Generate a list of the NoteShareSEP User's note posts Sessions & Study Groups
  $query = "Select   Course.Name  As cname, " .
		      "Session.Name As sname, " .
 		      "SessionNotes.* " .
	     "From SessionNotes " .
            "Inner Join Session On (Session.ID = SessionNotes.Session_Ptr) And " .
                                  "(SessionNotes.User_Ptr = " . $user . ") " . 
            "Inner Join Course On (Course.ID = Session.Course_Ptr);";


  $result = mysql_query($query);

  // Output the header.
  $message = $message . "<P /><P /><Table><TR><TD><B><Font Color = \"#00611C\">Session</B></Font></TD><TD><B><Font Color = \"#00CD00\">Header</Font></B></TD><TD><B><Font Color = \"#00FF66\">Body</Font></B></TD><TD><B><Font Color = \"#2C5D3F\">Post Date</Font></B></TD><TD><B><Font Color = \"#629632\">Removal Date</Font></B></TD><TD><B><Font Color = \"#7FFF00\">Original File Name</Font></B></TD><TD><B><Font Color = \"#AADD00\">File Size</Font></B></TD><TD><B><Font Color = \"#8B7500\">Good/Bad Rating</Font></B></TD></TR>";
 
  // Output the data.
  while($row = mysql_fetch_assoc($result)) {
    $message = $message . "<TR><TD><Font Color = \"#00611C\">" . $row['cname'] . "-" . $row['sname'] . "</Font></TD><TD><Font Color = \"#00CD00\">" .  $row['HEADER'] . "</Font></TD><TD><Font Color = \"#00FF66\">" . $row['BODY'] . "</Font></TD><TD><Font Color = \"#2C5D3F\">" . $row['POST_DATE'] . "</Font></TD><TD><Font Color = \"#629632\">" . $row['REMOVAL_DATE'] . "</Font></TD><TD><Font Color = \"#7FFF00\">" . $row['ORIGINAL_FILE_NAME'] . "</Font></TD><TD><Font Color = \"#AADD00\">" . $row['FILE_SIZE'] . "</Font></B></TD><TD><B><Font Color = \"#8B7500\">" . $row['GOOD_RATING'] . "/ " . $row['BAD_RATING'] . "</Font></B></TD></TR>";
 }

  $message = $message . "</TABLE>";

  closeDB ($result, $conn);




  $conn = openDB();


  // Generate a list of the NoteShareSEP User's BBS posts Sessions & Study Groups
  $query = "Select   Course.Name     As cname, " .
                    "Session.Name    As sname, " .  
		      "StudyGroup.Name As sgname, " .
 		      "StudyGroupNotes.* " .
	     "From StudyGroupNotes " .
            "Inner Join StudyGroup On (StudyGroup.ID = StudyGroupNotes.SG_Ptr) And " .
                                     "(StudyGroupNotes.User_Ptr = " . $user . ") " . 
            "Inner Join Session On (Session.ID = StudyGroup.Session_Ptr) " .
            "Inner Join Course On (Course.ID = Session.Course_Ptr);";


  $result = mysql_query($query);

  // Output the header.
  $message = $message . "<P /><P /><Table><TR><TD><B><Font Color = \"#00611C\">Study Group</B></Font></TD><TD><B><Font Color = \"#00CD00\">Header</Font></B></TD><TD><B><Font Color = \"#00FF66\">Body</Font></B></TD><TD><B><Font Color = \"#2C5D3F\">Post Date</Font></B></TD><TD><B><Font Color = \"#629632\">Removal Date</Font></B></TD><TD><B><Font Color = \"#7FFF00\">Original File Name</Font></B></TD><TD><B><Font Color = \"#AADD00\">File Size</Font></B></TD><TD><B><Font Color = \"#8B7500\">Good/Bad Rating</Font></B></TD></TR>";
 
  // Output the data.
  while($row = mysql_fetch_assoc($result)) {
    $message = $message . "<TR><TD><Font Color = \"#00611C\">" . $row['cname'] . "-" . $row['sname'] . "-" . $row['sgname'] ."</Font></TD><TD><Font Color = \"#00CD00\">" .  $row['HEADER'] . "</Font></TD><TD><Font Color = \"#00FF66\">" . $row['BODY'] . "</Font></TD><TD><Font Color = \"#2C5D3F\">" . $row['POST_DATE'] . "</Font></TD><TD><Font Color = \"#629632\">" . $row['REMOVAL_DATE'] . "</Font></TD><TD><Font Color = \"#7FFF00\">" . $row['ORIGINAL_FILE_NAME'] . "</Font></TD><TD><Font Color = \"#AADD00\">" . $row['FILE_SIZE'] . "</Font></B></TD><TD><B><Font Color = \"#8B7500\">" . $row['GOOD_RATING'] . "/ " . $row['BAD_RATING'] . "</Font></B></TD></TR>";
 }

  $message = $message . "</TABLE>";

  closeDB ($result, $conn);



}
  




// ------------------------- HTML FORM ----------------------------

echo '<HTML>';
echo '<BODY>';



echo '<form action="'.$_SERVER[PHP_SELF].'" method="POST">';

echo 'Select User: <select name="UserSelection">';
  
  $conn = openDB();

  // Generate a list of all NoteShareSEP Users
  $query = "Select User_ID From User"; 

  $result = mysql_query($query);


  while($row = mysql_fetch_assoc($result)) {
echo  $row['User_ID'];
    echo '<option value="'.$row['User_ID'].'">'.$row['User_ID'].'</option>'; 

  }

  closeDB ($result, $conn);

  


echo '</select>';



// Use a scrollable div tag to ensure all the text can be viewed on the page.  
echo '<input type="submit" value="Query NoteShare" />';
echo '</form>';


echo '<P />';
echo '<P />';
echo '<P />';
echo '<P />';



echo '<div style="overflow:auto; height:600px">';

// Print the message string 
echo $message;

echo '</div>';




echo '</BODY>';
echo '</HTML>';

?>

