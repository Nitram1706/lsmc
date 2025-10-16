<?php
// On active l'affichage des erreurs pour ne plus avoir de page blanche
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'config/database.php';
include 'templates/header.php';

// --- LOGIQUE DU TABLEAU DE BORD ---

// Statistiques existantes
$nombre_agents = $pdo->query("SELECT COUNT(*) FROM personnel")->fetchColumn();
$nombre_factures_en_attente = $pdo->query("SELECT COUNT(*) FROM factures WHERE statut_paiement IN ('Non payé', 'Impayé')")->fetchColumn();
$nombre_patients = $pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn();
$total_revenus = $pdo->query("SELECT SUM(prix_total) FROM factures WHERE statut_paiement = 'Payé'")->fetchColumn() ?? 0;

// Données pour le graphique
// LA CORRECTION EST DANS LA LIGNE CI-DESSOUS : PDO::FETCH_ASSOC au lieu de PDO.FETCH_ASSOC
$revenus_par_employe = $pdo->query("
    SELECT 
        p.prenom,
        p.nom,
        SUM(f.prix_total) as total_genere
    FROM factures f
    JOIN personnel p ON f.id_medecin = p.id
    WHERE f.statut_paiement = 'Payé'
    GROUP BY f.id_medecin
    ORDER BY total_genere DESC
")->fetchAll(PDO::FETCH_ASSOC);

$labels_graphique = [];
$data_graphique = [];
foreach ($revenus_par_employe as $employe) {
    $labels_graphique[] = $employe['prenom'] . ' ' . $employe['nom'];
    $data_graphique[] = $employe['total_genere'];
}
?>

<h1 class="mb-4">Tableau de Bord ResQ</h1>

<div class="row">
    <div class="col-md-3">
        <a href="personnel.php" class="text-decoration-none">
            <div class="card text-white bg-primary mb-3"><div class="card-header">Effectif Total</div><div class="card-body"><h5 class="card-title display-4"><?php echo $nombre_agents; ?></h5><p class="card-text">Agents enregistrés</p></div></div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="dossiers_medicaux.php" class="text-decoration-none">
            <div class="card text-white bg-info mb-3"><div class="card-header">Total des Patients</div><div class="card-body"><h5 class="card-title display-4"><?php echo $nombre_patients; ?></h5><p class="card-text">Dossiers médicaux</p></div></div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="facturation.php" class="text-decoration-none">
            <div class="card text-dark bg-warning mb-3"><div class="card-header">Factures en attente</div><div class="card-body"><h5 class="card-title display-4"><?php echo $nombre_factures_en_attente; ?></h5><p class="card-text">Non payées ou impayées</p></div></div>
        </a>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3"><div class="card-header">Revenus Totaux</div><div class="card-body"><h5 class="card-title display-4"><?php echo number_format($total_revenus, 2, ',', ' '); ?> €</h5><p class="card-text">Total encaissé</p></div></div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Répartition des revenus par employé</div>
            <div class="card-body">
                <canvas id="graphiqueRevenus"></canvas>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graphiqueRevenus');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($labels_graphique); ?>,
            datasets: [{
                label: 'Revenus générés',
                data: <?php echo json_encode($data_graphique); ?>,
                backgroundColor: ['rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)'],
                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Revenus par employé sur les factures payées' }
            }
        }
    });
</script>