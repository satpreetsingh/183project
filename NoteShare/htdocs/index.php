<?php
/*-----------------------------------------------------------------------------
 File:          index.php
 Description:   Kept here merely as a redirect to the user homepage.
 UseCases:      
 Requirements:  
 Components:    
-------------------------------------------------------------------------------
 Modified On:   11/04/09
 Notes:         Moved all of the content to the user homepage in the view 
                directory where it seems to belong.  This is kept merely as a
                redirect.

 Modified On:   10/07/09
 Notes:         Initial creation of this page to its skeleton extent.  This
                should serve as an example of how to create screen mockups
                for our architecture document.
-----------------------------------------------------------------------------*/
  require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Controller.php';

  $facebook->redirect( 'http://apps.facebook.com/notesharesep/views/UserHomePage.php' );
?>
