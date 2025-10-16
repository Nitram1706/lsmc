<?php
// On prend la clé pour le classeur et le menu
require 'config/database.php';
include 'templates/header.php';

// -- LA LOGIQUE DU TABLEAU DE BORD --

// 1. On compte le nombre total d'agents
$requete_agents = $pdo->query("SELECT COUNT(*) FROM personnel");
$nombre_agents = $requete_agents->fetchColumn();

// 2. NOUVEAU : On compte le nombre de factures dont le statut est "Non payé"
$requete_factures = $pdo->query("SELECT COUNT(*) FROM factures WHERE statut_paiement IN ('Non payé', 'Impayé')");
$nombre_factures_en_attente = $requete_factures->fetchColumn();

?>

<h1 class="mb-4">Tableau de Bord LSMC</h1>

<div class="row">

    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Effectif Total</div>
            <div class="card-body">
                <h5 class="card-title" style="font-size: 2.5rem;">
                    <?php echo $nombre_agents; ?>
                </h5>
                <p class="card-text">Agents enregistrés</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Agents en service</div>
            <div class="card-body">
                <h5 class="card-title" style="font-size: 2.5rem;">À venir</h5>
                <p class="card-text">Connectés actuellement</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <a href="facturation.php" style="text-decoration: none;">
            <div class="card text-dark bg-warning mb-3">
                <div class="card-header">Factures en attente</div>
                <div class="card-body">
                    <h5 class="card-title" style="font-size: 2.5rem;">
                        <?php echo $nombre_factures_en_attente; ?>
                    </h5>
                    <p class="card-text">Non payées</p>
                </div>
            </div>
        </a>
    </div>

</div>

<?php
// On met le pain du bas
include 'templates/footer.php';
?>