<?php
// generate connect mysql database class
class dbConnect {
    private $conn;
    private $host;
    private $port;
    private $user;
    private $pass;
    private $database;
    private $errorPage;


    public function __construct($host, $username, $password, $dbname, $errorPage = '') {
        $this->host = $host;
        $this->user = $username;
        $this->pass = $password;
        $this->database = $dbname;
        $this->errorPage = $errorPage ? $errorPage : 'error.php';
    }

    function connect($autoRedirect = true) { 
        try {
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->database);
        } catch (Exception $e) {
            // echo "Failed to connect to MySQL: " . mysqli_connect_error();
            $this->conn = null;

            // error_log("Failed to connect to MySQL: " . mysqli_connect_error());
            
            if ($autoRedirect) {
                header("Location: " . $this->errorPage);
            }
        }
        // return database handler
        return $this->conn;
    }
}
