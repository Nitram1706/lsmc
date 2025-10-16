<?php
// On active l'affichage des erreurs pour ne plus avoir de page blanche
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'config/database.php';
include 'templates/header.php';

// On définit la liste des grades disponibles
$grades_disponibles = [
    '0 - Directeur',
    '1 - Directeur Adjoint',
    '2 - Chef de département',
    '3 - Médecin en chef',
    '4 - Médecin Spécialiste',
    '5 - Médecin généraliste',
    '6 - Médecin',
    '7 - Infirmier en chef',
    '8 - Infirmier',
    '9 - Assistant médical'
];

// Logique pour ajouter un nouvel agent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recruter'])) {
    $matricule = $_POST['matricule'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_recrutement = $_POST['date_recrutement'];
    $grade = $_POST['grade'];
    
    $stmt = $pdo->prepare("INSERT INTO personnel (matricule, nom, prenom, date_recrutement, grade) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$matricule, $nom, $prenom, $date_recrutement, $grade]);

    header("Location: personnel.php");
    exit();
}

// Récupérer la liste du personnel pour l'affichage
$personnel = $pdo->query("SELECT * FROM personnel ORDER BY nom, prenom")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Gestion du Personnel</h2>

<div class="card my-4">
    <div class="card-header">Recruter un agent</div>
    <div class="card-body">
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" name="prenom" id="prenom" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" name="nom" id="nom" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="matricule" class="form-label">Matricule</label>
                    <input type="text" name="matricule" id="matricule" class="form-control" required>
                </div>
                 <div class="col-md-3">
                    <label for="date_recrutement" class="form-label">Date de recrutement</label>
                    <input type="date" name="date_recrutement" id="date_recrutement" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="grade" class="form-label">Grade</label>
                    <select name="grade" id="grade" class="form-select" required>
                        <option value="">-- Choisir un grade --</option>
                        <?php foreach ($grades_disponibles as $grade_option): ?>
                            <option value="<?php echo htmlspecialchars($grade_option); ?>">
                                <?php echo htmlspecialchars($grade_option); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" name="recruter" class="btn btn-success mt-3">Recruter</button>
        </form>
    </div>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Nom et Prénom</th>
            <th>Grade</th>
            <th>Matricule</th>
            <th>Recruté le</th>
            <th>Aptitude</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($personnel as $agent): ?>
        <tr>
            <td><?php echo htmlspecialchars($agent['prenom'] . ' ' . $agent['nom']); ?></td>
            <td><?php echo htmlspecialchars($agent['grade']); ?></td>
            <td><?php echo htmlspecialchars($agent['matricule']); ?></td>
            <td><?php echo date("d/m/Y", strtotime($agent['date_recrutement'])); ?></td>
            <td>
                <span class="badge <?php echo $agent['aptitude'] == 'APTE' ? 'bg-success' : 'bg-danger'; ?>">
                    <?php echo $agent['aptitude']; ?>
                </span>
            </td>
            <td>
                <a href="editer_personnel.php?id=<?php echo $agent['id']; ?>" class="btn btn-sm btn-primary">
                    Modifier
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'templates/footer.php'; ?>