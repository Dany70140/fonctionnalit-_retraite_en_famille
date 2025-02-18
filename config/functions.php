<?php
require 'config.php';

// Vérifier si un utilisateur est connecté
function estConnecte() {
    return isset($_SESSION['user_id']);
}

// Récupérer les informations de l'utilisateur
function getUtilisateur($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Rediriger si l'utilisateur n'est pas connecté
function redirectionSiNonConnecte() {
    if (!estConnecte()) {
        header("Location: /utilisateurs/connexion.php");
        exit();
    }
}
?>
