<!DOCTYPE html>
<html lang="fr">
<head>

  <?php
  require_once __DIR__ . '/../../enums/vers_page.php';

  use App\Enums\vers_page;

  $url = "http://" . $_SERVER["HTTP_HOST"];
  $css_promo = vers_page::CSS_PROMO->value;
  ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des promotions</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= $url . $css_promo ?>">
</head>

<body>
  <!-- En-tête -->
  <div class="header">
    <h1>Promotion</h1>
    <span class="count">30 apprenants</span>
  </div>

  <!-- Barre d'outils -->
  <div class="toolbar">
    <form method="GET" action="index.php" style="display: flex; flex: 1;">
        <div class="search-box">
            <input type="hidden" name="page" value="liste_table_promo">
            <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </div>
        <div class="filter-dropdown">
            <select>
                <option>Filtre par classe</option>
            </select>
        </div>
        <div class="filter-dropdown">
            <select name="filtre">
                <option value="">Tous</option>
                <option value="active" <?= ($_GET['filtre'] ?? '') === 'active' ? 'selected' : '' ?>>Actives</option>
                <option value="inactive" <?= ($_GET['filtre'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactives</option>
            </select>
        </div>
        <button type="submit" class="add-button">🔍 Rechercher</button>
    </form>
    <a href="index.php?page=add_promo" class="add-btn">+ Ajouter une promotion</a>
  </div>

  <!-- Cartes d'information -->
  <div class="cards">
    <div class="card">
      <div class="icon">
        <i class="fa fa-graduation-cap"></i>
      </div>
      <div class="info">
        <div class="number">30</div>
        <div class="label">Apprenants</div>
      </div>
    </div>
    <div class="card">
      <div class="icon">
        <i class="fa fa-folder"></i>
      </div>
      <div class="info">
        <div class="number"><?= $nbRefPromoActive ?></div>
        <div class="label">Référentiels</div>
      </div>
    </div>
    <div class="card">
      <div class="icon">
        <i class="fa fa-user-graduate"></i>
      </div>
      <div class="info">
        <div class="number">5</div>
        <div class="label">Stagiaires</div>
      </div>
    </div>
    <div class="card">
      <div class="icon">
        <i class="fa fa-users"></i>
      </div>
      <div class="info">
        <div class="number">13</div>
        <div class="label">Permanent</div>
      </div>
    </div>
  </div>

  <!-- Tableau -->
  <table>
    <thead>
      <tr>
        <th>Photo</th>
        <th>Promotion</th>
        <th>Date de début</th>
        <th>Date de fin</th>
        <th>Référentiel</th>
        <th>Statut</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($promotions as $promo): ?>
    <tr>
        <td class='photo-cell'><img src="<?= $promo["photo"] ?>" alt='photo' width='50'></td>
        <td class='promo-cell'><?= htmlspecialchars($promo["nom"]) ?></td>
        <td class='date-cell'><?= htmlspecialchars($promo["dateDebut"]) ?></td>
        <td class='date-cell'><?= htmlspecialchars($promo["dateFin"]) ?></td>
        <td>
            <div class='tag'>
                <?php
                $ref_ids = $promo["referenciels"] ?? [];
                $refs_affectes = array_filter(
                    $referentiels,
                    fn($ref) => in_array($ref["id"], $ref_ids)
                );
                foreach ($refs_affectes as $ref) {
                    echo "<span class='tag'>" . htmlspecialchars($ref['nom']) . "</span>";
                }
                ?>
            </div>
        </td>
        <td>
            <span class="status-label <?= $promo["statut"] === "Active" ? "active" : "inactive" ?>">
                <?= ucfirst(strtolower($promo["statut"])) ?>
            </span>
        </td>
        <td>
            <form method='GET' action='index.php'>
                <input type='hidden' name='page' value='activer_promo_liste'>
                <input type='hidden' name='activer_promo_liste' value='<?= $promo["id"] ?>'>
                <button type='submit' class='action-btn'>Changer</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Pagination -->
  <?php if ($pages > 1):    
  $filtre = $_GET['filtre'] ?? '';
  $search = $_GET['search'] ?? '';
  $baseUrl = "page=liste_table_promo&filtre=$filtre&search=$search&limit=$limit"; ?>
      <div class="custom-pagination">
          <!-- Flèche gauche -->
          <a href="index.php?<?= $baseUrl ?>&p=<?= max(1, $page - 1) ?>" 
             class="arrow <?= $page === 1 ? 'disabled' : '' ?>">&#10094;</a>

          <!-- Pages -->
          <?php for ($i = 1; $i <= $pages; $i++): ?>
              <a href="index.php?<?= $baseUrl ?>&p=<?= $i ?>" 
                 class="page-number <?= $i === $page ? 'active' : '' ?>">
                  <?= $i ?>
              </a>
          <?php endfor; ?>

          <!-- Flèche droite -->
          <a href="index.php?<?= $baseUrl ?>&p=<?= min($pages, $page + 1) ?>" 
             class="arrow <?= $page === $pages ? 'disabled' : '' ?>">&#10095;</a>
      </div>

  
  <?php endif; ?>

</body>

</html>
<style>
