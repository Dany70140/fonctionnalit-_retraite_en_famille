<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php require_once __DIR__ . '/../config/functions.php'; ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/index.php">🏡 Ma Retraite en Famille</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (estConnecte()): ?>
                    <li class="nav-item"><a class="nav-link" href="/utilisateurs/message.php">📩 Messagerie</a></li>
                    <li class="nav-item"><a class="nav-link" href="/utilisateurs/profil.php">👤 Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="../dashboard.php">🖼️ Tableau de bord</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="/utilisateurs/deconnexion.php">🚪 Déconnexion</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/annonces/annonces.php">🛏️ Annonces</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-success text-white" href="/utilisateurs/connexion.php">🔑 Connexion</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-primary text-white" href="/utilisateurs/inscription.php">📝 Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
