<?php
session_start();
require '../config/config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../utilisateurs/connexion.php");
    exit();
}

// Vérifier si l'ID de l'annonce est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ ID d'annonce invalide.");
}

$annonce_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Vérifier si l'annonce appartient à l'utilisateur connecté
$stmt = $conn->prepare("SELECT id FROM annonces WHERE id = ? AND utilisateur_id = ?");
$stmt->execute([$annonce_id, $user_id]);
$annonce = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$annonce) {
    die("❌ Annonce introuvable ou vous n'êtes pas autorisé à la supprimer.");
}

// Supprimer l'annonce
$stmt = $conn->prepare("DELETE FROM annonces WHERE id = ?");
$stmt->execute([$annonce_id]);

// Redirection après suppression
header("Location: ../dashboard.php");
exit();
?>
