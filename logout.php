<?php
session_start();
include_once 'config/config.php';
require_once 'class/Member.php';

if (isset($_SESSION[SITE_SESSION_KEY . 'userId'])) {
        require_once 'class/dbConnect.php';
        $db = new dbConnect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, 'error.php');
        $conn = $db->connect();
        $memberObj = new Member($conn);
        $userIdVal = !empty($_SESSION[SITE_SESSION_KEY . 'userId']) ? $_SESSION[SITE_SESSION_KEY . 'userId'] : false;
        if ($userIdVal) {
                $memberObj->clearAccessToken($userIdVal['value']);
        }
        unset($_SESSION[SITE_SESSION_KEY . 'userId']);
        unset($_SESSION[SITE_SESSION_KEY . 'accessToken']);
}

echo '<script>
        window.location.href = "index?t=' . time() . '";
        </script>';
exit;