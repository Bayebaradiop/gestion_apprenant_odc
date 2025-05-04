<!DOCTYPE html>
<html lang="fr">
<head>
  <?php
  require_once __DIR__ . '/../../enums/chemin_page.php';
  use App\Enums\CheminPage;
  $url = "http://" . $_SERVER["HTTP_HOST"];
  $css_promo = CheminPage::CSS_PROMO->value;
  ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des promotions</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= $url . $css_promo ?>">
</head>

<body>
  <div class="promo-container">
    <header class="header">
      <h2>Promotion</h2>
      <p>Gérer les promotions de l'école</p>
    </header>

    <div class="stats">
      <div class="stat orange">
        <div class="stat-content">
          <strong class="stat-value">0</strong>
          <span class="stat-label">Apprenants</span>
        </div>
        <div class="icon"><img src="/assets/images/icone1.png" alt=""></div>
      </div>
      <div class="stat orange">
        <div class="stat-content">
          <strong class="stat-value"><?= $nbRefPromoActive ?></strong>
          <span class="stat-label">Référentiels</span>
        </div>
        <div class="icon"><img src="/assets/images/ICONE2.png" alt=""></div>
      </div>
      <div class="stat orange">
        <div class="stat-content">
          <strong class="stat-value">1</strong>
          <span class="stat-label">Promotion activée</span>
        </div>
        <div class="icon"><img src="/assets/images/ICONE3.png" alt=""></div>
      </div>
      <div class="stat orange">
        <div class="stat-content">
          <strong class="stat-value"><?= $totalPromotions ?></strong>
          <span class="stat-label">Total promotions</span>
        </div>
        <div class="icon"><img src="/assets/images/ICONE4.png" alt=""></div>
      </div>
      <a href="index.php?page=add_promo" class="add-btn">+ Ajouter une promotion</a>
    </div>

    <div class="search-filter">
      <form method="GET" action="" style="display: flex; flex: 1;">
        <input type="hidden" name="page" value="liste_promo" />
        <input type="text" name="search" placeholder="Rechercher une promotion..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" />

        <select name="filtre">
          <option value="tous">Tous</option>
          <option value="active" <?= ($_GET['filtre'] ?? '') === 'active' ? 'selected' : '' ?>>Actives</option>
          <option value="inactive" <?= ($_GET['filtre'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactives</option>
        </select>
        <button type="submit" class="submit-btn" style="background-color:#f37021;color:white; border:none">Rechercher</button>
      </form>

      <div class="view-toggle">
        <form method="GET" action="">
          <button class="active">Grille</button>
          <input type="hidden" name="page" value="liste_table_promo" />
          <button type="submit">Liste</button>
        </form>
      </div>
    </div>

    <div class="card-grid">
      <?php foreach ($promotions as $promo): ?>
        <div class="promo-card">
          <div class="promo-header">

            <div class="toggle-container">
              <form method="GET" action="index.php">
                <input type="hidden" name="page" value="activer_promo">
                <input type="hidden" name="activer_promo" value="<?= $promo['id'] ?>">
                <button type="submit" class="toggle-label <?= $promo['statut'] === 'Active' ? 'active' : 'inactive' ?>">
                  <div class="status-pill <?= $promo['statut'] === 'Active' ? 'active' : 'inactive' ?>"></div>
                  <span class="status-text"><?= $promo['statut'] === 'Active' ? 'Active' : 'Inactive' ?></span>
                </button>
              </form>
            </div>

            
          </div>

          <div class="promo-body">
            <div class="promo-image">
              <img src="<?= $promo['photo'] ?>" alt="<?= $promo['nom'] ?>">
            </div>
            <div class="promo-details">
              <h3><?= htmlspecialchars($promo['nom']) ?></h3>
              <p class="promo-date"><?= date("d/m/Y", strtotime($promo['dateDebut'])) ?> - <?= date("d/m/Y", strtotime($promo['dateFin'])) ?></p>
            </div>
          </div>

          <div class="student">
            <div class="promo-students">
              <p class="p"><?= $promo['nbrApprenant'] ?> apprenant<?= $promo['nbrApprenant'] > 1 ? "s" : "" ?></p>
            </div>
          </div>

          <div class="promo-footer">
            <button class="details-btn">Voir détails ></button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if ($pages > 1): 
      $filtre = $_GET['filtre'] ?? '';
      $search = $_GET['search'] ?? '';
      $baseUrl = "page=liste_promo&filtre=$filtre&search=$search";
    ?>
      <div class="custom-pagination">
        <a href="?<?= $baseUrl ?>&p=<?= max(1, $page - 1) ?>" class="arrow <?= $page === 1 ? 'disabled' : '' ?>">&#10094;</a>

        <?php for ($i = 1; $i <= $pages; $i++): ?>
          <a href="?<?= $baseUrl ?>&p=<?= $i ?>" class="page-number <?= $i === $page ? 'active' : '' ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>

        <a href="?<?= $baseUrl ?>&p=<?= min($pages, $page + 1) ?>" class="arrow <?= $page === $pages ? 'disabled' : '' ?>">&#10095;</a>
      </div>

      
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
      <script>
        window.onload = function() {
          if (!location.hash.includes('popup')) {
            location.href = location.href.split('#')[0] + '#popup';
          }
        };
      </script>
    <?php endif; ?>
  </div>
</body>
</html>
