<?php
require_once 'admin_ini.php';

$uploadDir = '../' . UPLOAD_PATH;
$failResult = null;

if (isset($_POST['submit'])) {
    $hasFailed = false;
    $videoLiveUrl = $_POST['video_live_url'];
    $videoLiveUrlResult = $helperObj->updateSiteConfig('site_video_page_live_link', $videoLiveUrl);

    $imageUploadSet = [
        'site_login_page_banner_desktop' => ['configKey' => 'site_login_page_banner_desktop', 'prefix' => 'l1' , 'existingFileKey' => 'existing_login_page_banner_desktop'],
        'site_login_page_banner_mobile' => ['configKey' => 'site_login_page_banner_mobile', 'prefix' => 'l2' , 'existingFileKey' => 'existing_login_page_banner_mobile'],
        'site_ready_page_banner_desktop' => ['configKey' => 'site_ready_page_banner_desktop', 'prefix' => 'r1' , 'existingFileKey' => 'existing_ready_page_banner_desktop'],
        'site_ready_page_banner_mobile' => ['configKey' => 'site_ready_page_banner_mobile', 'prefix' => 'r2' , 'existingFileKey' => 'existing_ready_page_banner_mobile'],
        'site_finish_page_banner_desktop' => ['configKey' => 'site_finish_page_banner_desktop', 'prefix' => 'e1' , 'existingFileKey' => 'existing_finish_page_banner_desktop'],
        'site_finish_page_banner_mobile' => ['configKey' => 'site_finish_page_banner_mobile', 'prefix' => 'e2' , 'existingFileKey' => 'existing_finish_page_banner_mobile'],
        'site_qanda_page_banner_desktop' => ['configKey' => 'site_qanda_page_banner_desktop', 'prefix' => 'q1' , 'existingFileKey' => 'existing_qanda_page_banner_desktop'],
        'site_qanda_page_banner_mobile' => ['configKey' => 'site_qanda_page_banner_mobile', 'prefix' => 'q2' , 'existingFileKey' => 'existing_qanda_page_banner_mobile'],
        'site_video_page_banner_desktop' => ['configKey' => 'site_video_page_banner_desktop', 'prefix' => 'v1' , 'existingFileKey' => 'existing_video_page_banner_desktop'],
        'site_video_page_banner_mobile' => ['configKey' => 'site_video_page_banner_mobile', 'prefix' => 'v2' , 'existingFileKey' => 'existing_video_page_banner_mobile'],
    ];

    foreach ($imageUploadSet as $inputName => $config) {
        $uploadResult = $helperObj->handleFileUpload($inputName, $config['prefix'], $uploadDir);
        if ($uploadResult != null && $uploadResult['result']) {
            // upload DB value
            $helperObj->updateSiteConfig($config['configKey'], $uploadResult['fileName']);
            // remove the existing file if new file uploaded successfully
            $existingFile = $_POST[$config['existingFileKey']];
            if ($existingFile && file_exists($uploadDir . $existingFile)) {
                unlink($uploadDir . $existingFile);
            }
        } else if ($uploadResult != null && $uploadResult['result'] === false) {
            $hasFailed = true;
            $failResult[] = $uploadResult;
        }
    }

    if (!$hasFailed) {
        $updateResult = ['result' => true, 'message' => 'Updated successfully'];
    }    
}

