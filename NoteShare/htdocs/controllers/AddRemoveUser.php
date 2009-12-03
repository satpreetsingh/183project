<?php

include_once $_SERVER['DOCUMENT_ROOT'] . 'model/NoteshareDatabase.php';

/**
 * AddRemoveUser.php
 *
 * This file is pinged by Facebook upon authorization and removal.
 *
 */

 if($_POST['fb_sig_authorize'])
 {
 	addUserDAL($_POST['fb_sig_user']);
 }
 else if($_POST['fb_sig_uninstall'])
 {
 	removeUserDAL($_POST['fb_sig_user']);
 }
 
?>
