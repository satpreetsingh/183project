<?php
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
getSessionMembersDAL(8);
//removeUserSessionDAL(2,1);
//addUserSessionDAL(66000948,2);
//addSessionBBSPostDAL( 66000948, 2, 'testing header', 'testing body', null );
//echo getSessionBBSTopicsDAL( 2 );
//echo getSessionBBSPostsDAL( 11 );

//echo getHomePageSessionListDAL(66000948);

//addSessionNoteDAL(66000948, 1, 'testing header', 'testing body', '/no where', 'testing file.txt', 1.23999);
//echo getSessionNoteDAL (1,0);
//echo removeSessionNoteDAL(7);
//echo removeSessionBBSDAL(7);

//getSessionNoteDAL (2);

//addUserDAL(34234);

//include '/var/www/localhost/htdocs/model/dbcon.php';
//$dbData = new DBData();
//$dbData->free(true);



// Working deletion function...
function RemoveNoteFromServer()
{

  if (unlink('/home/fate/SEPRepository/183project/NoteShare/htdocs/noteFiles/66000948200911181805540')) {  
    ?>It Works!<?php
  }
  
  else {
     ?>It doesn't work :( <?php
  }
}



//RemoveNoteFromServer();

?>