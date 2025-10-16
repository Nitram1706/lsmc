<?php
$required_role = 'admin';
require 'auth_guard.php';
require 'config/database.php';

// On vérifie qu'on a bien reçu un ID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_agent'])) {
    $id_agent_a_supprimer = $_POST['id_agent'];

    // ATTENTION : Avant de supprimer un agent, il faudrait idéalement gérer ses factures
    // (les réassigner, les anonymiser...). Pour l'instant, on supprime simplement.

    $stmt = $pdo->prepare("DELETE FROM personnel WHERE id = ?");
    $stmt->execute([$id_agent_a_supprimer]);
}

// On redirige vers la liste du personnel
header("Location: personnel.php");
exit();
?>