<?php
class Database {
    private $host = "localhost";
    private $db_name = "social_media";
    private $username = "root";
    private $password = "";
    public $conn;

    public function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        if ($this->conn->connect_error) {
            die(json_encode(["error" => "Database Connection failed: " . $this->conn->connect_error]));
        }
        return $this->conn;
    }
}
