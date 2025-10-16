<?php
require 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_patient'])) {
    $id_patient_a_supprimer = $_POST['id_patient'];

    // ATTENTION : On doit d'abord supprimer les factures liées à ce patient pour éviter une erreur
    // car la base de données a une contrainte qui l'interdit.
    $stmt_factures = $pdo->prepare("DELETE FROM factures WHERE id_patient = ?");
    $stmt_factures->execute([$id_patient_a_supprimer]);

    // Ensuite, on peut supprimer le patient lui-même
    $stmt_patient = $pdo->prepare("DELETE FROM patients WHERE id = ?");
    $stmt_patient->execute([$id_patient_a_supprimer]);
}

header("Location: dossiers_medicaux.php");
exit();
?>