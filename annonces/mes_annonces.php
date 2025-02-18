<?php
session_start();
require __DIR__.'/../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

// Récupérer les annonces de l'utilisateur connecté
$stmt = $conn->prepare("SELECT * FROM annonces WHERE utilisateur_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$annonces = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes annonces</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="container mt-5">
    <h2>Mes annonces</h2>
    <a href="ajouter_annonce.php" class="btn btn-success mb-3">Ajouter une annonce</a>
    <div class="row">
        <?php foreach ($annonces as $annonce) : ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <?php if ($annonce['image']) : ?>
                        <img src="<?= $annonce['image'] ?>" class="card-img-top" alt="Photo">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($annonce['ville']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($annonce['prix']) ?> €/mois</p>
                        <a href="modifier_annonce.php?id=<?= $annonce['id'] ?>" class="btn btn-warning">Modifier</a>
                        <a href="supprimer_annonce.php?id=<?= $annonce['id'] ?>" class="btn btn-danger">Supprimer</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

</html>
