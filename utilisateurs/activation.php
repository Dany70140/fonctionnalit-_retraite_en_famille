<?php
require '../config/config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE token = ? AND statut_compte = 'inactif'");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $stmt = $conn->prepare("UPDATE utilisateurs SET statut_compte = 'actif', token = NULL WHERE id = ?");
        $stmt->execute([$user['id']]);

        echo "<h2>Compte activé avec succès ! 🎉 Vous pouvez maintenant <a href='connexion.php'>vous connecter</a>.</h2>";
    } else {
        echo "<h2>⚠ Ce lien est invalide ou le compte est déjà activé.</h2>";
    }
} else {
    echo "<h2>⚠ Token manquant.</h2>";
}
?>
