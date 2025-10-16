<?php
require 'config/database.php';
include 'templates/header.php';
require 'auth_guard.php';
// --- LOGIQUE DE RECHERCHE ---
$sql = "SELECT * FROM patients";
$params = [];

$search_term = '';
if (!empty($_GET['search'])) {
    $search_term = $_GET['search'];
    $sql .= " WHERE nom_prenom LIKE ?";
    $params[] = "%$search_term%";
}

$sql .= " ORDER BY nom_prenom";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Dossiers Médicaux</h1>
    <a href="nouveau_dossier.php" class="btn btn-success btn-lg">
        + Créer un nouveau dossier
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="dossiers_medicaux.php" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Rechercher par nom et prénom..." value="<?php echo htmlspecialchars($search_term); ?>">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </form>
    </div>
</div>

<table class="table table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th>Nom et Prénom</th>
            <th>Date de naissance</th>
            <th>Nationalité</th>
            <th class="text-end">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($patients)): ?>
            <tr>
                <td colspan="4" class="text-center">Aucun dossier trouvé.</td>
            </tr>
        <?php endif; ?>

        <?php foreach ($patients as $patient): ?>
        <tr>
            <td><?php echo htmlspecialchars($patient['nom_prenom']); ?></td>
            <td><?php echo date("d/m/Y", strtotime($patient['date_naissance'])); ?></td>
            <td><?php echo htmlspecialchars($patient['nationalite']); ?></td>
            <td class="text-end d-flex gap-2 justify-content-end">
                <a href="details_dossier.php?id=<?php echo $patient['id']; ?>" class="btn btn-sm btn-info">Voir</a>
                <a href="editer_dossier.php?id=<?php echo $patient['id']; ?>" class="btn btn-sm btn-primary">Modifier</a>
                <form method="POST" action="supprimer_dossier.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce dossier ? Cette action est irréversible.');">
                    <input type="hidden" name="id_patient" value="<?php echo $patient['id']; ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'templates/footer.php'; ?>