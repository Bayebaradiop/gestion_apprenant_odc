<?php
require_once __DIR__ . '/../../enums/vers_page.php';
use App\Enums\vers_page;
require_once vers_page::ERROR_FR->value;
require_once vers_page::ERREUR_ENUM->value;

$errors = recuperer_session('errors', []);
$success = recuperer_session('success');

$url = "http://" . $_SERVER["HTTP_HOST"];
$CSS_liste = vers_page::CSS_liste->value;

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

  <link rel="stylesheet" href="<?= $url . $CSS_liste ?>">

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
    <a href="index.php?page=entente">en entente<label for="menu-toggle" class="menu-closer"></label></a>
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



