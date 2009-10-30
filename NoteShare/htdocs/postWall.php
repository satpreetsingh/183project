<?php
/*-----------------------------------------------------------------------------
 File: postwall.php        
 Description: Basic look and feel of how Discussion Boards/Wall posts in
               NoteShare
 UseCases:     <Later>
 Requirements: <Later>
 Components:   <Later>
-------------------------------------------------------------------------------
 Last Modified: Nathan Denklau
 Modified On:   10/7/2009
 Notes:         Initial creation of this page to its skeleton extent. This
                should serve as an example of how to handle discussion board
                and walls in NoteShare. 
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
<?php
$username="facebook"; //login credentials for the mysql server
$password="noteshare"; //no database has been created yet

echo "<br><br><br><font style=\"font-size: large;\">This is a wall</font>";
echo "<fb:wall>";
echo "<fb:wallpost uid=\"$user_id\">This is a wall post!</fb:wallpost>";
echo "<fb:wallpost uid=\"$user_id\">Here is a note set...notice the link that follows!";
echo "<fb:wallpost-action href=\"http://www.google.com\">Link to a note set</fb:wallpost-action></fb:wallpost>";
echo "</fb:wall>";

echo "<br><br><br><font style=\"font-size: large;\">This is a comment section</font>";
echo "<fb:comments xid=\"example\" canpost=\"true\" candelete=\"false\"publish_feed=\"false\" reverse=\"true\">";
echo "<fb:title>Comments</fb:title>";
echo "</fb:comments>";

echo "<br><br><br><font style=\"font-size: large;\">This is a discussion board</font>";
echo "<fb:board xid=\"example2\" canpost=\"true\" candelete=\"false\" canmark=\"true\" cancreatetopic=\"true\">";
echo "<fb:title>Course Name</fb:title>";
echo "</fb:board>";

include 'model/NoteshareDatabase.php';

echo "<textarea cols=\"100\" rows=\"15\">".getUniversityDAL()."</textarea>";

?>
