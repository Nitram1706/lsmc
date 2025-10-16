<?php
// Mot de passe que nous voulons utiliser
$password = 'password';

// On demande à PHP de créer l'empreinte digitale sécurisée
$hash = password_hash($password, PASSWORD_DEFAULT);

// On affiche le résultat
echo "Voici la bonne empreinte digitale pour votre MAMP :<br><br>";
echo "<strong>" . $hash . "</strong>";
?>