<?php
$isDBconnect = true;
require_once 'page_ini.php';

$response = [
    'status' => 'success',
    'error' => '',
];

$responseCodeValue = 200;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accessTokenVal = !empty($_SESSION[SITE_SESSION_KEY . 'accessToken']) ? $_SESSION[SITE_SESSION_KEY . 'accessToken'] : false;
    $isLoggedIn = $accessTokenVal['value'] ?? false;

    if ($isLoggedIn) {
        $userIdVal = !empty($_SESSION[SITE_SESSION_KEY . 'userId']) ? $_SESSION[SITE_SESSION_KEY . 'userId'] : false;
        if ($userIdVal) {
            $userIdVal = $userIdVal['value'];
            if ($memberObj->isTokenExists($userIdVal)) {
                $memberObj->extendAccessTokenExpireTime($userIdVal);
            } else {
                $response['error'] = 'no_token';
            }
        }
    }

}

http_response_code($responseCodeValue);
echo json_encode($response);
exit;