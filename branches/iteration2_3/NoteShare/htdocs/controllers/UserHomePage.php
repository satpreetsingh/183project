<?php

include $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';

/**
 * Fetches the course list from the DAL given the specified user id.
 *
 * @version 1.0
 * @param integer $userid Facebook user ID
 * @return XML containing user's course list
**/
function getHomePageSessionList( $userid )
{
  $sessionList = getHomePageSessionListDAL( $userid );

  return $sessionList;
}
?>