$recommandedSizeInfoDesktop = 'Recommended size: 2000 × 1125 px, Max file size: 5MB';
$recommandedSizeInfoMobile = 'Recommended size: 2000 × 3555 px, Max file size: 5MB';
?>
<html>
<head>
    <?php require_once 'admin.header.php'; ?>
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>
        <br>
        <?php require_once 'admin.common.php'; ?>
        <br>
        <h3>Page Setting</h3>
        <br>
        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <div>Login Page</div>
                <div>
                    <?php $existsDesktopBanner = $helperObj->getSiteConfig('site_login_page_banner_desktop'); ?>
                    Desktop Banner: (<?=$recommandedSizeInfoDesktop?>)<br>
                    <input type="file" name="site_login_page_banner_desktop" id="site_login_page_banner_desktop" />
                    Preview: <img src="<?= $uploadDir . $existsDesktopBanner ?>" border=0 height="50" />
                    (<a href="<?= $uploadDir . $existsDesktopBanner ?>" target="_blank">View Full Size</a>)
                    <input type="hidden" name="existing_login_page_banner_desktop" value="<?= $existsDesktopBanner ?>" />
                </div>
                <div>
                    <?php $existsMobileBanner = $helperObj->getSiteConfig('site_login_page_banner_mobile'); ?>
                    Mobile Banner: (<?=$recommandedSizeInfoMobile?>)<br>
                    <input type="file" name="site_login_page_banner_mobile" id="site_login_page_banner_mobile" />
                    Preview: <img src="<?= $uploadDir . $existsMobileBanner ?>" border=0 height="50" />
                    (<a href="<?= $uploadDir . $existsMobileBanner ?>" target="_blank">View Full Size</a>)
                    <input type="hidden" name="existing_login_page_banner_mobile" value="<?= $existsMobileBanner ?>" />
                </div>
                <hr>
                <div>Ready Page</div>
                <div>
                    <?php $existsReadyDesktopBanner = $helperObj->getSiteConfig('site_ready_page_banner_desktop'); ?>
                    Desktop Banner: (<?=$recommandedSizeInfoDesktop?>)<br>
                    <input type="file" name="site_ready_page_banner_desktop" id="site_ready_page_banner_desktop" />
                    Preview: <img src="<?= $uploadDir . $existsReadyDesktopBanner ?>" border=0 height="50" />
                    (<a href="<?= $uploadDir . $existsReadyDesktopBanner ?>" target="_blank">View Full Size</a>)
                    <input type="hidden" name="existing_ready_page_banner_desktop" value="<?= $existsReadyDesktopBanner ?>" />
                </div>
                <div>
                    <?php $existsReadyMobileBanner = $helperObj->getSiteConfig('site_ready_page_banner_mobile'); ?>
                    Mobile Banner: (<?=$recommandedSizeInfoMobile?>)<br>
                    <input type="file" name="site_ready_page_banner_mobile" id="site_ready_page_banner_mobile" />
                    Preview: <img src="<?= $uploadDir . $existsReadyMobileBanner ?>" border=0 height="50" />
                    (<a href="<?= $uploadDir . $existsReadyMobileBanner ?>" target="_blank">View Full Size</a>)
                    <input type="hidden" name="existing_ready_page_banner_mobile" value="<?= $existsReadyMobileBanner ?>" />
                </div>
                <hr>
                <div>Video Page</div>
                <div>
                    Live Stream URL:<br>
                    <input type="text" name="video_live_url" id="video_live_url" style="width: 500px !important;" value="<?= $helperObj->getSiteConfig('site_video_page_live_link') ?>" placeholder="Live URL" />
                </div>
                <hr>
                <div>
                    <?php $existsingDesktopBanner = $helperObj->getSiteConfig('site_video_page_banner_desktop'); ?>
                    Desktop Banner: (<?=$recommandedSizeInfoDesktop?>)<br>
                    <input type="file" name="site_video_page_banner_desktop" id="site_video_page_banner_desktop" />
                    Preview: <img src="<?= $uploadDir . $existsingDesktopBanner ?>" border=0 height="50" />
                    (<a href="<?= $uploadDir . $existsingDesktopBanner ?>" target="_blank">View Full Size</a>)
                    <input type="hidden" name="existing_video_page_banner_desktop" value="<?= $existsingDesktopBanner ?>" />
                </div>
                <div>
                    <?php $existsingMobileBanner = $helperObj->getSiteConfig('site_video_page_banner_mobile'); ?>
                    Mobile Banner: (<?=$recommandedSizeInfoMobile?>)<br>
                    <input type="file" name="site_video_page_banner_mobile" id="site_video_page_banner_mobile" />
                    Preview: <img src="<?= $uploadDir . $existsingMobileBanner ?>" border=0 height="50" />
                    (<a href="<?= $uploadDir . $existsingMobileBanner ?>" target="_blank">View Full Size</a>)
                    <input type="hidden" name="existing_video_page_banner_mobile" value="<?= $existsingMobileBanner ?>" />
                </div>
                <hr>
                <div>Ending Page</div>
                <div>
                    <?php $existsingDesktopBanner = $helperObj->getSiteConfig('site_finish_page_banner_desktop'); ?>
                    Desktop Banner: (<?=$recommandedSizeInfoDesktop?>)<br>
                    <input type="file" name="site_finish_page_banner_desktop" id="site_finish_page_banner_desktop" />
                    Preview: <img src="<?= $uploadDir . $existsingDesktopBanner ?>" border=0 height="50" />
                    (<a href="<?= $uploadDir . $existsingDesktopBanner ?>" target="_blank">View Full Size</a>)
                    <input type="hidden" name="existing_finish_page_banner_desktop" value="<?= $existsingDesktopBanner ?>" />
                </div>
                <div>
                    <?php $existsingMobileBanner = $helperObj->getSiteConfig('site_finish_page_banner_mobile'); ?>
                    Mobile Banner: (<?=$recommandedSizeInfoMobile?>)<br>
                    <input type="file" name="site_finish_page_banner_mobile" id="site_finish_page_banner_mobile" />
                    Preview: <img src="<?= $uploadDir . $existsingMobileBanner ?>" border=0 height="50" />
                    (<a href="<?= $uploadDir . $existsingMobileBanner ?>" target="_blank">View Full Size</a>)
                    <input type="hidden" name="existing_finish_page_banner_mobile" value="<?= $existsingMobileBanner ?>" />
                </div>
                <div>Q and A Page</div>
                <div>
                    <?php $existsingQnADesktopBanner = $helperObj->getSiteConfig('site_qanda_page_banner_desktop'); ?>
                    Desktop Banner: (<?=$recommandedSizeInfoDesktop?>)<br>
                    <input type="file" name="site_qanda_page_banner_desktop" id="site_qanda_page_banner_desktop" />
                    Preview: <img src="<?= $uploadDir . $existsingQnADesktopBanner ?>" border=0 height="50" />
                    (<a href="<?= $uploadDir . $existsingQnADesktopBanner ?>" target="_blank">View Full Size</a>)
                    <input type="hidden" name="existing_qanda_page_banner_desktop" value="<?= $existsingQnADesktopBanner ?>" />
                </div>
                <div>
                    <?php $existsingQnAMobileBanner = $helperObj->getSiteConfig('site_qanda_page_banner_mobile'); ?>
                    Mobile Banner: (<?=$recommandedSizeInfoMobile?>)<br>
                    <input type="file" name="site_qanda_page_banner_mobile" id="site_qanda_page_banner_mobile" />
                    Preview: <img src="<?= $uploadDir . $existsingQnAMobileBanner ?>" border=0 height="50" />
                    (<a href="<?= $uploadDir . $existsingQnAMobileBanner ?>" target="_blank">View Full Size</a>)
                    <input type="hidden" name="existing_qanda_page_banner_mobile" value="<?= $existsingQnAMobileBanner ?>" />
                </div>
            </div>
            <input type="submit" name="submit" value="Update">
            <br>
            <div class="message-field">
                <?php if (isset($updateResult) && $updateResult): ?>
                    <span class="<?= ($updateResult['result']) ? 'success' : 'error' ?>">
                        <?= $updateResult['message'] ?>
                        (<?php
                        echo date('Y-m-d H:i:s');
                        ?>)
                    </span>
                <?php endif; ?>
                <?php if ($failResult != null): ?>
                    <span class="error">
                        <?php foreach ($failResult as $result): ?>
                            <?= $result['message'] ?><br>
                        <?php endforeach; ?>
                    </span>
                <?php endif; ?>
            </div>
            <br><br><br><br>
        </form>
    </div>
</body>

</html>