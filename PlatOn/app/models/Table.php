<?php
// app/models/Table.php
require_once **DIR** . '/../config/database.php';
class Tables {
    private $conn;
    private $table_name = "tables";
    public $id;
    public $numero;
    public $statut;
    public $places;
    public function __construct($db) {
        $this->conn = $db;
    }
    public function readAll() {
        $query = "SELECT id, numero, statut, places FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
