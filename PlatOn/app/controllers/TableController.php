<?php
require_once 'C:\xampp\htdocs\PlatOn\config\database.php';
require_once 'C:\xampp\htdocs\PlatOn\app\models\Table.php';

class TableController{
    private $db;
    private $tables;

    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
        $this->tables = new tables($this->db);
    }

    public function index(){
        $stmt = $this->tables->ReadAll();
        $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'C:\xampp\htdocs\PlatOn\app\views\table\map.php';
    }

}
