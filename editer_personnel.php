<?php
require 'config/database.php';
include 'templates/header.php';

// On définit les listes pour les menus déroulants
$grades_disponibles = [ '0 - Directeur', '1 - Directeur Adjoint', '2 - Chef de département', '3 - Médecin en chef', '4 - Médecin Spécialiste', '5 - Médecin généraliste', '6 - Médecin', '7 - Infirmier en chef', '8 - Infirmier', '9 - Assistant médical'];
$specialisations_disponibles = ['Psychiatrie', 'Chirurgie', 'Médecine Légale', 'Gynécologie'];

// On traite le formulaire quand il est envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $matricule = $_POST['matricule'];
    $grade = $_POST['grade'];
    $aptitude = $_POST['aptitude'];
    $id_a_modifier = $_POST['id_agent'];

    // On récupère les spécialisations (qui sont un tableau)
    $specialisations = isset($_POST['specialisations']) ? $_POST['specialisations'] : [];
    // On les transforme en une chaîne de caractères séparée par des virgules
    $specialisations_string = implode(', ', $specialisations);

    // On prépare la commande SQL complète pour tout mettre à jour
    $update_stmt = $pdo->prepare("
        UPDATE personnel 
        SET nom = ?, prenom = ?, matricule = ?, grade = ?, specialisations = ?, aptitude = ? 
        WHERE id = ?
    ");
    // On exécute la commande avec toutes les variables
    $update_stmt->execute([$nom, $prenom, $matricule, $grade, $specialisations_string, $aptitude, $id_a_modifier]);

    // On redirige vers la liste du personnel
    header("Location: personnel.php");
    exit();
}

// On récupère les informations de l'agent à modifier depuis l'URL
$id_agent = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM personnel WHERE id = ?");
$stmt->execute([$id_agent]);
$agent = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$agent) {
    header("Location: personnel.php");
    exit();
}

// On transforme la chaîne de spécialisations de la BDD en un tableau pour pouvoir pré-cocher les cases
$agent_specialisations = explode(', ', $agent['specialisations']);
?>

<h2>Modifier l'agent : <?php echo htmlspecialchars($agent['prenom'] . ' ' . $agent['nom']); ?></h2>

<form method="POST">
    <input type="hidden" name="id_agent" value="<?php echo $agent['id']; ?>">
    
    <div class="row">
        <div class="col-md-6 mb-3"><label for="prenom" class="form-label">Prénom</label><input type="text" class="form-control" name="prenom" id="prenom" value="<?php echo htmlspecialchars($agent['prenom']); ?>"></div>
        <div class="col-md-6 mb-3"><label for="nom" class="form-label">Nom</label><input type="text" class="form-control" name="nom" id="nom" value="<?php echo htmlspecialchars($agent['nom']); ?>"></div>
    </div>
    <div class="mb-3"><label for="matricule" class="form-label">Matricule</label><input type="text" class="form-control" name="matricule" id="matricule" value="<?php echo htmlspecialchars($agent['matricule']); ?>"></div>
    <div class="mb-3"><label for="grade" class="form-label">Grade</label><select name="grade" class="form-select" id="grade"><?php foreach ($grades_disponibles as $grade_option): ?><option value="<?php echo htmlspecialchars($grade_option); ?>" <?php if ($agent['grade'] == $grade_option) echo 'selected'; ?>><?php echo htmlspecialchars($grade_option); ?></option><?php endforeach; ?></select></div>
    
    <div class="mb-3">
        <label class="form-label">Spécialisation(s)</label>
        <div>
            <?php foreach ($specialisations_disponibles as $spe): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="specialisations[]" value="<?php echo $spe; ?>" id="spe_<?php echo str_replace(' ', '_', $spe); ?>" <?php if (in_array($spe, $agent_specialisations)) echo 'checked'; ?>>
                    <label class="form-check-label" for="spe_<?php echo str_replace(' ', '_', $spe); ?>"><?php echo $spe; ?></label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="mb-3"><label for="aptitude" class="form-label">Aptitude</label><select name="aptitude" class="form-select" id="aptitude"><option value="APTE" <?php if ($agent['aptitude'] == 'APTE') echo 'selected'; ?>>APTE</option><option value="INAPTE" <?php if ($agent['aptitude'] == 'INAPTE') echo 'selected'; ?>>INAPTE</option></select></div>

    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    <a href="personnel.php" class="btn btn-secondary">Annuler</a>
</form>

<?php include 'templates/footer.php'; ?>