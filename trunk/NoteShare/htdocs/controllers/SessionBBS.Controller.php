<?php

  include $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';

function getThreadWall( $threadID )
{
  return '' .
  '<?xml version="1.0" encoding="UTF-8"?>
   <ThreadWall Topic="Tests Coming up...">
     <Post Header="Example Header"
           Date="11-09-09 12:00:01"
           User="14821122">
		   Test next week everyone!
     </Post>
		 <Comment Header="Example Header"
			   Date="11-09-09 12:00:02"
			   User="14821222">
			   Just 7 days!
		 </Comment>		
     <Post Header="Example Header2"
           Date="11-09-09 12:00:05"
           User="14821122">
		   Welcome to the new semester!
     </Post>
   </ThreadWall>';
}

?>
