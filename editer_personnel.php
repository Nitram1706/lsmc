<?php
require 'config/database.php';
include 'templates/header.php';

// NOUVEAU : La liste des grades mise à jour selon ton image.
$grades_disponibles = [
    '0 - Directeur',
    '1 - Directeur Adjoint',
    '2 - Chef(fe) de Département',
    '3 - Médecin en Chef',
    '4 - Médecin Spécialiste',
    '5 - Médecin Généraliste',
    '6 - Médecin',
    '7 - Infirmier(e) en Chef',
    '8 - Infirmier(e)',
    '9 - Assistant Médical'
];

// 1. Récupérer l'agent à modifier depuis l'URL
if (!isset($_GET['id'])) {
    header("Location: personnel.php");
    exit();
}
$id_agent = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM personnel WHERE id = ?");
$stmt->execute([$id_agent]);
$agent = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$agent) {
    header("Location: personnel.php");
    exit();
}

// 2. Traiter le formulaire quand il est envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $matricule = $_POST['matricule'];
    $grade = $_POST['grade'];
    $aptitude = $_POST['aptitude'];
    $id_a_modifier = $_POST['id_agent'];

    $update_stmt = $pdo->prepare("
        UPDATE personnel 
        SET nom = ?, prenom = ?, matricule = ?, grade = ?, aptitude = ? 
        WHERE id = ?
    ");
    $update_stmt->execute([$nom, $prenom, $matricule, $grade, $aptitude, $id_a_modifier]);

    header("Location: personnel.php");
    exit();
}
?>

<h2>Modifier l'agent : <?php echo htmlspecialchars($agent['prenom'] . ' ' . $agent['nom']); ?></h2>

<form method="POST">
    <input type="hidden" name="id_agent" value="<?php echo $agent['id']; ?>">

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" name="prenom" id="prenom" value="<?php echo htmlspecialchars($agent['prenom']); ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" name="nom" id="nom" value="<?php echo htmlspecialchars($agent['nom']); ?>">
        </div>
    </div>
    <div class="mb-3">
        <label for="matricule" class="form-label">Matricule</label>
        <input type="text" class="form-control" name="matricule" id="matricule" value="<?php echo htmlspecialchars($agent['matricule']); ?>">
    </div>
    
    <div class="mb-3">
        <label for="grade" class="form-label">Grade</label>
        <select name="grade" class="form-select" id="grade">
            <option value="">-- Choisir un grade --</option>
            <?php foreach ($grades_disponibles as $grade_option): ?>
                <option value="<?php echo htmlspecialchars($grade_option); ?>" <?php if ($agent['grade'] == $grade_option) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($grade_option); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="aptitude" class="form-label">Aptitude</label>
        <select name="aptitude" class="form-select" id="aptitude">
            <option value="APTE" <?php if ($agent['aptitude'] == 'APTE') echo 'selected'; ?>>APTE</option>
            <option value="INAPTE" <?php if ($agent['aptitude'] == 'INAPTE') echo 'selected'; ?>>INAPTE</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    <a href="personnel.php" class="btn btn-secondary">Annuler</a>
</form>

<?php include 'templates/footer.php'; ?>