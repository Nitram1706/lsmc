<?php
session_start();

// Si l'utilisateur n'est pas connecté, on le renvoie à la page de connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Si une permission spécifique est requise (ex: 'admin') et que l'utilisateur ne l'a pas
if (isset($required_role) && $_SESSION['role'] !== $required_role) {
    // On pourrait le rediriger vers une page "accès refusé", mais pour l'instant on le renvoie au tableau de bord.
    header('Location: index.php');
    exit();
}
?>