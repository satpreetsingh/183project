
<?php


// Hold the user ID-this would be passed in from the view.
$User_ID = 6000948;


$AllowedExts     = array ("TXT", "PDF", "DOC", "DOCX", "XLS", "XLSX", "PPT", "PPTX", "JPEG", "JPG", "ZIP", "PS", "PNG", "DVI");
$AllowedExtTypes = array ("text/plain", "application/pdf", "application/x-pdf", "application/doc", "application/docx", 
                          "application/xls", "application/xlsx", "application/msexcel", "application/vnd.ms-excel", 
                          "application/excel", "application/vnd.x-msexcel", "application/msword", "application/vnd.msword", 
                          "application/vnd.ms-word", "application/word", "application/x-msword", 
                          "application/mspowerpoint", "application/vnd.ms-powerpoint", "application/powerpoint", "application/vnd.x-mspowerpoint", "application/octet-stream", 
			     "image/jpeg", "application/zip", "application/postscript", "image/png", "application/x-dvi");  


// Check that we have a file to upload.
if ((!empty ($_FILES["uploaded_file"])) && 
    ($_FILES['uploaded_file']['error'] == 0)) {

  // Get the filename, and grab the extension.
  $filename    = basename ($_FILES['uploaded_file']['name']);
  $ext         = strtoupper (substr ($filename, strrpos ($filename, '.') + 1));
  
  // Set an advanced file name for storage on our server.
  $AdvFileName = $User_ID . date("YmdHis");
  

  // If the ext, Mime type, and size requirements (< 5MB) are met, then proceed on uploading the file.
  if ((strlen (array_search ($ext, $AllowedExts)) > 0) && 
      (strlen (array_search ($_FILES["uploaded_file"]["type"], $AllowedExtTypes)) > 0) && 
      ($_FILES["uploaded_file"]["size"] < 5242881)) {

    // Determine the path to which we want to save this file.
    $newname = '/home/fate/SEPRepository/183project/NoteShare/htdocs/noteFiles/' . $AdvFileName;
    //dirname (__FILE__) .
    
    $index = 0;

    // Find the next unallocated filename for the file. 
    while (file_exists ($newname . $index)) {
      $index++;
    }



    // Attempt to move the uploaded file to it's new location within the noteFiles directory.
    if ((move_uploaded_file ($_FILES['uploaded_file']['tmp_name'], $newname . $index))) {
         echo "It's done! The file has been saved as: " . $newname . $index;
    } 
    else {
      echo "Error: A problem occurred during file upload!";
    }
    
  } 
  else {
    echo "Error: Either your file format is not supported or your file exceeds 5 MB.";
  }
} 
else {
  echo "Error: No file to uploaded!";
}

?>