<?php
require 'config/database.php';
include 'templates/header.php';

$medecins = $pdo->query("SELECT id, nom, prenom FROM personnel ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
$services = $pdo->query("SELECT id, nom_service, prix FROM services ORDER BY nom_service")->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Créer une nouvelle facture</h1>

<form method="POST" action="traitement_facture.php">
    <div class="card">
        <div class="card-header">Informations générales</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nom_patient" class="form-label">Nom du patient</label>
                    <input type="text" class="form-control" name="nom_patient" id="nom_patient" required>
                </div>
                <div class="col-md-6">
                    <label for="id_medecin" class="form-label">Médecin en charge</label>
                    <select class="form-select" name="id_medecin" id="id_medecin" required>
                        <option value="">-- Choisir un médecin --</option>
                        <?php foreach ($medecins as $medecin): ?>
                            <option value="<?php echo $medecin['id']; ?>">
                                <?php echo htmlspecialchars($medecin['prenom'] . ' ' . $medecin['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="date_facturation" class="form-label">Date de facturation</label>
                    <input type="date" class="form-control" name="date_facturation" id="date_facturation" value="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="delai_maximum" class="form-label">Date limite de paiement</label>
                    <input type="date" class="form-control" name="delai_maximum" id="delai_maximum" value="<?php echo date('Y-m-d', strtotime('+1 week')); ?>" required>
                </div>

            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Détail des soins</div>
        <div class="card-body">
            <div id="lignes_services">
                <div class="row g-3 mb-2 align-items-center">
                    <div class="col-md-10">
                        <select name="services[]" class="form-select">
                            <option value="">-- Choisir un soin --</option>
                            <?php foreach ($services as $service): ?>
                                <option value="<?php echo $service['id']; ?>">
                                    <?php echo htmlspecialchars($service['nom_service']) . ' (' . number_format($service['prix'], 2, ',', ' ') . ' €)'; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <button type="button" id="ajouter_service" class="btn btn-secondary mt-2">+ Ajouter un soin</button>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary btn-lg">Enregistrer la facture</button>
        <a href="facturation.php" class="btn btn-light btn-lg">Annuler</a>
    </div>
</form>

<script>
document.getElementById('ajouter_service').addEventListener('click', function() {
    var container = document.getElementById('lignes_services');
    var premiereLigne = container.querySelector('.row');
    var nouvelleLigne = premiereLigne.cloneNode(true);
    container.appendChild(nouvelleLigne);
});
</script>

<?php include 'templates/footer.php'; ?>