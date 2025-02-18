<?php
session_start();
require '../config/config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Vérifier si un destinataire est précisé
if (!isset($_GET['destinataire_id'])) {
    die("❌ Aucun destinataire sélectionné.");
}

$destinataire_id = $_GET['destinataire_id'];

// Vérifier que le destinataire existe
$stmt = $conn->prepare("SELECT nom, prenom FROM utilisateurs WHERE id = ?");
$stmt->execute([$destinataire_id]);
$destinataire = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$destinataire) {
    die("❌ Destinataire introuvable.");
}

// Envoi du message
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contenu = trim($_POST['contenu']);

    if (!empty($contenu)) {
        $stmt = $conn->prepare("INSERT INTO messages (expediteur_id, destinataire_id, contenu) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $destinataire_id, $contenu]);

        header("Location: messages.php?success=message_envoye");
        exit();
    } else {
        $error = "❌ Le message ne peut pas être vide.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Envoyer un message</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php require '../includes/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">📩 Envoyer un message à <?= htmlspecialchars($destinataire['prenom']) . " " . htmlspecialchars($destinataire['nom']); ?></h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm mt-4">
        <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="contenu" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-success w-100">📤 Envoyer</button>
    </form>
</div>

</body>
</html>
