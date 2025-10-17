<?php
require 'config/database.php';
include 'templates/header.php';
?>

<h2>Créer une nouvelle fiche patient</h2>

<form method="POST" action="traitement_dossier.php">
    <div class="mb-3">
        <label for="nom_prenom" class="form-label">Nom et Prénom du patient <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="nom_prenom" id="nom_prenom" required>
    </div>
    <div class="mb-3">
        <label for="date_naissance" class="form-label">Date de naissance <span class="text-danger">*</span></label>
        <input type="date" class="form-control" name="date_naissance" id="date_naissance" required>
    </div>
    <div class="mb-3">
        <label for="nationalite" class="form-label">Nationalité(s) <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="nationalite" id="nationalite" required>
    </div>
    <div class="mb-3">
        <label for="maladies" class="form-label">Maladie(s)</label>
        <textarea class="form-control" name="maladies" id="maladies" rows="3"></textarea>
    </div>
    <div class="mb-3">
        <label for="informations_supplementaires" class="form-label">Informations supplémentaires</label>
        <textarea class="form-control" name="informations_supplementaires" id="informations_supplementaires" rows="3" placeholder="Groupe sanguin, Numéro de Téléphone, Poids(KG), Taille(CM), Sexe..."></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Enregistrer le dossier</button>
    <a href="dossiers_medicaux.php" class="btn btn-secondary">Annuler</a>
</form>

<?php include 'templates/footer.php'; ?>