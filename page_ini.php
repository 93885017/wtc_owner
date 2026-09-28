<?php
session_start();
require_once 'config/config.php';
date_default_timezone_set('Asia/Hong_Kong');

if (SITE_ENV == 'development') {
    // enable php debug mode
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    // disable php debug mode
    error_reporting(0);
    ini_set('display_errors', 0);
}

require_once 'class/HelperClass.php';
require_once 'class/BilingualClass.php';
require_once 'class/Member.php';
$defaultLang = 'tc';


if ($isDBconnect) {
    require_once 'class/dbConnect.php';
    $db = new dbConnect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, 'error.php');
    $conn = $db->connect();
}

if (isset($_COOKIE[SITE_SESSION_KEY . 'lang'])) {
    $defaultLang = $_COOKIE[SITE_SESSION_KEY . 'lang'];
}

$lang = (isset($_GET['lang']) && $_GET['lang'] != '') ? $_GET['lang'] : $defaultLang;
$isTesting = (isset($_GET['testing']) && $_GET['testing'] == 'Y');

$accept_lang = array('tc');
if (!in_array($lang, $accept_lang)) {
    $lang = 'tc';
}

$langObj = new BilingualClass();
$langObj->setLanguage($lang);

$helperObj = new HelperClass($conn);
$memberObj = new Member($conn);
