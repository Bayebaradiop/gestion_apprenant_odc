<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord étudiant</title>
    <style>
        /* Variables */
        :root {
            --primary-color:#FF9933;
            --secondary-color: #FF9933;
            --success-color: #10B981;
            --danger-color: #EF4444;
            --warning-color: #F59E0B;
            --light-bg: #F9FAFB;
            --dark-text: #1F2937;
            --light-text: #6B7280;
            --border-color: #E5E7EB;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
            --border-radius: 12px;
        }

        /* Reset & Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            background-color: var(--light-bg);
            color: var(--dark-text);
            line-height: 1.6;
        }

        /* Layout Global */
        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* Right content */
        .right-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Topbar */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
            background: #fff;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .input-container {
            position: relative;
            width: 300px;
        }

        .input-container input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            border: 1px solid var(--border-color);
            border-radius: 9999px;
            background-color: var(--light-bg);
            transition: var(--transition);
            font-size: 14px;
        }

        .input-container::before {
            content: "🔍";
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--light-text);
        }

        .input-container input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .top-right {
            display: flex;
            align-items: center;
        }

        .icons-topbar {
            width: 24px;
            height: 24px;
            cursor: pointer;
            transition: var(--transition);
        }

        .icons-topbar:hover {
            opacity: 0.7;
        }

        /* Main Content */
        .content-variable {
            padding: 24px;
            flex: 1;
        }

        .main-container {
            display: flex;
            gap: 24px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Profile Sidebar */
        .profile-section {
            width: 300px;
            background: #fff;
            border-radius: var(--border-radius);
            padding: 24px;
            box-shadow: var(--card-shadow);
            position: sticky;
            top: 24px;
            height: fit-content;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            color: var(--primary-color);
            text-decoration: none;
            margin-bottom: 20px;
            font-weight: 500;
            font-size: 14px;
            transition: var(--transition);
        }

        .back-button:hover {
            opacity: 0.8;
        }

        .profile-pic {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .profile-pic img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-name {
            text-align: center;
            margin-bottom: 12px;
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-text);
        }

        .status-badge {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            border-radius: 9999px;
            padding: 6px 12px;
            font-size: 13px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .action-button {
            display: block;
            width: 100%;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
            padding: 10px;
            border-radius: 9999px;
            text-align: center;
            margin-bottom: 24px;
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
        }

        .action-button:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .contact-info {
            background-color: var(--light-bg);
            padding: 16px;
            border-radius: var(--border-radius);
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            font-size: 14px;
            color: var(--light-text);
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-icon {
            margin-right: 10px;
            font-size: 16px;
        }

        /* Details Section */
        .details-section {
            flex: 1;
        }

        /* Stats Cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #fff;
            border-radius: var(--border-radius);
            padding: 20px;
            display: flex;
            align-items: center;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            font-size: 20px;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
        }

        .stat-number {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--light-text);
        }

        .green { 
            background-color: rgba(16, 185, 129, 0.1); 
            color: var(--success-color);
        }

        .orange { 
            background-color: rgba(245, 158, 11, 0.1); 
            color: var(--warning-color);
        }

        .red { 
            background-color: rgba(239, 68, 68, 0.1); 
            color: var(--danger-color);
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .tab {
            background: #fff;
            padding: 10px 20px;
            border-radius: 9999px;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            transition: var(--transition);
            border: 1px solid var(--border-color);
        }

        .tab:hover {
            border-color: var(--secondary-color);
        }

        .tab.active {
            background: var(--secondary-color);
            color: white;
            border-color: var(--secondary-color);
        }

        /* Table */
        .attendance-container {
            background: #fff;
            padding: 24px;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .attendance-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .attendance-table thead {
            background: var(--secondary-color);
            color: white;
        }

        .attendance-table th, .attendance-table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .attendance-table th {
            font-weight: 500;
            letter-spacing: 0.5px;
            font-size: 14px;
        }

        .attendance-table th:first-child {
            border-top-left-radius: var(--border-radius);
        }

        .attendance-table th:last-child {
            border-top-right-radius: var(--border-radius);
        }

        .attendance-table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.01);
        }

        .attendance-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .main-container {
                flex-direction: column;
            }
            
            .profile-section {
                width: 100%;
            }
            
            .stats-row {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stats-row {
                grid-template-columns: repeat(1, 1fr);
            }
            
            .tabs {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
<div class="layout">
  <!-- Ne pas ajouter un nouveau sidebar car vous en avez déjà un -->

  <!-- Right content -->
  <div class="right-content">
    <!-- Topbar -->
    <header class="topbar">
      <div class="input-container">
        <input type="text" placeholder="Rechercher...">
      </div>
      <div class="top-right">
        <img class="icons-topbar" src="/ges-apprenant/public/assets/icons/notif.png" alt="Notifications">
      </div>
    </header>

    <!-- Main Content -->
    <main class="content-variable">
      <div class="main-container">
        <div class="profile-section">
          <a href="index.php?menu=apprenant" class="back-button">← Retour</a>
          <div class="profile">
            <div class="profile-pic">
              <img src="<?= htmlspecialchars($apprenant['photo'] ?? '/public/assets/images/default.png') ?>" alt="Profil">
            </div>
            <h3 class="profile-name"><?= htmlspecialchars($apprenant['nom_complet']) ?></h3>
            <div class="status-badge">
              <?php
                $referencielName = 'Non défini';
                foreach ($referenciels as $ref) {
                    if ($ref['id'] == $apprenant['referenciel']) {
                        $referencielName = $ref['nom'];
                        break;
                    }
                }
                echo htmlspecialchars($referencielName);
              ?>
            </div>
            <button class="action-button">Modifier Profil</button>
            <div class="contact-info">
              <div class="info-item"><span class="info-icon">✉️</span><?= htmlspecialchars($apprenant['login']) ?></div>
              <div class="info-item"><span class="info-icon">🏠</span><?= htmlspecialchars($apprenant['adresse']) ?></div>
              <div class="info-item"><span class="info-icon">📞</span><?= htmlspecialchars($apprenant['telephone']) ?></div>
            </div>
          </div>
        </div>

        <div class="details-section">
          <div class="stats-row">
            <div class="stat-card">
              <div class="stat-icon green">✓</div>
              <div>
                <div class="stat-number">20</div>
                <div class="stat-label">Présence(s)</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon orange">⏰</div>
              <div>
                <div class="stat-number">5</div>
                <div class="stat-label">Retard(s)</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon red">⚠️</div>
              <div>
                <div class="stat-number">1</div>
                <div class="stat-label">Absence(s)</div>
              </div>
            </div>
          </div>

          <div class="tabs">
            <div class="tab active">Infos Générales</div>
            <div class="tab">Présences</div>
            <div class="tab">Notes</div>
          </div>

          <div class="attendance-container">
            <table class="attendance-table">
              <thead>
                <tr>
                  <th>Matricule</th>
                  <th>Nom</th>
                  <th>Email</th>
                  <th>Téléphone</th>
                  <th>Adresse</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?= htmlspecialchars($apprenant['matricule']) ?></td>
                  <td><?= htmlspecialchars($apprenant['nom_complet']) ?></td>
                  <td><?= htmlspecialchars($apprenant['login']) ?></td>
                  <td><?= htmlspecialchars($apprenant['telephone']) ?></td>
                  <td><?= htmlspecialchars($apprenant['adresse']) ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>
</body>
</html>