<?php
require 'config/database.php';

// On vérifie qu'on a bien reçu un ID de rendez-vous dans l'URL
if (isset($_GET['id'])) {
    $id_rdv_a_supprimer = $_GET['id'];

    // On prépare et on exécute la commande de suppression
    $stmt = $pdo->prepare("DELETE FROM rendez_vous WHERE id = ?");
    $stmt->execute([$id_rdv_a_supprimer]);
}

// On redirige l'utilisateur vers la page des rendez-vous
header("Location: rendez_vous.php");
exit();
?>