<?php
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
?>
