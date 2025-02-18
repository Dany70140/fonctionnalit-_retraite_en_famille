<?php
session_start();
require '../config/config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les informations actuelles de l'utilisateur
$stmt = $conn->prepare("SELECT nom, prenom, email, age, statut, telephone FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("❌ Utilisateur introuvable.");
}

// Mise à jour du profil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $age = (int) $_POST['age'];
    $statut = trim($_POST['statut']);
    $telephone = trim($_POST['telephone']);

    // Vérifier que tous les champs sont remplis
    if (!empty($nom) && !empty($prenom) && filter_var($email, FILTER_VALIDATE_EMAIL) && $age > 0 && !empty($statut)) {
        $stmt = $conn->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, age = ?, statut = ?, telephone = ? WHERE id = ?");
        $stmt->execute([$nom, $prenom, $email, $age, $statut, $telephone, $user_id]);

        header("Location: profil.php?success=update");
        exit();
    } else {
        $error = "Tous les champs sont obligatoires et doivent être valides.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier mon profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php require '../includes/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">✏️ Modifier mon profil</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm mt-4">
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Prénom</label>
            <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($user['prenom']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Âge</label>
            <input type="number" name="age" class="form-control" value="<?= htmlspecialchars($user['age']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Statut</label>
            <select name="statut" class="form-select" required>
                <option value="senior" <?= $user['statut'] == 'senior' ? 'selected' : ''; ?>>Senior</option>
                <option value="etudiant" <?= $user['statut'] == 'etudiant' ? 'selected' : ''; ?>>Étudiant</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Téléphone</label>
            <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($user['telephone']); ?>">
        </div>
        <button type="submit" class="btn btn-success w-100">💾 Enregistrer</button>
    </form>
</div>

</body>
</html>
