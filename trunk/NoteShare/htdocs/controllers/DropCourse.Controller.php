<?php

include $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';

function removeUserSession($userid,$sessionid)
{
	removeUserSessionDAL($userid,$sessionid);
	return;
}

?>
