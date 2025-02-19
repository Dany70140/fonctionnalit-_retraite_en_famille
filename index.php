<?php
session_start();
require 'config/config.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - CDC Ma Retraite en Famille</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .feature-icon {
            background-color: #e8f0ff;
            border-radius: 50%;
            padding: 15px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 60px;
            height: 60px;
        }
        .feature-icon i {
            font-size: 30px;
            color: #1d4ed8;
        }
    </style>
</head>
<body class="bg-light">


<?php require_once __DIR__ . '/includes/navbar.php'; ?>


<div class="container-fluid bg-primary text-light text-center py-5">
    <h1 class="fw-bold">Bienvenue sur notre plateforme de colocation intergénérationnelle</h1>
    <p class="lead">Trouvez une chambre chez un senior ou accueillez un étudiant.</p>
    <a href="annonces/annonces.php" class="btn btn-light btn-lg">Voir les annonces</a>
</div>


<div class="container text-center my-5">
    <h2 class="fw-bold">Comment ça marche ?</h2>
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="feature-icon">
                <i class="bi bi-house"></i>
            </div>
            <h4 class="mt-3 fw-bold">Trouvez un logement</h4>
            <p>Parcourez les annonces et trouvez le logement qui vous correspond.</p>
        </div>
        <div class="col-md-3">
            <div class="feature-icon">
                <i class="bi bi-people"></i>
            </div>
            <h4 class="mt-3 fw-bold">Rencontrez</h4>
            <p>Échangez avec votre futur colocataire et apprenez à vous connaître.</p>
        </div>
        <div class="col-md-3">
            <div class="feature-icon">
                <i class="bi bi-heart"></i>
            </div>
            <h4 class="mt-3 fw-bold">Partagez</h4>
            <p>Vivez une expérience enrichissante basée sur l'entraide et le partage.</p>
        </div>
        <div class="col-md-3">
            <div class="feature-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h4 class="mt-3 fw-bold">En toute sécurité</h4>
            <p>Profitez d'un cadre sécurisé et d'un accompagnement personnalisé.</p>
        </div>
    </div>
</div>


<?php require_once __DIR__ . '/includes/footer.php'; ?>

</body>
</html>
