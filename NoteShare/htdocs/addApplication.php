<?php
/*-----------------------------------------------------------------------------
 File:         
 Description:  
 UseCases:     <Later>
 Requirements: <Later>
 Components:   <Later>
-------------------------------------------------------------------------------
 Last Modified: <Author>
 Modified On:   <Date>
 Notes:         <Dev information>
-----------------------------------------------------------------------------*/

//---------<Generic configuation probably for all pages>-----------------------
// Include Facebook API (path relative to current document location)
//   Could probably use an absolute path...but don't quote me on that -Fritze
require_once '../php/facebook.php';

// API key for our application, needed for facebook session
$appapikey = '20f5b69813b87ffd25e42744b326a112';

// Secret key that's also needed for a facebook session
$appsecret = '9c30a702413dccd1856b58d2fab4c992';

// Create the facebook session
$facebook = new Facebook($appapikey, $appsecret);

// Require that the user be logged in to use the page
//  Probably all pages should have this
$user_id = $facebook->require_login();

// Begin with a link back to the index page
//   When using quotes within an echo line...you need the escape character \
//   before the quote (see below)
echo "<a href=\"index.php\">Back to Main Page</a>";
?>

<!--------------------------- Actual Page Content ----------------------------->
<!- NOTE: This could be HTML and/or php.  If you want php remember to include
          the (less_than)?php ?(greater_than) tags
->

