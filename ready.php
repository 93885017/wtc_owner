<?php
$isDBconnect = true;
require_once 'page_ini.php';
$pageSection = 'ready_page';
// check section
require_once 'check_section.php';

// this page is public page, no need to check login

$pageBannerDesktop = $helperObj->getSiteConfig('site_ready_page_banner_desktop');
$pageBannerMobile = $helperObj->getSiteConfig('site_ready_page_banner_mobile');
?>
<!DOCTYPE html>
<html>
<?php include_once 'template/header.common.php'; ?>
<body class="lang-<?= $lang ?>" style="background: <?= SITE_BACKGROUND_COLOR?>;">
    <?php require_once 'header.php'; ?>
    <div class="main-container ready-page">
        <div class="desktop-section">
            <div class="desktop-image-container"><img src="<?=UPLOAD_PATH . $pageBannerDesktop ?>" border=0 class="full-img" /></div>
        </div>
        <div class="mobile-section">
            <img src="<?=UPLOAD_PATH . $pageBannerMobile ?>" border=0 class="full-img" />
        </div>
    </div>
</body>
</html>