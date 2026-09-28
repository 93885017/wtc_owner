<?php
// $pageSection
$accessTokenVal = !empty($_SESSION[SITE_SESSION_KEY . 'accessToken']) ? $_SESSION[SITE_SESSION_KEY . 'accessToken'] : false;
$isLoggedIn = $accessTokenVal['value'] ?? false;

if ($isLoggedIn) {
    $userIdVal = !empty($_SESSION[SITE_SESSION_KEY . 'userId']) ? $_SESSION[SITE_SESSION_KEY . 'userId'] : false;
    if ($userIdVal) {
        $isLoggedIn = $memberObj->checkIsValidAccessToken($accessTokenVal['value'], $userIdVal['value']);
    } else {
        $isLoggedIn = false;
    }

    if(!$isLoggedIn) {
        // clear all session
        unset($_SESSION[SITE_SESSION_KEY . 'userId']);
        unset($_SESSION[SITE_SESSION_KEY . 'accessToken']);
    }
}

if ($pageSection == 'login') {
    // login page, if already login then redirect to ready page
    if ($isLoggedIn) {
        echo '<script>
        window.location.href = "video?t=' . time() . '";
        </script>';
        exit;
    }
} else {
    // other page redirect to login page
    if (!$isLoggedIn) {
        echo '<script>
        window.location.href = "index?t=' . time() . '";
        </script>';
        exit;
    }
}