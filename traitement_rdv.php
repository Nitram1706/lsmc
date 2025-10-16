<?php
require 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $id_patient = $_POST['id_patient'];
    $id_medecin = $_POST['id_medecin'];
    $date_heure = $_POST['date_heure'];
    $motif = $_POST['motif'];

    // Préparer et exécuter la requête d'insertion
    $stmt = $pdo->prepare(
        "INSERT INTO rendez_vous (id_patient, id_medecin, date_heure, motif) VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$id_patient, $id_medecin, $date_heure, $motif]);

    // Rediriger vers la page des rendez-vous
    header("Location: rendez_vous.php");
    exit();
}
?>