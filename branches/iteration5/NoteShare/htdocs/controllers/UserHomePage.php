<?php

  require_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/Controller.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';


/**
 * Fetches the course list from the DAL given the specified user id.
 *
 * @version 1.0
 * @param integer $userid Facebook user ID
 * @return XML containing user's course list
**/
function getHomePageSessionList( $userid )
{
  if( $userid == NULL )
  {
    return "Err: Bad user id number.\n";
  }

  $sessionList = getHomePageSessionListDAL( $userid );
  return $sessionList;
}

/**
 *
**/
function getHomePageStudyGroupList( $userid )
{
  if( $userid == NULL )
  {
    return "Err: Bad user id number.\n";
  }

  $groupList = getHomePageStudyGroupListDAL( $userid );
  return $groupList;
}
?>
