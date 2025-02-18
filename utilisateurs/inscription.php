<?php
require '../config/config.php';

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $age = filter_var($_POST['age'], FILTER_VALIDATE_INT);
    $statut = $_POST['statut'];
    $telephone = htmlspecialchars($_POST['telephone']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mot_de_passe = $_POST['mot_de_passe'];
// Vérification de l'email déjà existant
    $stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $errors[] = "Cet email est déjà utilisé. Veuillez en choisir un autre.";
    }

    if (!$nom || !$prenom || !$age || !$statut || !$email || !$mot_de_passe) {
        $errors[] = "Tous les champs sont requis.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide.";
    }



    if (strlen($mot_de_passe) < 6) {
        $errors[] = "Le mot de passe doit avoir au moins 6 caractères.";
    }

    if (empty($errors)) {
        $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);
        $token = bin2hex(random_bytes(50)); // Génération d'un token unique
        $statut_compte = "inactif";

        $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, prenom, age, statut, telephone, email, mot_de_passe, token, statut_compte) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $age, $statut, $telephone, $email, $mot_de_passe_hash, $token, $statut_compte]);

        // Envoi de l'email d'activation
        $activation_link = "http://ton-site.com/activation.php?token=$token";
        $subject = "Activation de votre compte";
        $message = "Bonjour $prenom,\n\nCliquez sur le lien ci-dessous pour activer votre compte :\n\n$activation_link";
        $headers = "From: no-reply@ton-site.com";

        mail($email, $subject, $message, $headers);

        header("Location: confirmation.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php require '../includes/navbar.php'; ?>

<div class="container d-flex justify-content-center align-items-center vh-50 mt-5">
    <div class="card p-4 shadow-sm" style="max-width: 400px; width: 100%;">
        <h2 class="text-center">Créer un compte</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error) echo "<p class='mb-0'>$error</p>"; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Prénom</label>
                <input type="text" name="prenom" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Âge</label>
                <input type="number" name="age" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Statut</label>
                <select name="statut" class="form-select">
                    <option value="senior">Senior</option>
                    <option value="etudiant">Étudiant</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Téléphone</label>
                <input type="text" name="telephone" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="mot_de_passe" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">S'inscrire</button>
        </form>
    </div>
</div>

</body>
</html>
