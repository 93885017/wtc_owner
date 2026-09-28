<?php
// $pageSection
$getCurrecnttSection = $helperObj->getCurrentSection();
$redirectPage = '';
switch ($getCurrecnttSection) {
    case 'ready_page':
        if ($pageSection != 'ready_page') {
            $redirectPage = 'ready_page';
        }
        break;
    case 'video_page':
        if (!($pageSection == 'video_page' || $pageSection == 'login')) {
            $redirectPage = 'video_page';
        } 
        break;
    case 'finish_page':
        if ($pageSection != 'finish_page') {
            $redirectPage = 'finish_page';
        }
        break;
}

if ($redirectPage != '') {
    $redirectPagePath = $helperObj->getRedirectPage($redirectPage);
    // echo 'Current section: ' . $getCurrecnttSection . ', Redirecting from: '.$pageSection.' to: ' . $redirectPagePath . '<br>';
    echo '<script>
    window.location.href = "' . $redirectPagePath . '?t=' . time() . '";
    </script>';
    exit;
}