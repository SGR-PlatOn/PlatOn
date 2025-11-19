<?php

require_once __DIR__ . '/../models/Table.php';

/**
 * Class TableController
 *
 * Contrôleur responsable de la gestion du plan de salle
 *
 * @category Controllers
 * @package  SGR
 * @author   [Ton nom]
 */
class TableController
{
    private Table $tableModel;

    /**
     * Constructeur : initialise le modèle Table
     */
    public function __construct()
    {
        $this->tableModel = new Table();
    }

    /**
     * Affiche le plan de salle (page principale)
     *
     * @return void
     */
    public function map(): void
    {
        $tables = $this->tableModel->getAll();
        require __DIR__ . '/../views/table/map.php';
    }

    /**
     * Met à jour le statut d'une table (AJAX ou POST classique)
     *
     * @return void
     */
    public function update(): void
    {
        if (!isset($_POST['id']) || !isset($_POST['statut'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Données manquantes']);
            exit;
        }

        $id     = (int)$_POST['id'];
        $statut = $_POST['statut'];

        // Validation simple du statut autorisé
        $statutsAutorises = ['libre', 'occupée', 'réservée'];
        if (!in_array($statut, $statutsAutorises, true)) {
            $statut = 'occupée'; // fallback sécurisé
        }

        $success = $this->tableModel->updateStatus($id, $statut);

        // Réponse AJAX
        if ($this->isAjaxRequest()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }

        // Sinon redirect classique
        header('Location: /table/map');
        exit;
    }

    /**
     * Détecte si la requête est en AJAX
     */
    private function isAjaxRequest(): bool
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
    }
}
