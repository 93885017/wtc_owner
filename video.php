<?php
$isDBconnect = true;
require_once 'page_ini.php';
$pageSection = 'video_page';
// check login
require_once 'check_login.php';
// check section
require_once 'check_section.php';

$displayVideo = $helperObj->getSiteConfig('site_video_page_live_link');
$pageBannerDesktop = $helperObj->getSiteConfig('site_video_page_banner_desktop');
$pageBannerMobile = $helperObj->getSiteConfig('site_video_page_banner_mobile');
?>
<!DOCTYPE html>
<html>
<?php include_once 'template/header.common.php'; ?>
<body class="lang-<?= $lang ?>" style="background: <?= SITE_BACKGROUND_COLOR?>;">
    <?php require_once 'header.php'; ?>
    <?php require_once 'template/popup.php'; ?>
    <div class="main-container video-page">
        <div class="page-image-container">
            <img src="<?=UPLOAD_PATH . $pageBannerDesktop ?>" border=0 class="desktop-section full-img-no-max" />
            <div class="mobile-section">
                <img src="<?=UPLOAD_PATH . $pageBannerMobile ?>" border=0 class="full-img" />
            </div>
            <div class="video-frame-field">
                <iframe src="<?=$displayVideo?>" width="100%" style="aspect-ratio: 16/9;" frameborder="0" scrolling="no" allow="autoplay" allowfullscreen  webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe> 
            </div>
            <div class="logout-field">
                <div onclick="logout()" class="login-btn">登出</div>
            </div>
        </div>
    </div>
    <style>
        .video-page {
            position: relative;
        }

        .video-frame-field {
            position: absolute;
            width: 62.7%;
            /* border: 1px solid red; */
            top: 24.5%;
            left: 18.7%;
            z-index: 0;
        }

        .video-button-container {
            position: absolute;
            /* border: 1px solid red; */
            width: 10%;
            top: 90%;
            left: 45%;
            z-index: 0;
        }

        .logout-field {
            position: absolute;
            bottom: 2%;
            right: 2%;
            z-index: 99;
            cursor: pointer;
            text-decoration: underline;
        }

        /* Styles for mobile devices */
        @media only screen and (max-width: 759px) {
            /* CSS rules for mobile devices go here */
            .video-frame-field {
                width: 84%;
                top: 24.8%;
                left: 8%;
                z-index: 0;
            }

            .video-button-container {
                width: 40%;
                top: 54%;
                left: 30%;
                z-index: 0;
            }

            .logout-field {
                bottom: 30%;
            }

        }
    </style>
    <script>
    $(document).ready(function () {
        var interValTime = 5 * 60 * 1000; // 5 minutes
        setInterval(function() {
            $.ajax({
                type: "POST",
                url: "user_active.php",
                success: function (response) {
                    response = JSON.parse(response);
                    if (response.error == 'no_token') {
                        alert('<?= $langObj->getText('login_expired') ?>');
                        logout();
                    }
                }
            });
         }, interValTime);
    });

    function logout() {
        window.location.href = "logout?t=" + new Date().getTime();
    }
</script>
</body>
</html>