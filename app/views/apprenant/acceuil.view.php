<?php
require_once __DIR__ . '/../../enums/vers_page.php';
use App\Enums\vers_page;
$url = "http://" . $_SERVER["HTTP_HOST"];
$CSS_acceuil = vers_page::CSS_acceuil->value;
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Sonatel</title>
    <!-- <style>
        /* Variables et styles généraux */
        :root {
            --primary-color: #f26522;
            --secondary-color: #17a684;
            --light-bg: #f8f9fa;
            --border-radius: 8px;
            --box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            --orange-bg: #fff8f3;
            --green-bg: #eefbf7;
            --red-bg: #ffeeee;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f0f2f5;
        }
        
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #ffffff;
            border-bottom: 1px solid #e5e5e5;
        }
        
        .logo img {
            height: 40px;
        }
        
        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-color);
        }
        
        /* Main container */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .page-title {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 20px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(242, 101, 34, 0.2);
        }
        
        /* Cards container */
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 20px;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        
        /* User Profile Card */
        .profile-card {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .profile-big-img {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            object-fit: cover;
        }
        
        .profile-info h2 {
            color: #333;
            margin-bottom: 5px;
        }
        
        .user-job {
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 15px;
        }
        
        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #666;
        }
        
        .icon-circle {
            background-color: var(--primary-color);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            box-shadow: 0 3px 5px rgba(242, 101, 34, 0.3);
        }
        
        /* Présences Card */
        .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
        
        .stats-container {
            display: flex;
            justify-content: space-between;
            text-align: center;
        }
        
        .stat-item {
            padding: 15px;
            border-radius: var(--border-radius);
            flex: 1;
            margin: 0 5px;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
        }
        
        .present {
            background-color: var(--green-bg);
            color: var(--secondary-color);
        }
        
        .delay {
            background-color: var(--orange-bg);
            color: var(--primary-color);
        }
        
        .absent {
            background-color: var(--red-bg);
            color: #dc3545;
        }
        
        /* Chart Card */
        .chart-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 200px;
        }
        
        .donut-chart {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: conic-gradient(
                var(--secondary-color) 0% 87%,
                var(--primary-color) 87% 100%,
                #dc3545 100% 100%
            );
            position: relative;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        .donut-hole {
            position: absolute;
            width: 90px;
            height: 90px;
            background-color: white;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        
        .chart-legend {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 10px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
        }
        
        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 2px;
        }
        
        .color-present {
            background-color: var(--secondary-color);
        }
        
        .color-delay {
            background-color: var(--primary-color);
        }
        
        .color-absent {
            background-color: #dc3545;
        }
        
        /* QR Code Card */
        .qr-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .qr-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }
        
        .qr-code {
            width: 180px;
            height: 180px;
            margin-bottom: 20px;
        }
        
        .personal-code {
            margin-top: 10px;
            color: #666;
        }
        
        .code-number {
            font-weight: 600;
            color: #333;
            margin-top: 5px;
        }
        
        /* History Card */
        .history-card {
            grid-column: 1 / -1;
        }
        
        .search-filter {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .search-bar {
            display: flex;
            align-items: center;
            background-color: var(--light-bg);
            border-radius: 20px;
            padding: 5px 15px;
            flex: 1;
            max-width: 300px;
        }
        
        .search-bar input {
            border: none;
            background: none;
            outline: none;
            padding: 8px;
            width: 100%;
        }
        
        .filter-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            background-color: var(--light-bg);
            border: none;
            border-radius: 20px;
            padding: 8px 15px;
            cursor: pointer;
            color: #666;
        }
        
        .history-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .history-table th {
            text-align: left;
            padding: 15px;
            background-color: var(--light-bg);
            color: #666;
            font-weight: 500;
        }
        
        .history-table td {
            padding: 15px;
            border-bottom: 1px solid #e5e5e5;
        }
        
        .status-badge {
            background-color: var(--green-bg);
            color: var(--secondary-color);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
    </style> -->

    <link rel="stylesheet" href="<?= $url . $CSS_acceuil ?>">

</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="logo">
            <svg width="120" height="40" viewBox="0 0 120 40">
                <rect x="0" y="5" width="100" height="30" rx="5" fill="#f26522" />
                <text x="50" y="25" font-family="Arial" font-size="16" font-weight="bold" fill="white" text-anchor="middle">sonatel</text>
                <rect x="105" y="5" width="10" height="30" rx="3" fill="#f26522" />
            </svg>
        </div>
        <div class="profile">
            <span><?= htmlspecialchars($user['nom_complet'] ?? '') ?></span>
            <img src="<?= htmlspecialchars($user['photo'] ?? '/uploads/photos/default.png') ?>" alt="Photo Profil" class="profile-img">
            </div>
    </header>
    
    <!-- Main Container -->
    <div class="main-container">
        <!-- Page Title -->
        <div class="page-title">
            Tableau de Bord
        </div>
        
        <!-- Cards Container -->
        <div class="cards-container">
            <!-- Profile Card -->
            <div class="card">
                <div class="profile-card">
                    <img src="<?= htmlspecialchars($user['nom_complet'] ?? '') ?>" class="profile-big-img">
                    <div class="profile-info">
                        <h2><?= htmlspecialchars($user['nom_complet'] ?? '') ?></h2>
                        <div class="user-job">Apprenant</div>
                        <div class="contact-info">
                            <div class="info-item">
                                <div class="icon-circle">✉</div>
                                <span><?= htmlspecialchars($user['login'] ?? '') ?></span>
                            </div>
                            <div class="info-item">
                                <div class="icon-circle">🪪</div>
                                <span><?= htmlspecialchars($user['matricule'] ?? '') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques de présence -->
            <div class="card">
                <div class="card-header">
                    <div class="icon-circle">📊</div>
                    <span>Présences</span>
                </div>
                <div class="stats-container">
                    <div class="stat-item present">
                        <div class="stat-number">34</div>
                        <div class="stat-label">Présent</div>
                    </div>
                    <div class="stat-item delay">
                        <div class="stat-number">5</div>
                        <div class="stat-label">Retard</div>
                    </div>
                    <div class="stat-item absent">
                        <div class="stat-number">0</div>
                        <div class="stat-label">Absent</div>
                    </div>
                </div>
            </div>

            <!-- Chart Card -->
            <div class="card">
                <div class="card-header">
                    <div class="icon-circle">🕒</div>
                    <span>Répartition</span>
                </div>
                <div class="chart-container">
                    <div class="donut-chart">
                        <div class="donut-hole"></div>
                    </div>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <div class="legend-color color-present"></div>
                            <span>Présents</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color color-delay"></div>
                            <span>Retards</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color color-absent"></div>
                            <span>Absents</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code Card -->
         <!-- QR Code Card corrigée -->
         <div class="card qr-card">
                <div class="qr-title">Scanner pour la présence</div>
                <?php

                // Exemple de données à encoder dans le QR code  $qrData = $user['matricule'] ?? 'inconnu'; 
                    // $qrData = $user['nom_complet'] ?? 'inconnu';
                    // $qrData = $user['login'] ?? 'inconnu';



                $qrData = [
                    'nom_complet' => $user['nom_complet'] ?? 'inconnu',
                    'matricule' => $user['matricule'] ?? 'inconnu',
                    'login' => $user['login'] ?? 'inconnu',
                    
                ];

                $qrDataString = json_encode($qrData); // Convertir le tableau en JSON
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($qrDataString) . "&size=180x180";

                // Affichage de l'image QR code
                echo '<img src="' . $qrUrl . '" alt="QR Code">';
                ?>

                <!-- <img class="qr-code" src="<?= htmlspecialchars($qrUrl) ?>" alt="QR Code de <?= htmlspecialchars($user['nom_complet'] ?? '') ?>"> -->
                <div class="personal-code">Code de présence personnel</div>
                
            </div>

            <!-- Historique Card -->
            <div class="card history-card">
                <div class="card-header">
                    <div class="icon-circle">🕒</div>
                    <span>Historique de présence</span>
                </div>
                <div class="search-filter">
                    <div class="search-bar">
                        <span>🔍</span>
                        <input type="text" placeholder="Rechercher...">
                    </div>
                    <button class="filter-btn">
                        <span>🔍</span>
                        <span>Tous les statuts</span>
                    </button>
                </div>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>24/02/2025 08:01:31</td>
                            <td><span class="status-badge">PRÉSENT</span></td>
                        </tr>
                        <tr>
                            <td>25/02/2025 08:05:05</td>
                            <td><span class="status-badge">PRÉSENT</span></td>
                        </tr>
                        <tr>
                            <td>26/02/2025 08:03:26</td>
                            <td><span class="status-badge">PRÉSENT</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</body>
</html>