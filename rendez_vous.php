<?php
require 'config/database.php';
include 'templates/header.php';

// Récupérer les listes pour les menus déroulants
$patients = $pdo->query("SELECT id, nom_prenom FROM patients ORDER BY nom_prenom")->fetchAll(PDO::FETCH_ASSOC);
$medecins = $pdo->query("SELECT id, nom, prenom FROM personnel ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les rendez-vous à venir
$rendez_vous = $pdo->query("
    SELECT 
        rendez_vous.*,
        patients.nom_prenom AS patient_nom,
        personnel.nom AS medecin_nom,
        personnel.prenom AS medecin_prenom
    FROM rendez_vous
    JOIN patients ON rendez_vous.id_patient = patients.id
    JOIN personnel ON rendez_vous.id_medecin = personnel.id
    WHERE rendez_vous.date_heure >= NOW()
    ORDER BY rendez_vous.date_heure ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Gestion des Rendez-vous</h1>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                Prendre un rendez-vous
            </div>
            <div class="card-body">
                <form action="traitement_rdv.php" method="POST">
                    <div class="mb-3">
                        <label for="id_patient" class="form-label">Patient</label>
                        <select name="id_patient" id="id_patient" class="form-select" required>
                            <option value="">-- Choisir un patient --</option>
                            <?php foreach ($patients as $patient): ?>
                                <option value="<?php echo $patient['id']; ?>"><?php echo htmlspecialchars($patient['nom_prenom']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_medecin" class="form-label">Médecin</label>
                        <select name="id_medecin" id="id_medecin" class="form-select" required>
                            <option value="">-- Choisir un médecin --</option>
                            <?php foreach ($medecins as $medecin): ?>
                                <option value="<?php echo $medecin['id']; ?>"><?php echo htmlspecialchars($medecin['prenom'] . ' ' . $medecin['nom']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date_heure" class="form-label">Date et Heure</label>
                        <input type="datetime-local" name="date_heure" id="date_heure" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="motif" class="form-label">Motif</label>
                        <textarea name="motif" id="motif" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter le RDV</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <h2>Rendez-vous à venir</h2>
        <table class="table table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Date et Heure</th>
                    <th>Patient</th>
                    <th>Médecin</th>
                    <th>Motif</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rendez_vous)): ?>
                    <tr><td colspan="5" class="text-center">Aucun rendez-vous à venir.</td></tr>
                <?php endif; ?>
                <?php foreach ($rendez_vous as $rdv): ?>
                    <tr>
                        <td><?php echo date("d/m/Y à H:i", strtotime($rdv['date_heure'])); ?></td>
                        <td><?php echo htmlspecialchars($rdv['patient_nom']); ?></td>
                        <td><?php echo htmlspecialchars($rdv['medecin_prenom'] . ' ' . $rdv['medecin_nom']); ?></td>
                        <td><?php echo htmlspecialchars($rdv['motif']); ?></td>
                        <td class="text-end">
                            <a href="annuler_rdv.php?id=<?php echo $rdv['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?');">
                                Annuler
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'templates/footer.php'; ?>