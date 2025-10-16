<?php
require 'config/database.php';
include 'templates/header.php';

// On récupère l'ID de la facture depuis l'URL (?id=...)
$id_facture = $_GET['id'];

// 1. On récupère les infos générales de la facture
$stmt = $pdo->prepare("
    SELECT factures.*, personnel.nom AS medecin_nom, personnel.prenom AS medecin_prenom
    FROM factures
    LEFT JOIN personnel ON factures.id_medecin = personnel.id
    WHERE factures.id = ?
");
$stmt->execute([$id_facture]);
$facture = $stmt->fetch(PDO::FETCH_ASSOC);

// 2. On récupère la liste des soins liés à cette facture
$stmt_details = $pdo->prepare("
    SELECT services.nom_service, services.prix
    FROM details_facture
    JOIN services ON details_facture.id_service = services.id
    WHERE details_facture.id_facture = ?
");
$stmt_details->execute([$id_facture]);
$details_soins = $stmt_details->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h2>Détail Facture #<?php echo str_pad($facture['id'], 4, '0', STR_PAD_LEFT); ?></h2>
        <a href="facturation.php" class="btn btn-secondary">Retour à la liste</a>
    </div>
    <div class="card-body">
        <p><strong>Patient :</strong> <?php echo htmlspecialchars($facture['nom_patient']); ?></p>
        <p><strong>Médecin :</strong> <?php echo htmlspecialchars($facture['medecin_prenom'] . ' ' . $facture['medecin_nom']); ?></p>
        <p><strong>Date :</strong> <?php echo date("d/m/Y", strtotime($facture['date_facturation'])); ?></p>
        <hr>
        <h4>Soins effectués :</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Soin</th>
                    <th class="text-end">Prix</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($details_soins as $soin): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($soin['nom_service']); ?></td>
                        <td class="text-end"><?php echo number_format($soin['prix'], 2, ',', ' ') . ' €'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-group-divider">
                    <td class="text-end"><strong>TOTAL</strong></td>
                    <td class="text-end"><strong><?php echo number_format($facture['prix_total'], 2, ',', ' ') . ' €'; ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php include 'templates/footer.php'; ?>