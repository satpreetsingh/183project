<?php
  include_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Controller.php';
  include_once $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';

  $AllowedExts     = array ("TXT", "PDF", "DOC", "DOCX", "XLS", "XLSX", "PPT", "PPTX", "JPEG", "JPG", "ZIP", "PS", "PNG", "DVI");
  $AllowedExtTypes = array ("text/plain", "application/pdf", "application/x-pdf", "application/doc", "application/docx",
                            "application/xls", "application/xlsx", "application/msexcel", "application/vnd.ms-excel",
                            "application/excel", "application/vnd.x-msexcel", "application/msword", "application/vnd.msword",
                            "application/vnd.ms-word", "application/word", "application/x-msword",
                            "application/mspowerpoint", "application/vnd.ms-powerpoint", "application/powerpoint", "application/vnd.x-mspowerpoint",
                            "application/octet-stream", "image/jpeg", "application/zip", "application/postscript", "image/png", "application/x-dvi");

  /**
   * Retrieves all of the note posts for the specified session
   *
   * @param integer $sessionId identifier for which session's posts to grab
   * @return XML of the posts
  **/
  function getSessionNotePosts ($sessionId)
  {
    return getSessionNoteDAL ($sessionId);
  }

  /** Post reply **/
  // Check that we have a file to upload.
  if ((!empty ($_FILES["uploaded_file"])) &&
      ($_FILES['uploaded_file']['error'] == 0)) {

    // $sep_type = 0 = Session; 1 = Study Group
    $sep_type = $_POST['sep_type'];
    $session_id = $_POST['ns_session'];
    $study_group_id = 0;

    if ($sep_type == 1) {
      $study_group_id = $_POST['ns_study_group'];
    } 
 

    $body = $_POST['body'];
    $header = $_POST['header'];
    $user_id = $_POST['userId'];

    // Get the filename, and grab the extension.
    $filename    = basename ($_FILES['uploaded_file']['name']);
    $ext         = strtoupper (substr ($filename, (strrpos ($filename, '.') + 1)) );
	


    // If the ext, Mime type, and size requirements (< 5MB) are met, then proceed on uploading the file.
    if ((strlen (array_search ($ext, $AllowedExts)) > 0) &&
        (strlen (array_search ($_FILES["uploaded_file"]["type"], $AllowedExtTypes)) > 0) &&
        ($_FILES["uploaded_file"]["size"] < 5242881)) {

      // Determine the path to save this file, the first being for a session.
      if ($study_group_id == 0) {
        $newname = '/home/fate/SEPRepository/183project/NoteShare/htdocs/noteFiles/' . $user_id . $session_id . date("YmdHis");
      }
      else { 
        $newname = '/home/fate/SEPRepository/183project/NoteShare/htdocs/noteFiles/GroupnoteFiles/' . $user_id . $study_group_id . date("YmdHis");;    
      }




      $index = 0;

      // Find the next unallocated filename for the file.
      while (file_exists ($newname . $index)) {
        $index++;
      }



      // Attempt to move the uploaded file to it's new location within the noteFiles directory.
      if ((move_uploaded_file ($_FILES['uploaded_file']['tmp_name'], $newname . $index . $ext))) {
        

        // Session notes call the session DAL function.
        if ($study_group_id == 0) { 
          addSessionNoteDAL ($user_id, $session_id, $header, $body, $newname . $index . $ext, $filename, $_FILES["uploaded_file"]["size"]);
        }
        else {
          addStudyGroupNoteDAL ($user_id, $study_group_id, $header, $body, $newname . $index . $ext, $filename, $_FILES["uploaded_file"]["size"]);
        } 


      }
      else {
        echo '<script type="text/javascript">';
        echo ' alert( "Error: A problem occurred during file upload!" );';
        echo '</script>';
      }

    }
    else {
        echo '<script type="text/javascript">';
        echo ' alert( "Error: Either your file format is not supported or your file exceeds 5 MB." );';
        echo '</script>';
    }


    // Redirect the user back to the CoursePage (session) or GroupPage (study_group)
    if ($study_group_id == 0) { 
      $facebook->redirect( "http://apps.facebook.com/notesharesep/views/CoursePage.php?ns_session=" . $session_id );
    }
    else {
      $facebook->redirect( "http://apps.facebook.com/notesharesep/views/GroupPage.php?ns_session=" . $session_id . "&nsStudyGroup=" . $study_group_id);
    }
  }
?>

