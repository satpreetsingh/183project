<?php
/*-----------------------------------------------------------------------------
 File:         sessionController.php
 Description:  An example of how the session conroller might work.
 UseCases:     <Later>
 Requirements: <Later>
 Components:   <Later>
-------------------------------------------------------------------------------
 Last Modified: N.Fritze
 Modified On:   10/20/09
 Notes:         Quick initial creation of session controller.
-----------------------------------------------------------------------------*/

  require_once $_SERVER['DOCUMENT_ROOT'] . '../php/facebook.php';

// API key for our application, needed for facebook session
$appapikey = '20f5b69813b87ffd25e42744b326a112';

// Secret key that's also needed for a facebook session
$appsecret = '9c30a702413dccd1856b58d2fab4c992';

// Create the facebook session
$facebook = new Facebook($appapikey, $appsecret);

// Require that the user be logged in to use the page
//  Probably all pages should have this
$user_id = $facebook->require_login();

$user = isset( $_GET['fb_sig_user']) ? $_GET['fb_sig_user'] : null;
?>
