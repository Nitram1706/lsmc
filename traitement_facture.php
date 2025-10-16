<?php
require 'config/database.php';

// On vérifie qu'on a bien reçu des données du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // On récupère les informations
    $id_patient = $_POST['id_patient'];
    $id_medecin = $_POST['id_medecin'];
    $date_facturation = $_POST['date_facturation'];
    $delai_maximum = $_POST['delai_maximum'];
    
    // On initialise le prix total à 0
    $prix_total = 0;
    
    // On vérifie qu'au moins un service a été sélectionné
    if (!empty($_POST['services'])) {
        // Pour chaque service, on va chercher son prix et on l'ajoute au total
        foreach ($_POST['services'] as $service_id) {
            // On s'assure de ne pas traiter les lignes vides
            if (!empty($service_id)) {
                $stmt = $pdo->prepare("SELECT prix FROM services WHERE id = ?");
                $stmt->execute([$service_id]);
                $prix_service = $stmt->fetchColumn();
                $prix_total += $prix_service;
            }
        }
    }

    // On enregistre la facture principale avec le PRIX TOTAL bien calculé
    $stmt = $pdo->prepare("INSERT INTO factures (id_patient, id_medecin, date_facturation, delai_maximum, prix_total, statut_paiement) VALUES (?, ?, ?, ?, ?, 'Non payé')");
    $stmt->execute([$id_patient, $id_medecin, $date_facturation, $delai_maximum, $prix_total]);
    
    // On récupère l'ID de la facture qu'on vient de créer
    $id_nouvelle_facture = $pdo->lastInsertId();

    // On enregistre les détails des soins
    if (!empty($_POST['services'])) {
        foreach ($_POST['services'] as $service_id) {
            if (!empty($service_id)) {
                $stmt = $pdo->prepare("INSERT INTO details_facture (id_facture, id_service) VALUES (?, ?)");
                $stmt->execute([$id_nouvelle_facture, $service_id]);
            }
        }
    }
    
    // Une fois que tout est bien enregistré, on redirige vers la liste
    header("Location: facturation.php");
    exit();
}
?>