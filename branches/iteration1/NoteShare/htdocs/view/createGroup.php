<?php
/*-----------------------------------------------------------------------------
 File:         
 Description:  
 UseCases:     <Later>
 Requirements: <Later>
 Components:   <Later>
-------------------------------------------------------------------------------
 Last Modified: Satpreet
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
<?
echo "<br>Hello, <fb:name uid=\"$user_id\" useyou=\"false\" />!<br>";
?>

<!-Note commenting style for HTML ->
<hr>
<font style="font-size: small;">Create Study-Group under Course:</font> 
<a href="/addCourse.php">Operating Systems (Fall 2009)</a>
<br>
<br>

  <!- This is the new Study-Group field->
  <fb:editor-text label="Study-Group Name " name="newStudyGroup" value="Group Name"/>
<br>

  <!- Bottom display buttons, don't forget the / ->
  <fb:editor-buttonset>
  <br>

    <fb:editor-button value="Create"/> 
   <fb:editor-cancel />
  </fb:editor-buttonset>
</fb:editor>

