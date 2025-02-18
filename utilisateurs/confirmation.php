<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation d'inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php require '../includes/navbar.php'; ?>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-sm text-center" style="max-width: 500px; width: 100%;">
        <h2 class="text-success">Inscription réussie ! 🎉</h2>
        <p>Un email de confirmation vous a été envoyé. Veuillez cliquer sur le lien d'activation dans votre boîte mail pour activer votre compte.</p>
        <p class="text-muted">Si vous ne recevez pas l'email dans quelques minutes, vérifiez votre dossier spam.</p>
        <a href="connexion.php" class="btn btn-primary mt-3">Retour à la connexion</a>
    </div>
</div>

</body>
</html>
