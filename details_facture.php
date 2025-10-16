<?php
require 'config/database.php';
include 'templates/header.php';

$id_facture = $_GET['id'];

// On ajoute la jointure avec la table PATIENTS
$stmt = $pdo->prepare("
    SELECT 
        factures.*, 
        personnel.nom AS medecin_nom, 
        personnel.prenom AS medecin_prenom,
        patients.nom_prenom AS patient_nom_prenom
    FROM factures
    LEFT JOIN personnel ON factures.id_medecin = personnel.id
    LEFT JOIN patients ON factures.id_patient = patients.id
    WHERE factures.id = ?
");
$stmt->execute([$id_facture]);
$facture = $stmt->fetch(PDO::FETCH_ASSOC);

// ... (La récupération des détails des soins ne change pas) ...
?>

<p><strong>Patient :</strong> <?php echo htmlspecialchars($facture['patient_nom_prenom']); ?></p>

<?php include 'templates/footer.php'; ?>