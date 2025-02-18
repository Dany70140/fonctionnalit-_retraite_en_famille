<?php
session_start();
require '../config/config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les messages reçus et envoyés
$stmt = $conn->prepare("
    SELECT m.id, m.contenu, m.date_envoi, m.lu, 
           u.nom, u.prenom, u.id AS utilisateur_id,
           CASE WHEN m.expediteur_id = ? THEN 'envoyé' ELSE 'reçu' END AS type_message
    FROM messages m
    JOIN utilisateurs u ON (m.expediteur_id = u.id OR m.destinataire_id = u.id)
    WHERE m.expediteur_id = ? OR m.destinataire_id = ?
    ORDER BY m.date_envoi DESC
");
$stmt->execute([$user_id, $user_id, $user_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Messagerie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php require '../includes/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">📨 Ma messagerie</h2>

    <?php if (isset($_GET['success']) && $_GET['success'] == 'message_envoye'): ?>
        <div class="alert alert-success">✅ Message envoyé avec succès !</div>
    <?php endif; ?>

    <table class="table table-bordered table-striped mt-4">
        <thead>
        <tr>
            <th>Utilisateur</th>
            <th>Message</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($messages as $message): ?>
            <tr>
                <td><?= htmlspecialchars($message['prenom']) . " " . htmlspecialchars($message['nom']); ?></td>
                <td><?= htmlspecialchars(substr($message['contenu'], 0, 50)) . '...'; ?></td>
                <td><?= $message['date_envoi']; ?></td>
                <td><?= $message['lu'] ? '📖 Lu' : '📩 Non lu'; ?></td>
                <td><a href="conversation.php?id=<?= $message['utilisateur_id']; ?>" class="btn btn-primary btn-sm">Voir</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
