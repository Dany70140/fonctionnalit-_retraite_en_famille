<?php

session_start();

require __DIR__ .'/../config/config.php';

$ville = $_GET['ville'] ?? '';
$prix_max = $_GET['prix_max'] ?? '';
$surface_min = $_GET['surface_min'] ?? '';
$meublement = $_GET['meublement'] ?? '';

$query = "SELECT * FROM annonces WHERE 1=1";
$params = [];

if (!empty($ville)) {
    $query .= " AND ville LIKE ?";
    $params[] = "%$ville%";
}
if (!empty($prix_max)) {
    $query .= " AND prix <= ?";
    $params[] = $prix_max;
}
if (!empty($surface_min)) {
    $query .= " AND surface >= ?";
    $params[] = $surface_min;
}
if (!empty($meublement) && $meublement !== 'tous') {
    $query .= " AND meublement = ?";
    $params[] = $meublement;
}

$stmt = $conn->prepare($query);
$stmt->execute($params);
$annonces = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des annonces</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Rechercher une chambre</h2>
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="ville" class="form-control" placeholder="Ville" value="<?= htmlspecialchars($ville) ?>">
        </div>
        <div class="col-md-3">
            <input type="number" name="prix_max" class="form-control" placeholder="Prix max (€)" value="<?= htmlspecialchars($prix_max) ?>">
        </div>
        <div class="col-md-3">
            <input type="number" name="surface_min" class="form-control" placeholder="Surface min (m²)" value="<?= htmlspecialchars($surface_min) ?>">
        </div>
        <div class="col-md-3">
            <select name="meublement" class="form-control">
                <option value="tous">Tous</option>
                <option value="meublé" <?= ($meublement == 'meublé') ? 'selected' : '' ?>>Meublé</option>
                <option value="non-meublé" <?= ($meublement == 'non-meublé') ? 'selected' : '' ?>>Non meublé</option>
            </select>
        </div>
        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </div>
    </form>

    <h2 class="mb-4">Résultats</h2>
    <div class="row">
        <?php if (empty($annonces)) : ?>
            <p class="text-muted">Aucune annonce ne correspond à vos critères.</p>
        <?php else : ?>
            <?php foreach ($annonces as $annonce) : ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <?php if ($annonce['image']) : ?>
                            <img src="<?= $annonce['image'] ?>" class="card-img-top" alt="Photo de l'annonce">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($annonce['ville']) ?> (<?= htmlspecialchars($annonce['code_postal']) ?>)</h5>
                            <p class="card-text"><?= htmlspecialchars($annonce['prix']) ?> €/mois, <?= htmlspecialchars($annonce['surface']) ?> m²</p>
                            <a href="voir_annonce.php?id=<?= $annonce['id'] ?>" class="btn btn-primary">Détails</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

</html>
