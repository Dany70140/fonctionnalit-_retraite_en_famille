<?php
session_start();
require '../config/config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Vérifier si un utilisateur est sélectionné pour la conversation
if (!isset($_GET['id'])) {
    die("❌ Aucun utilisateur sélectionné.");
}

$autre_utilisateur_id = $_GET['id'];

// Vérifier que l'utilisateur existe
$stmt = $conn->prepare("SELECT nom, prenom FROM utilisateurs WHERE id = ?");
$stmt->execute([$autre_utilisateur_id]);
$autre_utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$autre_utilisateur) {
    die("❌ Utilisateur introuvable.");
}

// Récupérer la conversation entre les deux utilisateurs
$stmt = $conn->prepare("
    SELECT * FROM messages 
    WHERE (expediteur_id = ? AND destinataire_id = ?) 
       OR (expediteur_id = ? AND destinataire_id = ?) 
    ORDER BY date_envoi ASC
");
$stmt->execute([$user_id, $autre_utilisateur_id, $autre_utilisateur_id, $user_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Marquer les messages reçus comme "lus"
$stmt = $conn->prepare("
    UPDATE messages SET lu = 1 
    WHERE destinataire_id = ? AND expediteur_id = ?
");
$stmt->execute([$user_id, $autre_utilisateur_id]);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Conversation avec <?= htmlspecialchars($autre_utilisateur['prenom']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .message-box { max-width: 600px; margin: auto; }
        .message { padding: 10px; border-radius: 8px; margin-bottom: 5px; }
        .sent { background-color: #DCF8C6; text-align: right; }
        .received { background-color: #E8E8E8; text-align: left; }
    </style>
</head>
<body class="bg-light">
<?php require '../includes/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">🗨️ Conversation avec <?= htmlspecialchars($autre_utilisateur['prenom']) . " " . htmlspecialchars($autre_utilisateur['nom']); ?></h2>

    <div class="message-box mt-4 p-3 bg-white shadow-sm rounded">
        <?php foreach ($messages as $message): ?>
            <div class="message <?= $message['expediteur_id'] == $user_id ? 'sent' : 'received' ?>">
                <p><?= nl2br(htmlspecialchars($message['contenu'])) ?></p>
                <small class="text-muted"><?= $message['date_envoi'] ?></small>
            </div>
        <?php endforeach; ?>
    </div>

    <form method="POST" class="mt-4 message-box">
        <div class="input-group">
            <input type="text" name="contenu" class="form-control" placeholder="Écrire un message..." required>
            <button type="submit" class="btn btn-success">📩 Envoyer</button>
        </div>
    </form>

    <?php
    // Envoi du message si formulaire soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['contenu'])) {
        $contenu = trim($_POST['contenu']);
        $stmt = $conn->prepare("INSERT INTO messages (expediteur_id, destinataire_id, contenu) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $autre_utilisateur_id, $contenu]);

        // Recharger la page pour voir le nouveau message
        header("Location: conversation.php?id=$autre_utilisateur_id");
        exit();
    }
    ?>

</div>
</body>
</html>
