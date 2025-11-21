<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Tables</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="mb-4 text-center text-primary">Liste des Tables</h1>

        <?php if (empty($tables)):?>
            <div class="alert alert-info text-center">Aucune table n'est encore disponible.</div>
        <?php else: ?>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Numero</th>
                        <th>Statut</th>
                        <th>Places</th>
                    </tr>
                </thead>
            <tbody>
                <?php foreach ($tables as $row): ?>
                    <tr>
                        <td> <?= htmlspecialchars($row['id']) ?></td>

                        <td> <?= htmlspecialchars($row['numero']) ?></td>

                        <td> <?= htmlspecialchars($row['statut']) ?></td>

                        <td> <?= htmlspecialchars($row['']) ?></td>
                    </tr>
                    <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</body>
</html>
