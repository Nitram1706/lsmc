<?php
require 'config/database.php';
include 'templates/header.php';

// Vérifie si un ID est passé dans l'URL, sinon redirige
if (!isset($_GET['id'])) {
    header("Location: dossiers_medicaux.php");
    exit();
}
$id_patient = $_GET['id'];

// Récupère toutes les informations du patient correspondant à l'ID
$stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
$stmt->execute([$id_patient]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

// Si aucun patient n'est trouvé, redirige
if (!$patient) {
    header("Location: dossiers_medicaux.php");
    exit();
}
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>Dossier de : <?php echo htmlspecialchars($patient['nom_prenom']); ?></h2>
        <a href="dossiers_medicaux.php" class="btn btn-secondary">Retour à la liste</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Date de naissance :</strong> <?php echo date("d/m/Y", strtotime($patient['date_naissance'])); ?></p>
                <p><strong>Nationalité(s) :</strong> <?php echo htmlspecialchars($patient['nationalite']); ?></p>
            </div>
        </div>
        <hr>
        <h4>Maladie(s) connue(s)</h4>
        <p><?php echo nl2br(htmlspecialchars($patient['maladies'])); ?></p>

        <hr>

        <h4>Informations supplémentaires</h4>
        <p><?php echo nl2br(htmlspecialchars($patient['informations_supplementaires'])); ?></p>
    </div>
</div>

<?php include 'templates/footer.php'; ?>