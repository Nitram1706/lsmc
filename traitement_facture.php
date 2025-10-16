<?php
require 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_patient = $_POST['nom_patient'];
    $id_medecin = $_POST['id_medecin'];
    $date_facturation = $_POST['date_facturation'];

    // MODIFIÉ : On ne calcule plus, on récupère la date choisie par l'utilisateur
    $delai_maximum = $_POST['delai_maximum'];

    $prix_total = 0;
    if (!empty($_POST['services'])) {
        foreach ($_POST['services'] as $service_id) {
            if (!empty($service_id)) {
                $stmt = $pdo->prepare("SELECT prix FROM services WHERE id = ?");
                $stmt->execute([$service_id]);
                $prix_service = $stmt->fetchColumn();
                $prix_total += $prix_service;
            }
        }
    }

    $stmt = $pdo->prepare("INSERT INTO factures (nom_patient, id_medecin, date_facturation, delai_maximum, prix_total, statut_paiement) VALUES (?, ?, ?, ?, ?, 'Non payé')");
    $stmt->execute([$nom_patient, $id_medecin, $date_facturation, $delai_maximum, $prix_total]);

    $id_nouvelle_facture = $pdo->lastInsertId();

    if (!empty($_POST['services'])) {
        foreach ($_POST['services'] as $service_id) {
            if (!empty($service_id)) {
                $stmt = $pdo->prepare("INSERT INTO details_facture (id_facture, id_service) VALUES (?, ?)");
                $stmt->execute([$id_nouvelle_facture, $service_id]);
            }
        }
    }

    header("Location: facturation.php");
    exit();
}
?>