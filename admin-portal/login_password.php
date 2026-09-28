<?php
require_once 'admin_ini.php';

$uploadDir = '../' . UPLOAD_PATH;
$failResult = null;

if (isset($_POST['submit'])) {
    $hasFailed = false;
    $oldPwd = $_POST['oldPwd'];
    $newPwd = $_POST['newPwd'];
    
    $updateResult = $helperObj->updateLoginPassword($oldPwd, $newPwd);
}

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
        <h3>Login Password</h3>
        <br>
        <form action="" method="post">
            Old Password: <input type="password" name="oldPwd" placeholder="Old Password" required>
            <br><br>
            New Password: <input type="password" name="newPwd" placeholder="New Password" required>
            <br><br>
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
            </div>
            <br><br><br><br>
        </form>
        <script>
            $(document).ready(function () {
                $('form').submit(function (e) {
                    var oldPwd = $('input[name="oldPwd"]').val().trim();
                    var newPwd = $('input[name="newPwd"]').val().trim();
                    if (oldPwd == '' || newPwd == '') {
                        alert('Please fill in both fields.');
                        e.preventDefault();
                    }
                });
            });
        </script>
    </div>
</body>

</html>