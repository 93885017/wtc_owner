<?php
$isDBconnect = true;

require_once 'page_ini.php';

$pageSection = 'login';
// check section
require_once 'check_section.php';
// check login
require_once 'check_login.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a secure token
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $validRequest = true;
    // check the csrf token
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!$csrfToken || $csrfToken !== $_SESSION['csrf_token']) {
        // Invalid CSRF token, handle the error (e.g., show an error message or log the attempt)
        $validRequest = false;
        $error_message = $langObj->getText('invalid_request');
    }

    if ($validRequest) {
        try {
            $loginVal = $_POST['login'] ?? '';
            $passwordVal = $_POST['password'] ?? '';

            $loginResult = $memberObj->login($loginVal, $passwordVal);
            if ($loginResult['success']) {
            $helperObj->createSessionWithLifetime(SITE_SESSION_KEY . 'userId', $loginResult['user_id'], 3600);
            $helperObj->createSessionWithLifetime(SITE_SESSION_KEY . 'accessToken', $loginResult['access_token'], 3600);
                echo '<script>
                window.location.href = "video?t=' . time() . '";
                </script>';
                exit;
            } else {
                $error_message = $langObj->getText('login_fail') ;
                if (SITE_ENV == 'development') {
                    $error_message.= '('.$loginResult['error_code'].')';
                }
            }

        } catch (Exception $e) {
            // echo '' . $e->getMessage() . '';
            // exit;
        }
    }
}

$pageBannerDesktop = $helperObj->getSiteConfig('site_login_page_banner_desktop');
$pageBannerMobile = $helperObj->getSiteConfig('site_login_page_banner_mobile');

?>
<!DOCTYPE html>
<html>
<?php include_once 'template/header.common.php'; ?>
<body class="lang-<?= $lang ?> <?=($error_message != '' ? 'no-reload' : '')?>" style="background: <?= SITE_BACKGROUND_COLOR?>;">
    <?php require_once 'header.php'; ?>
    <?php require_once 'template/popup.php'; ?>
    <div class="main-container login-page">
        <div class="page-image-container">
            <img src="<?=UPLOAD_PATH . $pageBannerDesktop?>" border=0 class="desktop-section full-img-no-max" />
            <div class="mobile-section">
                <img src="<?=UPLOAD_PATH . $pageBannerMobile?>" border=0 class="full-img" />
            </div>
            <form method="post" id="loginForm">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="form_type" value="">
                <div class="form-field login">
                    <input type="text" class="password-input" id="login" name="login" value="" />
                </div>
                <div class="form-field password">
                    <input type="password" class="password-input" id="password" name="password" value="" />
                </div>

                <div class="form-field-btn">
                    <div onclick="formCheck('')" class="login-btn">登入</div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .login-page {
            position: relative;
        }

        .form-field {
            position: absolute;
            width: 18.7%;
            height: 5.4%;
            left: 48.2%;
            z-index: 0;
            /* border: 1px solid red; */
        }

        .form-field.login {
            top: 50.3%;
        }

        .form-field.password {
            top: 59.1%;
        }

        .form-field-btn {
            position: absolute;
            width: 19.7%;
            top: 68.2%;
            left: 47.7%;
            z-index: 0;
        }

        .login-btn {
            padding: 2%;;
            text-align: center;
            border: 1px solid;
            background: #393131;
            color: white;
            border-radius: 10px;
            cursor: pointer;
        }

        .password-input {
            width: 100%;
            height: 100%;
            background: transparent;
            border: 0;
            box-sizing: border-box;
        }

        /* Styles for mobile devices */
        @media only screen and (max-width: 759px) {
            /* CSS rules for mobile devices go here */
            .form-field {
                width: 45.2%;
                height: 4.1%;
                left: 27.4%;
                z-index: 0;
            }

            .form-field.login {
                top: 45.8%;
            }

            .form-field.password {
                top: 60.2%;
            }

            .form-field-btn {
                width: 45.4%;
                top: 68.5%;
                left: 27%;
                z-index: 0;
            }

        }
    </style>

    <script>
        function formCheck(type) {
            var login = document.getElementById('login').value;
            var password = document.getElementById('password').value;

            if (login == '') {
                messageDisplay('<?= $langObj->getText('fill_login') ?>');
                return;
            }

            if (password == '') {
                messageDisplay('<?= $langObj->getText('fill_password') ?>');
                return;
            }

            document.getElementsByName('form_type')[0].value = type;
            document.getElementById("loginForm").submit();
        }

        $(document).ready(function () {
            <?php if (!empty($error_message)) { ?>
                messageDisplay('<?= $error_message ?>');
            <?php } ?>
        });
    </script>
</body>

</html>