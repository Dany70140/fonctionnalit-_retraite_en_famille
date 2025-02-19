<?php
session_start();
require 'config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /utilisateurs/connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT nom, prenom, email, statut FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT * FROM annonces WHERE utilisateur_id = ?");
$stmt->execute([$user_id]);
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de bord</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php require 'includes/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center"><i class="bi bi-person"></i> Tableau de bord</h2>

    <!-- Carte avec les informations utilisateur -->
    <div class="card p-4 shadow-sm mt-4">
        <h4>Bienvenue, <?= htmlspecialchars($user['prenom']) ." ". htmlspecialchars($user['nom']); ?> !</h4>
        <p><strong>Email :</strong> <?= htmlspecialchars($user['email']); ?></p>
        <p><strong>Statut :</strong> <?= ucfirst(htmlspecialchars($user['statut'])); ?></p>
        <a href="utilisateurs/edit_profil.php" class="btn btn-primary">Modifier mes informations</a>
    </div>
    <?php if ($user["statut"] !== 'etudiant') : ?>

    <div class="card p-4 shadow-sm mt-4">
        <h4><i class="bi bi-megaphone"></i> Mes annonces</h4>
        <?php if (count($annonces) > 0): ?>
            <table class="table mt-3">
                <thead>
                <tr>
                    <th>Prix (€)</th>
                    <th>Ville</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($annonces as $annonce): ?>
                    <tr>
                        <td><?= htmlspecialchars($annonce['prix']); ?></td>
                        <td><?= htmlspecialchars($annonce['ville']); ?></td>
                        <td>
                            <a href="annonces/modifier_annonces.php?id=<?= $annonce['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <a href="annonces/supprimer_annonces.php?id=<?= $annonce['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">Supprimer</a>
                        </td>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune annonce publiée pour le moment.</p>
        <?php endif; ?>
        <a href="annonces/ajouter_annonce.php" class="btn btn-success mt-3"><i class="bi bi-plus"></i> Ajouter une annonce</a>
    </div>

    <?php endif; ?>


</div>

</body>
</html>
