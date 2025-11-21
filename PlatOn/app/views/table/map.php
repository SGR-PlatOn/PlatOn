<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SGR - Plan de Salle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .table-card {
            width: 160px; height: 160px; margin: 20px; cursor: pointer;
            border-radius: 20px; box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            transition: all 0.4s ease; text-align: center; padding-top: 20px;
            font-weight: bold; font-size: 1.4rem;
        }
        .table-card:hover { transform: scale(1.1); }
        .libre    { background: #d4edda; border: 5px solid #28a745; color: #155724; }
        .occupée  { background: #f8d7da; border: 5px solid #dc3545; color: #721c24; }
        .réservée { background: #fff3cd; border: 5px solid #ffc107; color: #856404; }
    </style>
</head>
<body>
<div class="container py-5 text-center">
    <h1 class="display-5 fw-bold text-primary mb-5"><i class="bi bi-shop-window"></i> Plan de Salle</h1>

    <div class="d-flex flex-wrap justify-content-center">
        <?php foreach ($tables as $table): ?>
            <?php 
                $current = $table['statut'];
                $next = ($current === 'libre') ? 'occupée' : 'libre';
            ?>
            <div class="table-card <?= $current ?>" 
                 onclick="if(confirm('Passer la table <?= $table['numero'] ?> en <?= $next ?> ?')) {
                     document.getElementById('form-<?= $table['id'] ?>').submit();
                 }">
                Table <?= $table['numero'] ?><br>
                <small><?= $table['places'] ?> places</small><br>
                <span class="badge bg-light text-dark mt-2"><?= ucfirst($current) ?></span>
            </div>

            <!-- Formulaire caché pour chaque table -->
            <form id="form-<?= $table['id'] ?>" method="POST" class="d-none">
                <input type="hidden" name="id" value="<?= $table['id'] ?>">
                <input type="hidden" name="statut" value="<?= $next ?>">
            </form>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>

