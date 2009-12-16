<?php
  /**
   * SessionBBS.View.php
   * Displays the session bbs topic and associated posts.
  **/
	require_once $_SERVER['DOCUMENT_ROOT'] . 'views/View.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/NewNote.php';

  //--------------------------View Functions--------------------------------//

  /**
   * Generates the form for uploading new notes
   *
  **/
  function genNewNotes( $sessionId, $userId )
  {
    openEditor();
    genTextBox( "Subject:", header );
    echo '<input type="hidden" name="MAX_FILE_SIZE" value="26214400">';     
    echo '<input type="hidden" name="sep_type" value="0">';
    echo '<input type="hidden" name="ns_session" value="' . $sessionId . '">';
    echo '<input type="hidden" name="userId" value="' . $userId . '">';
 

    genTextArea( "Body:", body );
    genFileUpload();
    genButtons( $sessionId );
    closeEditor();
  }

  /**
   * Prints the initial opening tags for the new thread form.  Function posts
   * to controller for new thread creation.
   *
   * @version 1.0
   * @return prints open form tags, and starts form table
  **/
  function openEditor( )
  {

    echo '' .
    '<form enctype="multipart/form-data" action="http://noteshare.homelinux.net/controllers/NewNote.php" method="post" target="_top">
       <table class="formTable">';
  }

  function genTextBox( $label, $name )
  {
    echo '' .
    '  <tr>
        <th>
          <label class="fbFont large">' . $label . '</label>
        </th>
        <td class=""><input class="text" type="text" id="' . $name . '" name="' . $name . '"/></td>
          <td class="right_padding"></td>
        </tr>';
  }


  function genTextArea( $label, $name )
  {
    echo '' .
    '  <tr>
        <th>
          <label class="fbFont large">' . $label . '</label>
        </th>
        <td class=""><textarea class="fbFont" id="' . $name . '" name="' . $name . '"/></textarea>
          <td class="right_padding"></td>
        </tr>';
  }

  /**
   * Prints the closing submission button and cancel link.  This in turn
   * submits to the location specified by openEditor()
   *
   * @version 1.0
   * @return prints the closing submission buttons
  **/
  function genButtons( $sessionId )
  {
    echo '' .
    '  <tr>
         <th></th>
           <td class="editorkit_buttonset">
             <input class="submit add action" type="submit" onclick="return confirm( \'Do you agree to the terms of the disclaimer?\' );" value="Post Notes" name="Post Notes"/>
             <span class="cancel_link">
               <span>or</span>
               <a href="http://apps.facebook.com/notesharesep/views/CoursePage.php?ns_session=' . $sessionId . '" target="_top">Cancel</a>
             </span>
           </td>
           <td class="right_padding">
           </td>
       </tr>
    ';
  }

  function genFileUpload( )
  {
    echo '' .
    '  <tr>
        <th>
          <label class="fbFont large">Upload New Notes:</label>
        </th>
        <td ><input class="text" type="file" name="uploaded_file" /></td>
          <td class="right_padding"></td>
        </tr>';
  }

  /**
   * Closes the form table and the form itself.
   *
   * @version 1.0
   * @return prints close form table and close form tag
  **/
  function closeEditor()
  {
    echo '</table></form>';
  }
  //----------------------Begin View Code----------------------------------//

  genViewHeader( "SessionBBS View" );
  genPageHeader( array( "Main Page",
                        "Course View",
                        "New Thread" ),
                 array( "/views/UserHomePage.php", 
                        "/views/CoursePage.php?ns_session=" . $_GET['ns_session'],
                        "/views/NewThread.php?ns_session=" . $_GET['ns_session'] ));

  // Upload new Notes
  $sessionId = $_GET['ns_session'];
  genNewNotes( $sessionId, $user_id );

  echo "
    <h1>Disclaimer</h1>
    <p>Copyright law protects various types of original works of authorship
    and the reproduction of such works without the consent of the copyright 
    owner. NoteSharedoes not promote or in any way condone the use of our 
    application for such purposes.</p>

    <p>The use of this website and for such illegal purposes, including file 
    sharing of copyrighted material without, amongst other things, all 
    necessary permissions, is prohibited and is in breach of the Facebook 
    terms and conditions which you have accepted in using this site. You 
    should not assume that you have permission to share a file simply because 
    someone else has made it available online.</p>

    <p>Compliance with all relevant laws is your responsibility! NoteShare 
    maintains an audit trail, and will comply with local laws in cases where 
    a breach of copyright law is suspected. Please use this site and any 
    material on it responsibly.</p>

    <p>Some schools and professors have a policy against students discussing 
    coursework on online forums outside of the official school forums. Users 
    should check if there is anything in their school/course policy against 
    discussing class material or homework, or collaborating. We are not 
    affiliated with any educational institutions: we are a commercial 
    3rd-party venture.</p>

    <p>NoteShare reserves the right to delete any objectionable content 
    without notice.</p>
  ";

  // Close out page
  genViewFooter();
?>
