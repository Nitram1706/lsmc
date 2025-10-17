<?php
require 'config/database.php';
include 'templates/header.php';

// Requête pour obtenir le total des secondes par utilisateur
$service_hours = $pdo->query("
    SELECT 
        user_discord_name, 
        SUM(duration_seconds) as total_seconds
    FROM service_logs
    GROUP BY user_discord_name
    ORDER BY total_seconds DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour convertir les secondes en format H/M/S
function format_seconds($seconds) {
    $h = floor($seconds / 3600);
    $m = floor(($seconds % 3600) / 60);
    return sprintf('%dh %02dm', $h, $m);
}
?>

<h1>Total des Heures de Service (Dispatch)</h1>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead class="table-light">
                <tr>
                    <th>Employé (Discord)</th>
                    <th class="text-end">Temps total en service</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($service_hours)): ?>
                    <tr><td colspan="2" class="text-center">Aucune heure de service enregistrée.</td></tr>
                <?php endif; ?>

                <?php foreach ($service_hours as $log): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($log['user_discord_name']); ?></td>
                        <td class="text-end"><strong><?php echo format_seconds($log['total_seconds']); ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'templates/footer.php'; ?>