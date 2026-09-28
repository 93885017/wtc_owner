<?php
class HelperClass
{
    private $conn;
    private $currentSection = "";

    public function __construct($conn)
    {
        date_default_timezone_set('Asia/Hong_Kong');
        $this->conn = $conn;
    }

    public function checkCurrentSection($sectionName, $setionValue = '')
    {
        $sql = "SELECT meta_value FROM site_config WHERE meta_key = 'enable_section'";
        $result = mysqli_query($this->conn, $sql);
        $row = mysqli_fetch_array($result);
        $this->currentSection = $row['meta_value'];
        $redirectTo = '';
        if ($redirectTo != '') {
            $currTime = time();
            echo '<script>
                window.location = "' . $redirectTo . '?t=' . $currTime . '";
                </script>';
            exit;
        }

    }

    public function getRedirectPage($section)
    {
        switch ($section) {
            case 'contingency_page':
                return 'contingency_page';
            case "video_page":
                return "video";
            case "ready_page":
                return "ready";
            case "finish_page":
                return "finish";
            default:
                return "index";
        }
    }

    public function updateSiteConfig($key, $value)
    {
        try {
            $sql = "UPDATE site_config SET meta_value = ?, updated_at = NOW() WHERE meta_key = ?";
            $stmt = mysqli_prepare($this->conn, $sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
            }
            mysqli_stmt_bind_param($stmt, 'ss', $value, $key);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
        } catch (Exception $e) {
            return ['result' => false, 'message' => 'Failed to update the section. Please contact the system admin. (' . $e->getMessage() . ')'];
        }
        return ['result' => true, 'message' => 'Updated successfully'];
    }

    public function getSiteConfig($metaKey)
    {
        $sql = "SELECT meta_value FROM site_config WHERE meta_key = '$metaKey'";
        $result = mysqli_query($this->conn, $sql);

        $row = mysqli_fetch_array($result);
        if ($row) {
            return $row['meta_value'];
        } else {
            return false;
        }
    }

    public function loginCheck($password)
    {
        // Use prepared statements to prevent SQL injection
        $sql = "SELECT 1 FROM site_config WHERE meta_key = 'member_login' AND meta_value = md5(?) LIMIT 1";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        // Check if a record exists
        if (mysqli_stmt_num_rows($stmt) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateLoginPassword($oldPwd, $newPwd)
    {
        // First, check if the old password is correct
        if (!$this->loginCheck($oldPwd)) {
            return ['result' => false, 'message' => 'Old password is incorrect.'];
        }

        // If the old password is correct, update to the new password
        $updateResult = $this->updateSiteConfig('member_login', md5($newPwd));
        if ($updateResult['result']) {
            return ['result' => true, 'message' => 'Password updated successfully.'];
        } else {
            return ['result' => false, 'message' => 'Failed to update password. Please contact the system admin.'];
        }
    }

    public function getCurrentSection()
    {
        return $this->getSiteConfig('enable_section');
    }

    public function getUserIP()
    {
        // Get the user's IP address
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // Check for shared internet/ISP IP
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Check for IPs passing through proxies
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            // Use the remote address
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            // Default to an empty string if no IP is found
            $ip = '';
        }
        return $ip;
    }

    /**
     * Creates or updates a session variable with a time limit.
     *
     * @param string $key The session key.
     * @param mixed $value The value to store in the session.
     * @param int $lifetime The lifetime of the session in seconds.
     */
    public function createSessionWithLifetime($key, $value, $lifetime = 300)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Store the value and expiration time in the session
        $_SESSION[$key] = [
            'value' => $value,
            'expires_at' => time() + $lifetime
        ];
    }

    /**
     * Retrieves a session variable if it hasn't expired.
     *
     * @param string $key The session key.
     * @return mixed|null The session value, or null if expired or not set.
     */
    public function getSessionWithLifetime($key)
    {
        session_start();

        if (isset($_SESSION[$key])) {
            $sessionData = $_SESSION[$key];
            // Check if the session has expired
            if (time() < $sessionData['expires_at']) {
                return $sessionData['value'];
            } else {
                // Remove the expired session
                unset($_SESSION[$key]);
            }
        }

        return null; // Return null if the session doesn't exist or has expired
    }

    public function handleFileUpload($inputName, $configKey, $uploadDir, $maxFileSize = 5)
    {
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['name'] != '') {
            $fileTmpPath = $_FILES[$inputName]['tmp_name'];
            $fileName = $_FILES[$inputName]['name'];
            $fileSize = $_FILES[$inputName]['size'];
            $fileType = mime_content_type($fileTmpPath);

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($fileType, $allowedTypes)) {
                return ['result' => false, 'message' => 'Invalid file type. Only JPG, PNG, and GIF are allowed. - ' . $fileType];
            }

            if ($fileSize > $maxFileSize * 1024 * 1024) {
                return ['result' => false, 'message' => 'File size exceeds the maximum limit of ' . $maxFileSize . 'MB.'];
            }

            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // rename the file use timestamp to avoid duplicate
            $newFileName = $configKey . '_' . time() . '.' . $fileExtension;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Update the database with the new file name
                $updateResult = $this->updateSiteConfig($configKey, $newFileName);
                if ($updateResult['result']) {
                    return ['result' => true, 'message' => 'File uploaded and database updated successfully.', 'fileName' => $newFileName];
                } else {
                    return ['result' => false, 'message' => 'File uploaded but failed to update database. Please contact the system admin.'];
                }
            } else {
                return ['result' => false, 'message' => 'There was an error moving the uploaded file. Please contact the system admin.'];
            }
        }
        return null;
    }

    public function outputString($string)
    {
        if (is_string($string)) {
            return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        }
        return '';
    }

    public function addJobLog($job, $message) {
       try {
        $currentTimestamp = date('Y-m-d H:i:s');
        $sql = "INSERT INTO job_logs (job, msg, created_at) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sss', $job, $message, $currentTimestamp);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
       } catch (Exception $e) {
        
       }
    }
}
