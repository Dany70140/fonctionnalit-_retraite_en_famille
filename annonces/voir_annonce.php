<?php
session_start();
require '../config/config.php';

// Vérifier si l'ID de l'annonce est bien passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Aucune annonce sélectionnée.");
}

$id_annonce = $_GET['id'];

// Récupérer les informations de l'annonce depuis la BDD
$stmt = $conn->prepare("SELECT * FROM annonces WHERE id = ?");
$stmt->execute([$id_annonce]);
$annonce = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$annonce) {
    die("❌ Cette annonce n'existe pas.");
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Voir l'annonce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require '../includes/navbar.php'; ?>

<div class="container mt-5">

    <div class="card mt-3">
        <div class="card-body">
            <p class="card-text"><strong>Image :</strong> <img src="<?= $annonce['image'] ?>" class="card-img-top" alt="Photo de l'annonce"></p>
            <p class="card-text"><strong>Ville :</strong> <?= htmlspecialchars($annonce['ville']) ?></p>
            <p class="card-text"><strong>Prix :</strong> <?= htmlspecialchars($annonce['prix']) ?> €</p>
            <p class="card-text"><strong>Surface :</strong> <?= htmlspecialchars($annonce['surface']) ?> m²</p>
            <p class="card-text"><strong>Meublé :</strong> <?= $annonce['meublement'] ? 'Oui' : 'Non' ?></p>
            <p class="card-text"><strong>Description :</strong> <?= nl2br(htmlspecialchars($annonce['description'])) ?></p>

            <a href='mailto:<?= htmlspecialchars($annonce['contact_email']) ?>' class="btn btn-primary">📩 Contacter</a>
        </div>
    </div>
</div>

</body>
</html>
