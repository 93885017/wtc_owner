<?php
require_once 'admin_ini.php';

$uploadDir = 'imports/';
$failResult = null;

// $allowedExtensions = ['xlsx', 'csv'];
$allowedExtensions = ['csv'];

if (isset($_POST['submit'])) {
    $hasFailed = false;
    $extraMsg = '';

    if (isset($_FILES['import_file']) && $_FILES['import_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['import_file']['tmp_name'];
        $fileName = $_FILES['import_file']['name'];
        $fileSize = $_FILES['import_file']['size'];
        $fileType = $_FILES['import_file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Check if the file has the allowed extension
        if (in_array($fileExtension, $allowedExtensions)) {
            $destPath = $uploadDir . $fileName;
            if (move_uploaded_file($fileTmpPath, $destPath)) {

                $insertRecods = [];

                $file = fopen($destPath, "r");
                $i = 0;

                $targetInsert = [];

                while (!feof($file)) {

                    // skip the first line
                    if ($i == 0) {
                        $i++;
                        fgetcsv($file);
                        continue;
                    } else {
                        $i++;
                    }

                    $member = fgetcsv($file);
                    $username = $member[0];
                    $password = $member[1];

                    if ($username != '' && $password != '') {
                        $username = mysqli_real_escape_string($conn, $username);
                        $password = mysqli_real_escape_string($conn, $password);
                        $insertRecods[] = [$username, $password];
                    }
                }

                try {
                    // Start the transaction
                    mysqli_begin_transaction($conn);

                    // Clean up existing records
                    $currentTimeStamp = date('Y-m-d H:i:s');
                    $inactiveQuery = "Update users set status = 0, access_token = '', expired_at = null, updated_at = ? where status = 1";
                    $stmt = mysqli_prepare($conn, $inactiveQuery);
                    mysqli_stmt_bind_param($stmt, 's', $currentTimeStamp);
                    if (!mysqli_stmt_execute($stmt)) {
                        throw new Exception("Failed to update existing records: " . mysqli_error($conn));
                    }

                    // Prepare the insert query
                    $insertQuery = "INSERT INTO users (login, pswd, created_at) VALUES (?, MD5(?), ?)";
                    $stmt = mysqli_prepare($conn, $insertQuery);

                    foreach ($insertRecods as $record) {
                        mysqli_stmt_bind_param($stmt, 'sss', $record[0], $record[1], $currentTimeStamp);
                        if (!mysqli_stmt_execute($stmt)) {
                            throw new Exception("Failed to insert record: " . mysqli_error($conn));
                        }
                    }

                    // Commit the transaction
                    mysqli_commit($conn);
                    $extraMsg = ' Total records: ' . count($insertRecods);

                } catch (Exception $e) {
                    // Rollback the transaction on error
                    mysqli_rollback($conn);
                    $hasFailed = true;
                } finally {
                    // Close the statement
                    if (isset($stmt)) {
                        mysqli_stmt_close($stmt);
                    }
                }


                // remove the file after processing
                unlink($destPath);

            } else {
                $hasFailed = true;
                $failResult[] = ['message' => 'There was an error moving the uploaded file.'];
            }
        } else {
            $hasFailed = true;
            $failResult[] = ['message' => 'Only .xlsx and .csv files are allowed.'];
        }
    } else {
        $hasFailed = true;
        $failResult[] = ['message' => 'No file uploaded or there was an upload error.'];
    }

    if (!$hasFailed) {
        $updateResult = ['result' => true, 'message' => 'Updated successfully' . $extraMsg];
    }
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
        <h3>Import Users</h3>
        <br>
        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <div>File</div>
                <div>
                    <input type="file" name="import_file" id="import_file" accept=".csv" />
                </div>
                <hr>
                <div>only allow .csv</div>
                <br>
            </div>
            <input type="submit" name="submit" value="Submit">
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