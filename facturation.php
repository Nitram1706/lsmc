<?php
require 'config/database.php';
include 'templates/header.php';

// --- NOUVELLE LOGIQUE DE VÉRIFICATION ---
// 1. On récupère toutes les factures qui sont "Non payé"
$factures_a_verifier = $pdo->query("SELECT * FROM factures WHERE statut_paiement = 'Non payé'")->fetchAll(PDO::FETCH_ASSOC);

$date_actuelle = time(); // On récupère la date d'aujourd'hui (en secondes)

// 2. Pour chaque facture, on vérifie si le délai est dépassé
foreach ($factures_a_verifier as $facture) {
    $date_limite = strtotime($facture['delai_maximum']); // On convertit la date limite en secondes

    if ($date_limite < $date_actuelle) {
        // Le délai est dépassé !
        $nouveau_total = $facture['prix_total'] * 1.15; // On applique 15% de pénalité

        // On met à jour la facture dans la base de données
        $stmt = $pdo->prepare("UPDATE factures SET statut_paiement = 'Impayé', prix_total = ? WHERE id = ?");
        $stmt->execute([$nouveau_total, $facture['id']]);
    }
}
// --- FIN DE LA NOUVELLE LOGIQUE ---


// On récupère la liste À JOUR de toutes les factures pour l'affichage
$requete = $pdo->query("
    SELECT factures.*, personnel.nom AS medecin_nom, personnel.prenom AS medecin_prenom
    FROM factures
    LEFT JOIN personnel ON factures.id_medecin = personnel.id
    ORDER BY factures.id DESC
");
$factures = $requete->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Suivi de Facturation</h1>
    <a href="nouvelle_facture.php" class="btn btn-success btn-lg">
        + Créer une nouvelle facture
    </a>
</div>

<table class="table table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th># Facture</th>
            <th>Patient</th>
            <th>Médecin</th>
            <th>Date / Délai</th> <th>Prix Total</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($factures as $facture): ?>
        <tr>
            <td><a href="details_facture.php?id=<?php echo $facture['id']; ?>"><strong><?php echo 'FACT-' . str_pad($facture['id'], 4, '0', STR_PAD_LEFT); ?></strong></a></td>
            <td><?php echo htmlspecialchars($facture['nom_patient']); ?></td>
            <td><?php echo htmlspecialchars($facture['medecin_prenom'] . ' ' . $facture['medecin_nom']); ?></td>

            <td>
                <?php echo date("d/m/Y", strtotime($facture['date_facturation'])); ?>
                <br>
                <small class="text-muted">Limite: <?php echo date("d/m/Y", strtotime($facture['delai_maximum'])); ?></small>
            </td>

            <td><?php echo number_format($facture['prix_total'], 2, ',', ' ') . ' €'; ?></td>

            <td>
                <?php
                // On adapte la couleur de la pastille au nouveau statut
                $statut = $facture['statut_paiement'];
                $couleur = 'bg-danger'; // Non payé par défaut
                if ($statut == 'Payé') $couleur = 'bg-success';
                if ($statut == 'Impayé') $couleur = 'bg-dark'; // Impayé en noir
                ?>
                <span class="badge fs-6 <?php echo $couleur; ?>"><?php echo $statut; ?></span>
            </td>

            <td>
                <?php if ($facture['statut_paiement'] != 'Payé'): ?>
                    <form action="update_statut.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id_facture" value="<?php echo $facture['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-success">Marquer payé</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'templates/footer.php'; ?>