<?php
require_once '../config/config.php';
require_once '../class/QandaInfo.php';
require_once '../class/dbConnect.php';

$db = new dbConnect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, 'index.php');
$conn = $db->connect();

$registerInfoObj = new QandaInfo();
$registerInfoObj->exportCSV($conn);
