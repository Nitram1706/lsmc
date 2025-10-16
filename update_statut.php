<?php
require 'config/database.php';

// On vérifie qu'on a bien reçu un ID de facture
if (isset($_POST['id_facture'])) {
    $id_facture = $_POST['id_facture'];

    // On prépare la commande pour mettre à jour le statut
    $stmt = $pdo->prepare("UPDATE factures SET statut_paiement = 'Payé' WHERE id = ?");

    // On exécute la commande
    $stmt->execute([$id_facture]);
}

// Dans tous les cas, on redirige vers la liste des factures
header("Location: facturation.php");
exit();
?>