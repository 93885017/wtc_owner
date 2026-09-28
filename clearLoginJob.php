<?php
// if (php_sapi_name() !== 'cli') {
//     die('This script can only be run from the command line.');
// }

include_once 'config/config.php';
require_once 'class/Member.php';

require_once 'class/dbConnect.php';
$db = new dbConnect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, 'error.php');
$conn = $db->connect();
$memberObj = new Member($conn);

$memberObj->clearExpiredUsers();

require_once 'class/HelperClass.php';
$helperObj = new HelperClass($conn);
$helperObj->addJobLog('clearLoginJob', 'Cleared expired login sessions');