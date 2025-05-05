<?php

require_once __DIR__ . '/../../enums/vers_page.php';
use App\Enums\vers_page;
$url = "http://" . $_SERVER["HTTP_HOST"];
$CSS_detail = vers_page::CSS_detail->value;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord étudiant</title>
    <link rel="stylesheet" href="<?= $url . $CSS_detail ?>">

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