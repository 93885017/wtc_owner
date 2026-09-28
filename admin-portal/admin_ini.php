<?php
session_start();
header("Cache-Control: no-cache");
require_once '../config/config.php';
date_default_timezone_set('Asia/Hong_Kong');
if (SITE_ENV == 'development') {
    // enable php debug mode
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
date_default_timezone_set('Asia/Hong_Kong');

require_once '../class/dbConnect.php';
require_once '../class/HelperClass.php';

$db = new dbConnect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, 'index.php');
$conn = $db->connect();
$helperObj = new HelperClass($conn);
