<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResQ</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><path fill='%230d6efd' d='M224 480c10.5 0 20.3-4.1 27.6-11.4l65-65c6.2-6.2 9.4-14.4 9.4-22.6s-3.1-16.4-9.4-22.6l-20.4-20.4c-3.1-3.1-7.3-4.7-11.4-4.7s-8.3 1.6-11.4 4.7l-4.5 4.5L160 320l-36.2 36.2c-6.2 6.2-6.2 16.4 0 22.6l20.4 20.4c6.2 6.2 14.4 9.4 22.6 9.4H224zm-48-80l64-64 4.5-4.5c3.1-3.1 4.7-7.3 4.7-11.4s-1.6-8.3-4.7-11.4l-20.4-20.4c-6.2-6.2-16.4-6.2-22.6 0L160 320l-36.2-36.2c-6.2-6.2-16.4-6.2-22.6 0l-20.4 20.4c-6.2 6.2-6.2 16.4 0 22.6l65 65c7.3 7.3 17.1 11.4 27.6 11.4s20.3-4.1 27.6-11.4zM496 240c0-8.8-7.2-16-16-16H368c-8.8 0-16 7.2-16 16v96c0 8.8 7.2 16 16 16h112c8.8 0 16-7.2 16-16v-96zM288 128c0-8.8 7.2-16 16-16h112c8.8 0 16 7.2 16 16v96c0 8.8-7.2 16-16 16H304c-8.8 0-16-7.2-16-16v-96z'/></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .navbar-brand i { margin-right: 8px; }
        .nav-link.active { font-weight: bold; color: #0d6efd !important; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm py-3 mb-4">
        <div class="container-fluid align-items-center">
            <a class="navbar-brand" href="index.php"><i class="fa-solid fa-heart-pulse"></i> ResQ</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link <?php if ($currentPage == 'rendez_vous.php') echo 'active'; ?>" href="rendez_vous.php">Rendez-vous</a>
    </li>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <li class="nav-item"><a class="nav-link <?php if ($currentPage == 'index.php') echo 'active'; ?>" href="index.php">Tableau de Bord</a></li>
        
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <li class="nav-item"><a class="nav-link <?php if ($currentPage == 'personnel.php') echo 'active'; ?>" href="personnel.php">Personnel</a></li>
        <?php endif; ?>
        
        <li class="nav-item"><a class="nav-link <?php if ($currentPage == 'dossiers_medicaux.php') echo 'active'; ?>" href="dossiers_medicaux.php">Dossiers médicaux</a></li>
        
        <li class="nav-item">
            <a class="nav-link <?php if ($currentPage == 'heures_service.php') echo 'active'; ?>" href="heures_service.php">Heures de Service</a>
        </li>
        <li class="nav-item"><a class="nav-link <?php if ($currentPage == 'facturation.php') echo 'active'; ?>" href="facturation.php">Facturation</a></li>
    <?php endif; ?>
</ul>
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"><i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="logout.php">Déconnexion</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Connexion</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">