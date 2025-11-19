<?php
/**
 * Vue : Plan de salle (Table Map)
 * 
 * Affiche le plan de salle interactif avec Bootstrap 5
 * 
 * @category Views
 * @package  SGR
 * @author   [Ton nom]
 */
 
// Sécurité : empêcher l'accès direct au fichier
if (!defined('INDEX_ACCESS')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Accès direct interdit');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SGR • Plan de Salle</title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        body { background: #f8f9fa; }
        .table-card {
            width: 150px;
            height: 150px;
            margin: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 18px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }
        .table-card:hover { transform: translateY(-8px); box-shadow: 0 12px 25px rgba(0,0,0,0.2); }
        
        .libre    { background: #d1e7dd; border: 4px solid #0f5132; color: #0f5132; }
        .occupée  { background: #f8d7da; border: 4px solid #58151c; color: #58151c; }
        .réservée { background: #fff3cd; border: 4px solid #664d03; color: #664d03; }
        
        .places { font-size: 0.9rem; margin-top: 5px; opacity: 0.9; }
    </style>
</head>
<body>

<div class="container py-5">
    <h1 class="text-center mb-2 fw-bold text-primary">
        <i class="bi bi-shop-window"></i> Plan de Salle
    </h1>
    <p class="text-center text-muted mb-5">Cliquez sur une table pour changer son statut</p>

    <!-- Légende -->
    <div class="text-center mb-5">
        <span class="badge bg-success fs-6 p-3 me-3">Libre</span>
        <span class="badge bg-danger fs-6 p-3 me-3">Occupée</span>
        <span class="badge bg-warning fs-6 p-3 text-dark">Réservée</span>
    </div>

    <!-- Grille des tables -->
    <div class="d-flex flex-wrap justify-content-center">
        <?php foreach ($tables as $table): ?>
            <?php 
                $nextStatus = $table['statut'] === 'libre' ? 'occupée' : 'libre';
                $statusLabel = ucfirst($table['statut']);
            ?>
            <div class="table-card <?= htmlspecialchars($table['statut']) ?>"
                 onclick="changeStatus(<?= $table['id'] ?>, '<?= $nextStatus ?>')">
                <div class="fs-4">Table <?= $table['numero'] ?></div>
                <div class="places"><?= $table['places'] ?> places</div>
                <small class="mt-2 opacity-75"><?= $statusLabel ?></small>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Message de succès -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success text-center mt-4">
            <i class="bi bi-check-circle"></i> Statut mis à jour avec succès !
        </div>
    <?php endif; ?>
</div>

<script>
function changeStatus(id, nouveauStatut) {
    if (!confirm(`Passer cette table en "${nouveauStatut}" ?`)) return;

    const formData = new FormData();
    formData.append('id', id);
    formData.append('statut', nouveauStatut);

    fetch('/table/update', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur lors de la mise à jour');
        }
    })
    .catch(() => alert('Erreur réseau'));
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
