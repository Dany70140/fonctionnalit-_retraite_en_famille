<?php
session_start();
require '../config/config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les informations de l'utilisateur
$stmt = $conn->prepare("SELECT nom, prenom, email, age, statut, telephone FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("❌ Utilisateur introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php require '../includes/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center"><i class="bi bi-person"></i> Mon Profil</h2>

    <div class="card p-4 shadow-sm mt-4">
        <h4><?= htmlspecialchars($user['prenom']) . " " . htmlspecialchars($user['nom']); ?></h4>
        <p><strong>Email :</strong> <?= htmlspecialchars($user['email']); ?></p>
        <p><strong>Âge :</strong> <?= htmlspecialchars($user['age']); ?> ans</p>
        <p><strong>Statut :</strong> <?= ucfirst(htmlspecialchars($user['statut'])); ?></p>
        <p><strong>Téléphone :</strong> <?= htmlspecialchars($user['telephone']); ?></p>
        <a href="edit_profil.php" class="btn btn-primary">✏️ Modifier mon profil</a>

    </div>
</div>

</body>
</html>
