<?php
require_once __DIR__ . '/../../enums/chemin_page.php';
use App\Enums\CheminPage;
require_once CheminPage::ERROR_FR->value;
require_once CheminPage::ERREUR_ENUM->value;

$errors = recuperer_session('errors', []);
$success = recuperer_session('success');
?>

<?php if (!empty($errors)): ?>
    <div class="alert-error" style="background-color: #ffdddd; color: #d8000c; padding: 1rem; margin: 1rem 0; border: 1px solid #d8000c; border-radius: 8px;">
        <ul>
            <?php foreach ($errors as $cleErreur): ?>
                <?php
                $parts = explode('ligne', $cleErreur);
                $cle_sans_ligne = $parts[0];
                $ligne = $parts[1] ?? null;
                $message = $error_messages[$cle_sans_ligne] ?? $cleErreur;
                ?>
                <li><?= htmlspecialchars($message . ($ligne ? " (ligne $ligne)" : '')) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert-success" style="background-color: #ddffdd; color: #270; padding: 1rem; margin: 1rem 0; border: 1px solid #270; border-radius: 8px;">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>




<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Liste des Apprenants</title>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }
    body {
      background: #f5f6fa;
      width: 100%;
      padding: 0;
      margin: 0;
    }

    /* Topbar */
    .topbar {
      background: white;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .topbar-left {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .search-container {
      position: relative;
    }
    .search-container input {
      padding: 10px 35px 10px 15px;
      border: none;
      background: #f1f1f1;
      border-radius: 10px;
      width: 250px;
    }
    .search-container::before {
      content: "🔍";
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 18px;
      color: #999;
    }
    .topbar-right {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .notif-icon {
      font-size: 22px;
      cursor: pointer;
    }
    .user-profile {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .user-profile img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
    }

    /* Container principal */
    .containerapp {
      padding: 30px;
    }
    .title {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 25px;
    }
    .title h2 {
      font-size: 2rem;
      color: #009688;
    }
    .badge-count {
      background: #ffe0b2;
      color: #ff7a00;
      padding: 5px 12px;
      border-radius: 30px;
      font-size: 0.9rem;
      font-weight: bold;
    }

    /* Filtres + Actions */
    .filters-actions {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 20px;
      align-items: center;
    }
    .filters {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }
    .filters input, .filters select {
      padding: 12px 15px;
      border-radius: 10px;
      border: none;
      background: white;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      min-width: 180px;
    }
    .actions {
      display: flex;
      gap: 10px;
      align-items: center;
    }
    .btn-download, .add-btn {
      padding: 12px 20px;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      font-weight: bold;
    }
    .btn-download {
      background: #000;
      color: white;
    }
    .add-btn {
      background: #009688;
      color: white;
    }
    .export {
      position: relative;
    }
    .export-btn {
      background: #000;
      color: white;
      padding: 10px 15px;
      border-radius: 10px;
      border: none;
      cursor: pointer;
    }
    .export-menu {
      display: none;
      position: absolute;
      background: white;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
      margin-top: 5px;
      right: 0;
      border-radius: 8px;
      overflow: hidden;
    }
    .export:hover .export-menu {
      display: block;
    }
    .export-menu a, .export-menu input, .export-menu button {
      display: block;
      width: 100%;
      padding: 10px;
      text-decoration: none;
      color: black;
      font-size: 0.9rem;
      background: white;
      border: none;
      text-align: left;
    }
    .export-menu a:hover {
      background: #eee;
    }

    /* Tabs */
    .tabs {
      display: flex;
      background: white;
      justify-content: center;
      gap: 100px;
      padding: 15px 0;
      border-radius: 15px;
      margin: 30px 0;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .tabs button {
      background: none;
      border: none;
      font-weight: bold;
      font-size: 1rem;
      cursor: pointer;
      color: #555;
      padding-bottom: 5px;
      position: relative;
    }
    .tabs button.active::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: #ff7a00;
      border-radius: 10px;
    }

    /* Table */
    .table-container {
      background: white;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      overflow-x: auto;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    thead {
      background: #ff7a00;
      color: white;
    }
    th, td {
      padding: 15px;
      text-align: left;
      font-size: 0.9rem;
    }
    tr {
      background: white;
      border-bottom: 1px solid #eee;
    }
    .photo {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
    }
    .badge {
      display: inline-block;
      padding: 5px 10px;
      border-radius: 12px;
      font-size: 0.8rem;
      font-weight: bold;
    }
    .badge.ref {
      background: #e0f7fa;
      color: #00796b;
    }
    .badge.status {
      background: #d0f0c0;
      color: #2e7d32;
    }
    .actions-btn {
      background: none;
      border: none;
      font-size: 20px;
      cursor: pointer;
    }

    /* Alertes */
    .alert {
      padding: 15px 20px;
      margin-bottom: 20px;
      border-radius: 10px;
      font-size: 0.95rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      animation: fadeIn 0.5s ease-in-out;
    }
    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border-left: 5px solid #28a745;
    }
    .alert-error {
      background-color: #f8d7da;
      color: #721c24;
      border-left: 5px solid #dc3545;
    }
    .alert p {
      margin: 0;
      padding: 0;
    }
    .alert .close-btn {
      background: none;
      border: none;
      font-size: 20px;
      font-weight: bold;
      color: inherit;
      cursor: pointer;
    }
    @keyframes fadeIn {
      from {opacity: 0;}
      to {opacity: 1;}
    }



    .pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
    gap: 5px;
}

.pagination a {
    padding: 10px 15px;
    text-decoration: none;
    color: #00796b;
    border: 1px solid #ddd;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.pagination a.active {
    background-color: #00796b;
    color: white;
    border-color: #00796b;
}

.pagination a:hover {
    background-color: #e0f7fa;
}
  </style>
</head>

<body>

<!-- Topbar -->
<div class="topbar">
  <div class="topbar-left">
    <div class="search-container">
      <input type="text" placeholder="Search">
    </div>
  </div>
  <div class="topbar-right">
    <span class="notif-icon">🔔</span>
    <div class="user-profile">
      <img src="default_avatar.jpg" alt="Profil">
      <div>
        <div>Awa Niang</div>
        <small>Admin</small>
      </div>
    </div>
  </div>
</div>

<!-- Main Container -->
<div class="containerapp">

  <!-- Title -->
  <div class="title">
    <h2>Apprenants</h2>
    <span class="badge-count"><?= count($apprenants) ?> apprenants</span>
  </div>

  <!-- Filters and Actions -->
  <div class="filters-actions">

  <form method="GET" action="index.php" style="display:flex; gap:15px; flex-wrap:wrap;">
    <input type="hidden" name="page" value="liste_apprenant">

    <input 
      type="text" 
      name="search" 
      placeholder="Rechercher par nom complet..." 
      value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
    >

    <select name="referenciel">
      <option value="">Filtrer par classe</option>
      <?php foreach ($referenciels as $referenciel): ?>
        <option value="<?= htmlspecialchars($referenciel['id']) ?>" 
          <?= (isset($_GET['referenciel']) && $_GET['referenciel'] == $referenciel['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($referenciel['nom']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <select name="statut">
      <option value="">Filtrer par statut</option>
      <option value="Retenu" <?= (($_GET['statut'] ?? '') == 'Retenu') ? 'selected' : '' ?>>Retenu</option>
      <option value="Remplacer" <?= (($_GET['statut'] ?? '') == 'Remplacer') ? 'selected' : '' ?>>Remplacer</option>
      <option value="En attente" <?= (($_GET['statut'] ?? '') == 'En attente') ? 'selected' : '' ?>>En attente</option>
    </select>

    <button type="submit" class="add-btn">🔍 Rechercher</button>
  </form>

  

    <div class="actions">
      <form class="export" action="index.php?page=import_apprenants" method="POST" enctype="multipart/form-data">
        <div class="export-btn">📁 Importer / Exporter ▼
          <div class="export-menu">
            <a href="#">📄 Exporter PDF</a>
            <a href="#">📋 Exporter Excel</a>
            <hr>
            <input type="file" name="import_excel" accept=".csv,.xlsx" required>
            <button type="submit" class="add-btn" style="margin-top:5px;">Importer Excel</button>
          </div>
        </div>
      </form>
      <a  href="index.php?page=ajouter_apprenant" class="add-btn">+ Ajouter Apprenant</a>
    </div>


    
  </div>

  <!-- Tabs -->
  <div class="tabs">
  <?php if (!empty($errors)): ?>
    <div class="alert-error" style="background-color: #ffe0e0; border:1px solid #ff0000; padding:1rem; margin: 1rem 0; border-radius: 8px;">
        <h3 style="color: #c00;">Erreurs lors de l'importation :</h3>
        <ul style="list-style: inside;">
            <?php foreach ($errors as $error): ?>
                <li style="color: #900;"><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

    <button class="active">Liste des retenues</button>
    <button>Liste d'attente</button>
  </div>

  <!-- Flash messages -->
  <?php if (!empty($_SESSION['errors'])): ?>
    <div class="alert alert-error">
      <div>
        <?php foreach ($_SESSION['errors'] as $error): ?>
          <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
      </div>
      <button class="close-btn" onclick="this.parentElement.style.display='none';">×</button>
    </div>
    <?php unset($_SESSION['errors']); ?>
  <?php endif; ?>

  <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
      <div><p><?= htmlspecialchars($_SESSION['success']) ?></p></div>
      <button class="close-btn" onclick="this.parentElement.style.display='none';">×</button>
    </div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <!-- Table -->
  <div class="table-container">
    
    <table>
      
        <tr>
          <th>Photo</th>
          <th>Matricule</th>
          <th>Nom Complet</th>
          <th>Adresse</th>
          <th>Téléphone</th>
          <th>Référentiel</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      

      <tbody>
<?php if (!empty($apprenants)): ?>
  <?php foreach ($apprenants as $apprenant): ?>
    <tr>
      <td>
        <?php if (!empty($apprenant['photo'])): ?>
          <img src="<?= htmlspecialchars($apprenant['photo']) ?>" alt="Photo" class="photo">
        <?php else: ?>
          <img src="default_photo.jpg" alt="Photo par défaut" class="photo">
        <?php endif; ?>
      </td>
      <td><?= htmlspecialchars($apprenant['matricule'] ?? '') ?></td>
      <td><?= htmlspecialchars($apprenant['nom_complet'] ?? $apprenant['nom'] ?? '') ?></td>
      <td><?= htmlspecialchars($apprenant['adresse'] ?? '') ?></td>
      <td><?= htmlspecialchars($apprenant['telephone'] ?? '') ?></td>
      <td>
        <?php
          $nomReferenciel = 'Non défini';
          foreach ($referenciels as $ref) {
              if (isset($apprenant['referenciel']) && $ref['id'] == $apprenant['referenciel']) {
                  $nomReferenciel = $ref['nom'];
                  break;
              }
          }
        ?>
        <span class="badge ref"><?= htmlspecialchars($nomReferenciel) ?></span>
      </td>
      <td>
        <?php if (($apprenant['statut'] ?? '') === 'Retenu'): ?>
          <span class="badge status" style="background-color: #d0f0c0; color: #2e7d32;">Retenu</span>
        <?php elseif (($apprenant['statut'] ?? '') === 'Remplacer'): ?>
          <span class="badge status" style="background-color: #ffeeba; color:rgb(255, 4, 4);">Remplacer</span>
        <?php else: ?>
          <span class="badge status" style="background-color: #f8d7da; color:rgb(228, 110, 122);">En attente</span>
        <?php endif; ?>
      </td>
      <td>
    <a href="index.php?page=detail_apprenant&id=<?= htmlspecialchars($apprenant['id']) ?>" 
       class="add-btn" 
       style="padding: 8px 12px; font-size: 0.85rem; text-decoration: none;">
       📄 Détails
    </a>
</td>
    </tr>
  <?php endforeach; ?>
<?php else: ?>
  <tr><td colspan="8" style="text-align:center;">Aucun apprenant trouvé</td></tr>
<?php endif; ?>
</tbody>

    </table>
  </div>

</div>
<?php if ($pagination['pages'] > 1): ?>
<div class="pagination">
    <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
        <a href="index.php?page=liste_apprenant&pageCourante=<?= $i ?>&search=<?= urlencode($_GET['search'] ?? '') ?>&referenciel=<?= urlencode($_GET['referenciel'] ?? '') ?>"
           class="<?= $i === $pagination['pageCourante'] ? 'active' : '' ?>">
           <?= $i ?>
        </a>
    <?php endfor; ?>
</div>
<?php endif; ?>

</body>
</html>


