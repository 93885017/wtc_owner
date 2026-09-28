<?php
class Member
{
    private $conn;

    public function __construct($conn)
    {
        date_default_timezone_set('Asia/Hong_Kong');
        $this->conn = $conn;
    }

    public function login($username, $password)
    {
        $memberInfo = $this->checkLogin($username, $password);

        if ($memberInfo) {
            $memberId = $memberInfo['id'];
            if (!empty($memberInfo['access_token']) && !empty($memberInfo['expired_at']) && strtotime($memberInfo['expired_at']) > time()) {
                return [
                    'success' => false,
                    'error_code' => 'already_logged_in',
                ];
            }
            $accessToken = md5(uniqid(rand(), true));
            $expiredAt = date('Y-m-d H:i:s', strtotime('+6 minutes'));
            $currentTimeStamp = date('Y-m-d H:i:s');
            $sql = "UPDATE users SET updated_at = ?, access_token = ?, expired_at = ? WHERE id = ?";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, 'sssi', $currentTimeStamp, $accessToken, $expiredAt, $memberId);
            mysqli_stmt_execute($stmt);
            return [
                'success' => true,
                'access_token' => $accessToken,
                'user_id' => $memberId,
            ];
        }
        return [
            'success' => false,
            'error_code' => 'invalid_credentials',
        ];
    }

    public function checkLogin($username, $password)
    {
        $sql = "SELECT id, access_token, expired_at FROM users WHERE login = ? AND pswd = MD5(?) AND status = '1'";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            mysqli_stmt_bind_result($stmt, $id, $accessToken, $expiredAt);
            mysqli_stmt_fetch($stmt);
            return ['id' => $id, 'access_token' => $accessToken, 'expired_at' => $expiredAt];
        }
        return false;
    }

    public function checkIsValidAccessToken($inToken, $useId)
    {
        $sql = "SELECT expired_at FROM users WHERE access_token = ? AND status = '1' AND id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $inToken, $useId);

        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        $numRows = mysqli_stmt_num_rows($stmt);

        if ($numRows > 0) {

            // get the expired_at and id
            mysqli_stmt_bind_result($stmt, $expiredAt);
            mysqli_stmt_fetch($stmt);

            // check if the token is expired
            if (strtotime($expiredAt) < time()) {
                // if expired, clear the access token and expired_at
                // var_dump(strtotime($expiredAt) < time());exit;
                $this->clearAccessToken($useId);
                return false;
            }
            return true;
        }

        return false;
    }

    public function clearAccessToken($userId)
    {
        $currentTimeStamp = date('Y-m-d H:i:s');
        $sql = "UPDATE users SET access_token = '', expired_at = null, updated_at = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $currentTimeStamp,  $userId);
        mysqli_stmt_execute($stmt);
    }

    public function clearExpiredUsers() {
        // Update all users records when the expired_at is less than current time and the expired_at is not null
        $currentTimeStamp = date('Y-m-d H:i:s');
        $sql = "UPDATE users SET access_token = '', expired_at = null, updated_at = ? WHERE expired_at < ? AND expired_at IS NOT NULL";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $currentTimeStamp, $currentTimeStamp);
        mysqli_stmt_execute($stmt);
    }

    public function isTokenExists($userId) {
        $sql = "SELECT access_token FROM users WHERE id = ? AND status = '1'";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            mysqli_stmt_bind_result($stmt, $accessToken);
            mysqli_stmt_fetch($stmt);
            return !empty($accessToken);
        }
    }
    
    public function extendAccessTokenExpireTime($userId) {
        $expiredAt = date('Y-m-d H:i:s', strtotime('+6 minutes'));
        $currentTimeStamp = date('Y-m-d H:i:s');
        $sql = "UPDATE users SET expired_at = ?, updated_at = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssi', $expiredAt, $currentTimeStamp, $userId);
        mysqli_stmt_execute($stmt);
    }
}
