<?php

//require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Controller.php';
include '/var/www/localhost/htdocs/model/NoteshareDatabase.php';


function runMemoryTests( $functions )
{
  print "Executing memory leak tests.\n";

  foreach( $functions as $testFunction )
  {
    $memTestFunction = 'memTest_' . $testFunction;
    for( $i = 0; $i < 10; $i++ )
    {
      print "Test Iteration #" . $i . ":\n";
      $memTestFunction( True );
      print "\n";
    }
  }
}

function memTest_getUniversityDAL( $bruteTest )
{
  $start =  memory_get_usage( false );
  if( $bruteTest )
  {
    print "Brute testing getUniversityDAL \n";
    for( $j = 0; $j < 2000; $j++ )
    {
      getUniversityDAL();
    }
  }
  else
  {
    print "Testing getUniversityDAL \n";
    getUniversityDAL(66000948);
  }
  $end =  memory_get_usage( false );
  $peak = memory_get_peak_usage( false );
  if( $end - $start < 3000 )
  {
    print "Test passed!\n";
  }
  else
  {
    print "ERR: Memory leak in function getUniversityDAL!\n";
  }
  print "Start: " . $start . "    End: " . $end .
        "     Peak: " . $peak . "   Diff: " . ( $end - $start ) . "\n";
}

//runMemoryTests( array( 'getUniversityDAL' ));
//HomePageSessionListDAL' ));


//WORKING CALLS Remember to add in this to output it:  	echo "<textarea cols=\"100\" rows=\"15\">".$out."</textarea>";

//getUniversityDAL();
//getDepartmentsDAL(1);
//getCoursesDAL(1);
//getSessionsDAL(1);
//getDepartmentsDAL(1);
//getSessionMetadataDAL(1);

// Create the facebook session
//require_once $_SERVER['DOCUMENT_ROOT'] . '../php/facebook.php';
//$facebook2 = new Facebook('20f5b69813b87ffd25e42744b326a112', '9c30a702413dccd1856b58d2fab4c992', true);
//getSessionMembersDAL(2,66000948,$facebook2,5);

//removeUserSessionDAL(2,1);
//addUserSessionDAL(66000948,2);
//addSessionBBSPostDAL( 66000948, 2, 'testing header', 'testing body', null );
//echo getSessionBBSTopicsDAL( 2 );
//echo getSessionBBSPostsDAL( 11 );

//echo getHomePageSessionListDAL(66000948);

//addSessionNoteDAL(66000948, 1, 'testing header', 'testing body', '/no where', 'testing file.txt', 1.23999);
//echo getSessionNoteDAL (1,0,1);
//echo removeSessionNoteDAL(7);
//echo removeSessionBBSDAL(7);

//getSessionNoteDAL (2);

//addUserDAL(34234);

//include '/var/www/localhost/htdocs/model/dbcon.php';
//$dbData = new DBData();
//$dbData->free(true);



// Working deletion function...
function RemoveNoteFromServer()
{ if (unlink('/home/fate/SEPRepository/183project/NoteShare/htdocs/noteFiles/66000948200911181805540')) {
?>It Works!<?php
} 
  else {
?>It doesn't work :( <?php
}
}
//RemoveNoteFromServer();




//addStudyGroupUserGroupDAL(66000948,2,1);
//removeUserStudyGroupDAL(66000948,1);
//getStudyGroupMembersDAL (1,2);
//getStudyGroupMetadataDAL (1);
//getHomePageStudyGroupListDAL (66000948);
//getStudyGroupsDAL(4);
//addStudyGroupUserGroupDAL(66000948,2,2);
//getStudyGroupsDAL(1,66000948);
//getStudyGroupNoteDAL(2,0,5);
//createUnversityDAL('Clarke College','Liberal Arts Institution located in Dubuque, Iowa');
//echo createDepartmentDAL(5,'Computer Science');
//echo createCourseDAL(5, 'Data Structures and Algorithms', 'This course covers the topics of various data structures commonly used in Computer Science');
//echo createSessionDAL(11, 'Fall 2009', '2009-08-11', '2009-12-11');
//echo createStudyGroupDAL( 1, "Test Header", "Description" );
//phpinfo();

?>
