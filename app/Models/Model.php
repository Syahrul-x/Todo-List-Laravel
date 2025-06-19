<?php

class Model {
    protected $db;

    public function __construct() {
        $dbhostname = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $dbusername = $_ENV['DB_USERNAME'] ?? 'root';
        $dbpassword = $_ENV['DB_PASSWORD'] ?? '';
        $dbname = $_ENV['DB_DATABASE'] ?? 'FP01';
        $port = $_ENV['DB_PORT'] ?? '3306';

        $this->db = new mysqli($dbhostname, $dbusername, $dbpassword, $dbname, $port);

        if($this->db->connect_errno)
            die("Database connection error!");
    }
}
