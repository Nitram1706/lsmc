<?php
require 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom_prenom = $_POST['nom_prenom'];
    $date_naissance = $_POST['date_naissance'];
    $nationalite = $_POST['nationalite'];
    $maladies = $_POST['maladies'];
    $informations_supplementaires = $_POST['informations_supplementaires'];

    // Préparer la requête d'insertion
    $stmt = $pdo->prepare("
        INSERT INTO patients (nom_prenom, date_naissance, nationalite, maladies, informations_supplementaires) 
        VALUES (?, ?, ?, ?, ?)
    ");

    // Exécuter la requête
    $stmt->execute([$nom_prenom, $date_naissance, $nationalite, $maladies, $informations_supplementaires]);

    // Rediriger vers la liste des dossiers
    header("Location: dossiers_medicaux.php");
    exit();
}
?>