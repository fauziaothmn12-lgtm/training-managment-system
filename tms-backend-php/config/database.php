<?php
class Database {
    private $host = "localhost";
    private $db_name = "tms_db";
    private $username = "tms_user"; // au username yako ya MySQL
    private $password = "password123";     // password yako ya MySQL (kama ipo)
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo json_encode([
                "status" => "error",
                "message" => "Database Connection Error: " . $exception->getMessage()
            ]);
            exit();
        }

        return $this->conn;
    }
}
?>