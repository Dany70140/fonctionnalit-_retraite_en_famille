<?php
session_start();
require __DIR__.'/../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ville = $_POST['ville'];
    $code_postal = $_POST['code_postal'];
    $prix = $_POST['prix'];
    $surface = $_POST['surface'];
    $meublement = $_POST['meublement'];
    $description = $_POST['description'];
    $image = "";

    // Gestion de l'upload d'image
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $image = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }

    // Insérer l'annonce en base de données
    $stmt = $conn->prepare("INSERT INTO annonces (utilisateur_id, ville, code_postal, prix, surface, meublement, description, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $ville, $code_postal, $prix, $surface, $meublement, $description, $image]);

    header("Location: mes_annonces.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une annonce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="container mt-5">
    <h2>Ajouter une annonce</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Ville</label>
            <input type="text" name="ville" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Code postal</label>
            <input type="text" name="code_postal" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Prix (€)</label>
            <input type="number" name="prix" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Surface (m²)</label>
            <input type="number" name="surface" class="form-control" required>
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
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Photo</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Publier</button>
    </form>
</div>
</body>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

</html>
