<?php
// config/database.php

// -- LES IDENTIFIANTS DE TON CLASSEUR --

// L'adresse du classeur (c'est toujours 'localhost' sur ton ordi)
$host = 'localhost';

// Le nom de notre classeur
$dbname = 'lsmc_db';

// Le nom d'utilisateur pour accéder au classeur
$user = 'root';

// Le mot de passe pour accéder au classeur
$pass = 'root'; // Pour MAMP, c'est 'root'. Si tu avais utilisé XAMPP, ce serait vide ('').


// -- LA MÉCANIQUE DE CONNEXION (ne touche pas à ça) --
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Oups ! Impossible d'ouvrir le classeur : " . $e->getMessage());
}
?>