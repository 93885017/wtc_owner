<?php
require_once 'admin_ini.php';

if (isset($_POST['submit'])) {
    $sel_section = $_POST['sel_section'];
    $updateResult = $helperObj->updateSiteConfig('enable_section', $sel_section);
}
$curr_section = $helperObj->getSiteConfig('enable_section');
?>
<html>

<head>
    <meta name="viewport" content="<?= HEADER_VIEWPORT ?>">
    <title>Admin Panel</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../plugin/bootstrap/css/bootstrap.min.css">
    <script src="../plugin/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container">
        <h1>Admin Panel</h1>
        <br>
        <?php require_once 'admin.common.php'; ?>
        <br>
        <?php
        $sections = [
            'ready_page' => 'Ready Page',
            'video_page' => 'Video Page',
            'finish_page' => 'Ending Page',
            // 'contingency_page' => 'Contingency Page'
        ];
        ?>
        <h3>Set Page</h3>
        <br>
        <form action="" method="post">
            <select id="sel_section" name="sel_section">
                <?php foreach ($sections as $value => $text): ?>
                    <option value="<?= $value ?>" <?= ($curr_section == $value) ? 'selected' : '' ?>><?= $text ?></option>
                <?php endforeach; ?>
            </select>
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
                    var curr_section = $('input[name="sel_section"]').val();
                    if (curr_section == '') {
                        alert('Please select the Display Section');
                        e.preventDefault();
                    }
                });
            });
        </script>
    </div>
    </div>
</body>

</html>