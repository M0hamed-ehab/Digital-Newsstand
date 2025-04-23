<?php
class Database {
    private $host = "localhost";
    private $db_name = "news_api";
    private $username = "root";
    private $password = "";
    public $conn;

    public function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        if ($this->conn->connect_error) {
            die("Connection error: " . $this->conn->connect_error);
        }
        return $this->conn;
    }
}
