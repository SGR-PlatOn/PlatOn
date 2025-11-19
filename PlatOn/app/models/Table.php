<?php

/**
 * Class Table
 * 
 * Modèle pour la gestion des tables du restaurant
 * 
 * @category Models
 * @package  SGR
 * @author   [Ton nom]
 */
class Table
{
    /** @var PDO Connexion à la base de données */
    private PDO $db;

    /**
     * Constructeur : initialise la connexion PDO
     */
    public function __construct()
    {
        $this->db = require __DIR__ . '/../config/database.php';
    }

    /**
     * Récupère toutes les tables triées par numéro
     *
     * @return array
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM tables ORDER BY numero");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour le statut d'une table
     *
     * @param int    $id     ID de la table
     * @param string $statut 'libre', 'occupée' ou 'réservée'
     * @return bool
     */
    public function updateStatus(int $id, string $statut): bool
    {
        $stmt = $this->db->prepare("UPDATE tables SET statut = ? WHERE id = ?");
        return $stmt->execute([$statut, $id]);
    }

    /**
     * Passe une table en statut "occupée"
     *
     * @param int $id
     * @return bool
     */
    public function occuper(int $id): bool
    {
        return $this->updateStatus($id, 'occupée');
    }

    /**
     * Libère une table (statut "libre")
     *
     * @param int $id
     * @return bool
     */
    public function liberer(int $id): bool
    {
        return $this->updateStatus($id, 'libre');
    }
}
