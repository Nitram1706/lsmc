<?php
require 'config/database.php';
include 'templates/header.php';

// 1. On récupère les informations du patient à modifier
if (!isset($_GET['id'])) {
    header("Location: dossiers_medicaux.php");
    exit();
}
$id_patient = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
$stmt->execute([$id_patient]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$patient) {
    header("Location: dossiers_medicaux.php");
    exit();
}

// 2. On traite le formulaire quand il est envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_prenom = $_POST['nom_prenom'];
    $date_naissance = $_POST['date_naissance'];
    $nationalite = $_POST['nationalite'];
    $maladies = $_POST['maladies'];
    $informations_supplementaires = $_POST['informations_supplementaires'];
    $id_a_modifier = $_POST['id_patient'];

    $update_stmt = $pdo->prepare("
        UPDATE patients 
        SET nom_prenom = ?, date_naissance = ?, nationalite = ?, maladies = ?, informations_supplementaires = ?
        WHERE id = ?
    ");
    $update_stmt->execute([$nom_prenom, $date_naissance, $nationalite, $maladies, $informations_supplementaires, $id_a_modifier]);

    header("Location: dossiers_medicaux.php");
    exit();
}
?>

<h2>Modifier le dossier de : <?php echo htmlspecialchars($patient['nom_prenom']); ?></h2>

<form method="POST">
    <input type="hidden" name="id_patient" value="<?php echo $patient['id']; ?>">

    <div class="mb-3">
        <label for="nom_prenom" class="form-label">Nom et Prénom <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="nom_prenom" id="nom_prenom" value="<?php echo htmlspecialchars($patient['nom_prenom']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="date_naissance" class="form-label">Date de naissance <span class="text-danger">*</span></label>
        <input type="date" class="form-control" name="date_naissance" id="date_naissance" value="<?php echo htmlspecialchars($patient['date_naissance']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="nationalite" class="form-label">Nationalité(s) <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="nationalite" id="nationalite" value="<?php echo htmlspecialchars($patient['nationalite']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="maladies" class="form-label">Maladie(s)</label>
        <textarea class="form-control" name="maladies" id="maladies" rows="3"><?php echo htmlspecialchars($patient['maladies']); ?></textarea>
    </div>
    <div class="mb-3">
        <label for="informations_supplementaires" class="form-label">Informations supplémentaires</label>
        <textarea class="form-control" name="informations_supplementaires" id="informations_supplementaires" rows="3"><?php echo htmlspecialchars($patient['informations_supplementaires']); ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    <a href="dossiers_medicaux.php" class="btn btn-secondary">Annuler</a>
</form>

<?php include 'templates/footer.php'; ?>