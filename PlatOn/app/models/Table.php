<?php
// app/models/Table.php
require_once __DIR__ . '/../config/database.php';

class Table // SINGULIER et majuscule
{
    private $conn;
    private $table_name = "tables";

    public $id;
    public $numero;
    public $statut;
    public $places;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lecture de toutes les tables
    public function readAll()
    {
        $query = "SELECT id, numero, statut, places FROM " . $this->table_name . " ORDER BY numero";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // NOUVELLE FONCTION : mise à jour du statut
    public function updateStatus($id, $statut)
    {
        $query = "UPDATE " . $this->table_name . " SET statut = :statut WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
