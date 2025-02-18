<?php


// Paramètres de connexion à la base de données
$host = 'localhost';
$dbname = 'ma_retraite_famille';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Définition du fuseau horaire
date_default_timezone_set('Europe/Paris');

