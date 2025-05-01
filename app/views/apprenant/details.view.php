<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord étudiant</title>
    <style>
       /* Layout Global */
.layout {
  display: flex;
  min-height: 100vh;
}

/* Sidebar */
.left-sidebar {
  width: 220px;
  background-color: #fff;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 20px;
  border-right: 1px solid #eee;
}
.container-menu .menu {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
}
.menu img {
  width: 24px;
  margin-right: 10px;
}
.menu-title {
  font-size: 14px;
}
.deconnection {
  margin-top: 20px;
}
.deconn-link {
  color: red;
  text-decoration: none;
  font-weight: bold;
}

/* Topbar */
.topbar {
  display: flex;
  justify-content: space-between;
  padding: 15px;
  background: #fff;
  border-bottom: 1px solid #eee;
}
.input-container input {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
}

/* Main Content */
.right-content {
  flex: 1;
  display: flex;
  flex-direction: column;
}
.content-variable {
  padding: 20px;
}
.main-container {
  display: flex;
  gap: 20px;
}

/* Profile Sidebar */
.profile-section {
  width: 250px;
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.profile-pic img {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 50%;
  margin: 0 auto;
}
.profile-name {
  text-align: center;
  margin: 10px 0;
}
.status-badge {
  background-color: #00a389;
  color: white;
  text-align: center;
  border-radius: 20px;
  padding: 5px;
  font-size: 12px;
  margin-bottom: 10px;
}
.action-button {
  display: block;
  width: 100%;
  border: 1px solid #00a389;
  color: #00a389;
  background: transparent;
  padding: 8px;
  border-radius: 20px;
  text-align: center;
  margin-bottom: 15px;
  cursor: pointer;
}

/* Details Section */
.details-section {
  flex: 1;
}

/* Stats Cards */
.stats-row {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
}
.stat-card {
  flex: 1;
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  display: flex;
  align-items: center;
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}
.stat-icon {
  font-size: 20px;
  width: 40px;
  height: 40px;
  background: #eee;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 15px;
}
.green { background-color: #e6f7f4; }
.orange { background-color: #fff2e6; }
.red { background-color: #ffeaea; }

/* Tabs */
.tabs {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}
.tab {
  background: #f1f1f1;
  padding: 10px 20px;
  border-radius: 20px;
  cursor: pointer;
}
.tab.active {
  background: #ff9933;
  color: white;
}

/* Table */
.attendance-container {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  overflow-x: auto;
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}
.attendance-table {
  width: 100%;
  border-collapse: collapse;
}
.attendance-table thead {
  background: #ff9933;
  color: white;
}
.attendance-table th, .attendance-table td {
  padding: 12px 15px;
  text-align: left;
}
.student-photo {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  object-fit: cover;
}
.badge {
  display: inline-block;
  padding: 5px 10px;
  border-radius: 20px;
  font-size: 12px;
}
.present { background: #00a389; color: white; }
.absent { background: #ff5e5e; color: white; }
.late { background: #ff9933; color: white; }
.justified {
  color: #00a389;
}
.action-dots {
  font-size: 18px;
  cursor: pointer;
  color: #888;
}


.profile-pic {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 15px;
}

    </style>
</head>
<body>
<div class="layout">
  <!-- Sidebar -->
  <

  <!-- Right content -->
  <div class="right-content">
    <!-- Topbar -->
    <header class="topbar">
      <div class="input-container">
        <input type="text" class="icon-input" placeholder="Rechercher...">
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
      <div class="stat-card green">
        <div class="stat-icon">✓</div>
        <div><div class="stat-number">20</div><div class="stat-label">Présence(s)</div></div>
      </div>
      <div class="stat-card orange">
        <div class="stat-icon">⏰</div>
        <div><div class="stat-number">5</div><div class="stat-label">Retard(s)</div></div>
      </div>
      <div class="stat-card red">
        <div class="stat-icon">⚠️</div>
        <div><div class="stat-number">1</div><div class="stat-label">Absence(s)</div></div>
      </div>
    </div>

    <div class="tabs">
      <div class="tab active">Infos Générales</div>
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