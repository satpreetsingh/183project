<?php
/*-----------------------------------------------------------------------------
 File:        index.php
 Description: Basic user homepage that every user will see when they login
 UseCases:
 Requirements:
 Components: 
-------------------------------------------------------------------------------
 Modified On:   10/07/09
 Notes:         Initial creation of this page to its skeleton extent.  This
                should serve as an example of how to create screen mockups
                for our architecture document.
-----------------------------------------------------------------------------*/

//---------<Generic configuation probably for all pages>-----------------------
require_once './controllers/Session.Controller.php';

include './controllers/UserHomePage.Controller.php';    // controller

// Require that the user be logged in to use the page
//  Probably all pages should have this
$user_id = $facebook->require_login();

//-----------------<Actual User Course Page>------------------------------------
$username="facebook";   // login credentials for the mySql server
$password="noteshare";  // --no database has been created yet

// echo HTML when inside the php tags.
// Outside of those tags, everything is considered HTML

// USER_PAGE::TITLE
// --Shows how to get the user name via FBML
?>
<!-- Note: Include this div markup as a workaround for a known bug in this release on IE where you may get a "operation aborted" error --> <div id="FB_HiddenIFrameContainer" style="display:none; position:absolute; left:-100px; top:-100px; width:0px; height: 0px;"></div> <script src="http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script> <script type="text/javascript"> FB_RequireFeatures(["CanvasUtil"], function(){ FB.XdComm.Server.init(<site relative url to xd_receiver.htm>); FB.CanvasClient.startTimerToSizeToContent(); }); </script>
<font style="font-size: large;">NoteShare (beta)</font>

<?php
$userDetails = $facebook->api_client->users_getInfo( $user_id, 'last_name, first_name');
if( $userDetails != null )
{
  echo "<p>Hello, " . $userDetails[0]['first_name'] . "!</p>";
} else {
  echo $user_id;
}
?>

<!-Note commenting style for HTML ->
<hr>
<font style="font-size: large;">Your Courses</font> -- [
 <a href="/addCourse.php">Add a course</a>
]<br>

<! --HTML won't recognize less thans or greater thans...so do this:
  &lt; makes '<'   (less than)
  &gt; makes (greater than) {can't show it, otherwise the comment will stop}
  &nbsp; makes ' ' (non breaking space)
->

<?php
//echo GetCourseList();
echo "<br><br>";
echo "(The XML gets interpreted, but, underneath the hood, starts here: Use GetCourseListXML() )";
?>

<p> Course Stuff <a href="">Course Homepage</a></p>
<br>
<br>
<hr>
<font style="font-size: large;">Your Notes</font> -- [
 <a href="/addNote.php">Upload a Note</a>
]<br>
Sort by: [ <a href="">Upload Date</a> | <a href="">Rating</a> | <a href="">etc</a> ]<br>
<p>Note Name [ <a href="">X</a> ]</p>
<br>
<br>
<hr>
<font style="font-size: large;">Your Study Groups</font> -- [
 <a href="/joinGroup.php">Join a Group</a>
]<br>
&lt;List of groups shown here&gt;
<hr>
<font style="font-size: large;">Your Friends</font><br>

<?php
/*
// Print out at most 25 of the logged-in user's friends,
// using the friends.get API method
echo "<p>Friends:";
$friends = $facebook->api_client->friends_get();
$friends = array_slice($friends, 0, 25);
foreach ($friends as $friend) {
  echo "<br><fb:name uid=\"$friend\" useyou=\"false\" />";
}
echo "</p>";
*/
?>

<hr>

<!- JavaScript Example
 This is a link.  Note that the id is needed for accessing this via JScript
   (There are other ways...but let's stick with this for now ) -Fritze
->
<a href="#" id="hello" onmouseover="hello_world()">Hello World!</a>

<script>
<!--
  /*
   * We'll want documentation for these types of functions (?)
   * This just generates a random number.
  */
  function random_int(lo, hi) {
    return Math.floor((Math.random() * (hi - lo)) + lo);
  }

  // This manipulates the obj's color
  function hello_world() {
    var obj = document.getElementById( "hello" );
    var r = random_int(0, 255);
    var b = random_int(0, 255);
    var g = random_int(0, 255);
    var color = r+', '+g+', '+b;
    obj.style.color='rgb('+color+')';
  }
  hello_world();
//-->
</script>

<p>Okay, so here are links to where everyone's pages could be.  These are in
  the same directory as index.php (this file)</p>
Fritze -- <a href="test.php">addcourse.php</a><br>
JonH -- <a href="uploadNote.php">uploadNote.php</a><br>
Joe -- <a href="addApplication.php">addApplication.php</a> -- might not
  need a page for this<br>
Denklau -- <a href="postWall.php">postWall.php</a><br>
Satpreet -- <a href="createGroup.php">createGroup.php</a><br>

<?php
  include 'disclaimer.php';
?>
