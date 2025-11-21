<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/models/Table.php';

class TableController
{
    private $db;
    private $table;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->table = new Table($this->db);
    }

    public function index()
    {
        $stmt = $this->table->readAll();
        $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);  // ← $tables bien créée

        // ON CHARGE LA VUE (et $tables est automatiquement disponible dedans)
        require __DIR__ . '/../views/table/map.php';
    }

    public function update()
    {
        if ($_POST['id'] && $_POST['statut']) {
            $id = (int)$_POST['id'];
            $statut = $_POST['statut'];
            if (in_array($statut, ['libre', 'occupée', 'réservée'])) {
                $this->table->updateStatus($id, $statut);
            }
        }
        header("Location: index.php");
        exit;
    }
}

// ================= ROUTEUR =================
$controller = new TableController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->update();
} else {
    $controller->index();
}
