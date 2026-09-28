<?php

define('SITE_VERSION', '0_1');
define('SITE_ENV', 'production');

// DB setup
define('DB_USERNAME', 'uqt3uzgwjdxw8');
define('DB_PASSWORD', '5*`4w))1ce{1');
define('DB_HOST', 'localhost');
define('DB_NAME', 'dbyrogqj4xzkz0');

define('HEADER_VIEWPORT', 'width=device-width, initial-scale=1.0');
// define('HEADER_VIEWPORT', '');

define('HEADER_DATE', '');
define('SITE_SESSION_KEY', '20260430123xsA1123Ab!lf@');

define('ERROR_MODE_SECTION', '');
// video_page_1, video_page_2, ready_page_1, ready_page_2
if (ERROR_MODE_SECTION == '') {
    define('ERROR_MODE_ENABLE', false);
} else {
    define('ERROR_MODE_ENABLE', true);
}

define('SAME_IP_SUBMIT_MAX_COUNT', 30);
define('SITE_BACKGROUND_COLOR', '#542a2c');
define('UPLOAD_PATH', 'uploads/images/');

define('FORM_MIN_CAHR', 1);
define('FORM_MAX_CAHR', 3000);