<?php
$required_role = 'admin';
require 'auth_guard.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'config/database.php';
include 'templates/header.php';

// On définit les listes ici pour les réutiliser
$grades_disponibles = ['0 - Directeur', '1 - Directeur Adjoint', '2 - Chef de département', '3 - Médecin en chef', '4 - Médecin Spécialiste', '5 - Médecin généraliste', '6 - Médecin', '7 - Infirmier en chef', '8 - Infirmier', '9 - Assistant médical'];
$specialisations_disponibles = ['Psychiatrie', 'Chirurgie', 'Médecine Légale', 'Gynécologie'];

// Logique pour ajouter un nouvel agent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recruter'])) {
    $matricule = $_POST['matricule'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_recrutement = $_POST['date_recrutement'];
    $grade = $_POST['grade'];

    // On récupère les spécialisations (qui sont un tableau)
    $specialisations = isset($_POST['specialisations']) ? $_POST['specialisations'] : [];
    // On les transforme en une chaîne de caractères séparée par des virgules pour la BDD
    $specialisations_string = implode(', ', $specialisations);
    
    $stmt = $pdo->prepare("INSERT INTO personnel (matricule, nom, prenom, date_recrutement, grade, specialisations) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$matricule, $nom, $prenom, $date_recrutement, $grade, $specialisations_string]);

    header("Location: personnel.php");
    exit();
}

// On récupère la liste du personnel pour l'affichage (avec la recherche)
$sql = "SELECT * FROM personnel";
$params = [];
$search_term = '';
if (!empty($_GET['search'])) {
    $search_term = $_GET['search'];
    $sql .= " WHERE nom LIKE ? OR prenom LIKE ?";
    $params[] = "%$search_term%";
    $params[] = "%$search_term%";
}
$sql .= " ORDER BY nom, prenom";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$personnel = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Gestion du Personnel</h2>

<div class="card my-4">
    <div class="card-header">Recruter un agent</div>
    <div class="card-body">
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-3"><label for="prenom" class="form-label">Prénom</label><input type="text" name="prenom" id="prenom" class="form-control" required></div>
                <div class="col-md-3"><label for="nom" class="form-label">Nom</label><input type="text" name="nom" id="nom" class="form-control" required></div>
                <div class="col-md-3"><label for="matricule" class="form-label">Matricule</label><input type="text" name="matricule" id="matricule" class="form-control" required></div>
                <div class="col-md-3"><label for="date_recrutement" class="form-label">Date de recrutement</label><input type="date" name="date_recrutement" id="date_recrutement" class="form-control" value="<?php echo date('Y-m-d'); ?>" required></div>
                <div class="col-md-6"><label for="grade" class="form-label">Grade</label><select name="grade" id="grade" class="form-select" required><option value="">-- Choisir un grade --</option><?php foreach ($grades_disponibles as $grade_option): ?><option value="<?php echo htmlspecialchars($grade_option); ?>"><?php echo htmlspecialchars($grade_option); ?></option><?php endforeach; ?></select></div>
                <div class="col-12 mt-3">
                    <label class="form-label">Spécialisation(s)</label>
                    <div>
                        <?php foreach ($specialisations_disponibles as $spe): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="specialisations[]" value="<?php echo $spe; ?>" id="spe_<?php echo str_replace(' ', '_', $spe); ?>">
                                <label class="form-check-label" for="spe_<?php echo str_replace(' ', '_', $spe); ?>"><?php echo $spe; ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <button type="submit" name="recruter" class="btn btn-success mt-3">Recruter</button>
        </form>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="personnel.php" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Rechercher par nom ou prénom..." value="<?php echo htmlspecialchars($search_term); ?>">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </form>
    </div>
</div>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>Nom et Prénom</th>
            <th>Grade / Spécialisation(s)</th>
            <th>Matricule</th>
            <th>Aptitude</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($personnel as $agent): ?>
        <tr>
            <td><?php echo htmlspecialchars($agent['prenom'] . ' ' . $agent['nom']); ?></td>
            <td>
                <strong><?php echo htmlspecialchars($agent['grade']); ?></strong><br>
                <small class="text-muted"><?php echo htmlspecialchars($agent['specialisations']); ?></small>
            </td>
            <td><?php echo htmlspecialchars($agent['matricule']); ?></td>
            <td>
                <span class="badge <?php echo $agent['aptitude'] == 'APTE' ? 'bg-success' : 'bg-danger'; ?>">
                    <?php echo $agent['aptitude']; ?>
                </span>
            </td>
            <td class="d-flex gap-2">
                <a href="editer_personnel.php?id=<?php echo $agent['id']; ?>" class="btn btn-sm btn-primary">Modifier</a>
                <form method="POST" action="supprimer_personnel.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet agent ?');">
                    <input type="hidden" name="id_agent" value="<?php echo $agent['id']; ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'templates/footer.php'; ?>