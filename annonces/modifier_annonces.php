<?php
session_start();
require '../config/config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../utilisateurs/connexion.php");
    exit();
}

// Vérifier si l'ID de l'annonce est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ ID d'annonce invalide.");
}

$annonce_id = $_GET['id'];

// Récupérer l'annonce
$stmt = $conn->prepare("SELECT * FROM annonces WHERE id = ? AND utilisateur_id = ?");
$stmt->execute([$annonce_id, $_SESSION['user_id']]);
$annonce = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$annonce) {
    die("❌ Annonce introuvable ou vous n'êtes pas autorisé à la modifier.");
}

// Mise à jour de l'annonce
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prix = (float) $_POST['prix'];
    $ville = trim($_POST['ville']);
    $meublement = trim($_POST['meublement']);
    $description = trim($_POST['description']);

    if ($prix > 0 && !empty($ville) && !empty($meublement) && !empty($description)) {
        $stmt = $conn->prepare("UPDATE annonces SET prix = ?, ville = ?, meublement = ?, description = ? WHERE id = ?");
        $stmt->execute([$prix, $ville, $meublement, $description, $annonce_id]);

        header("Location: ../dashboard.php");
        exit();
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier annonce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php require '../includes/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">📝 Modifier mon annonce</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm mt-4">
        <div class="mb-3">
            <label class="form-label">Prix (€)</label>
            <input type="number" name="prix" class="form-control" value="<?= htmlspecialchars($annonce['prix']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ville</label>
            <input type="text" name="ville" class="form-control" value="<?= htmlspecialchars($annonce['ville']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Meublement</label>
            <select name="meublement" class="form-control">
                <option value="meublé">Meublé</option>
                <option value="non-meublé">Non meublé</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <input type="text" name="description" class="form-control" value="<?= htmlspecialchars($annonce['description']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Enregistrer les modifications</button>
    </form>
</div>

</body>
</html>
