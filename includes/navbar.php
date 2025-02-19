<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php require_once __DIR__ . '/../config/functions.php'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="/index.php"><i class="bi bi-house"></i> Ma Retraite en Famille</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (estConnecte()): ?>
                    <li class="nav-item"><a class="nav-link" href="/utilisateurs/profil.php"><i class="bi bi-person"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="../dashboard.php"><i class="bi bi-clipboard-data"></i> Tableau de bord</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-dark" href="/utilisateurs/deconnexion.php"><i class="bi bi-box-arrow-in-right"></i> Déconnexion</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/annonces/annonces.php">Annonces</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-success text-dark" href="/utilisateurs/connexion.php"><i class="bi bi-key"></i> Connexion</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-primary text-dark" href="/utilisateurs/inscription.php"><i class="bi bi-pencil-square"></i> Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
