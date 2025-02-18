<?php
session_start();
require '../config/config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Vérifier si un destinataire est sélectionné
if (!isset($_GET['id'])) {
    die("❌ Aucun utilisateur sélectionné pour l'avis.");
}

$destinataire_id = $_GET['id'];

// Vérifier que l'utilisateur existe
$stmt = $conn->prepare("SELECT nom, prenom FROM utilisateurs WHERE id = ?");
$stmt->execute([$destinataire_id]);
$destinataire = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$destinataire) {
    die("❌ Utilisateur introuvable.");
}

// Traitement du formulaire d'avis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $note = $_POST['note'];
    $commentaire = trim($_POST['commentaire']);

    // Vérifier que la note est valide
    if ($note < 1 || $note > 5) {
        die("❌ Note invalide.");
    }

    // Vérifier si un avis a déjà été laissé
    $stmt = $conn->prepare("SELECT id FROM avis WHERE auteur_id = ? AND destinataire_id = ?");
    $stmt->execute([$user_id, $destinataire_id]);
    if ($stmt->fetch()) {
        die("❌ Vous avez déjà laissé un avis sur cet utilisateur.");
    }

    // Insérer l'avis en BDD
    $stmt = $conn->prepare("INSERT INTO avis (auteur_id, destinataire_id, note, commentaire) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $destinataire_id, $note, $commentaire]);

    echo "<script>alert('✅ Avis ajouté avec succès !'); window.location='avis.php?id=$destinataire_id';</script>";
}

// Récupérer les avis de cet utilisateur
$stmt = $conn->prepare("
    SELECT a.note, a.commentaire, a.date_publication, u.nom, u.prenom
    FROM avis a 
    JOIN utilisateurs u ON a.auteur_id = u.id
    WHERE a.destinataire_id = ?
    ORDER BY a.date_publication DESC
");
$stmt->execute([$destinataire_id]);
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Avis sur <?= htmlspecialchars($destinataire['prenom']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require '../includes/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">⭐ Avis sur <?= htmlspecialchars($destinataire['prenom']) . " " . htmlspecialchars($destinataire['nom']) ?></h2>

    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">Laisser un avis</h5>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Note :</label>
                    <select name="note" class="form-select" required>
                        <option value="5">⭐️⭐️⭐️⭐️⭐️ - Excellent</option>
                        <option value="4">⭐️⭐️⭐️⭐️ - Très bien</option>
                        <option value="3">⭐️⭐️⭐️ - Moyen</option>
                        <option value="2">⭐️⭐️ - Mauvais</option>
                        <option value="1">⭐️ - Horrible</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Commentaire :</label>
                    <textarea name="commentaire" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">📝 Publier l'avis</button>
            </form>
        </div>
    </div>

    <h3 class="mt-4">📝 Avis existants</h3>
    <?php if (empty($avis)): ?>
        <p>Aucun avis pour le moment.</p>
    <?php else: ?>
        <?php foreach ($avis as $a): ?>
            <div class="card mt-2">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($a['prenom']) . " " . htmlspecialchars($a['nom']) ?> (<?= $a['note'] ?>⭐)</h5>
                    <p class="card-text"><?= nl2br(htmlspecialchars($a['commentaire'])) ?></p>
                    <p class="text-muted"><?= $a['date_publication'] ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
