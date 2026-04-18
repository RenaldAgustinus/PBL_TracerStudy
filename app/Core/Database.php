<?php

namespace App\Core;

use PDO;

class Database {
    private static $instance;
    private $connection;

    private function __construct() {
        $this->connection = new PDO('mysql:host=localhost;dbname=pbl_tracerstudy', 'root', ' ');
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
