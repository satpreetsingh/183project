<?php
  /**
   * SessionBBS.View.php
   * Displays the session bbs topic and associated posts.
  **/
	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Session.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/NewThread.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . 'views/View.php';

  //--------------------------View Functions--------------------------------//

  function genNewBBSThread( $sessionId )
  {
    openEditor();
    genTextBox( "Subject:", header );
    echo '<input type="hidden" name="ns_session" value="' . $sessionId . '">';
    genTextArea( "Body:", post );
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
    '<form action="/controllers/NewThread.php" method="GET" target="iframe_canvas"
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
             <input class="submit" type="submit" class="add action" value="Post Thread" name="Post Thread"/>
             <span class="cancel_link">
               <span>or</span>
               <a href="/views/CoursePage.php?ns_session=' . $sessionId . '" target="iframe_canvas">Cancel</a>
             </span>
           </td>
           <td class="right_padding">
           </td>
       </tr>
    ';
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

  //Session BBS Table
  $sessionId = $_GET['ns_session'];
  genNewBBSThread( $sessionId );

  // Close out page
  genViewFooter();
?>
