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

    // Affiche le plan de salle
    public function index()
    {
        $result = $this->table->readAll();
        $tables = $result->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/../views/table/map.php';
    }

    // NOUVELLE MÉTHODE : mise à jour via POST
    public function update()
    {
        if ($_POST['id'] && $_POST['statut']) {
            $id = (int)$_POST['id'];
            $statut = $_POST['statut'];

            // Sécurité : on n'accepte que ces 3 valeurs
            if (in_array($statut, ['libre', 'occupée', 'réservée'])) {
                $this->table->updateStatus($id, $statut);
            }
        }
        // On redirige toujours vers le plan de salle
        header("Location: index.php");
        exit;
    }
}

$controller = new TableController();

if (isset($_POST['id'])) {
    // Si on reçoit un POST → mise à jour
    $controller->update();
} else {
    // Sinon → affichage normal
    $controller->index();
}
