<!DOCTYPE html>
<html lang="fr">
<?php
require_once __DIR__ . '/../../enums/chemin_page.php';

use App\Enums\CheminPage;

$url = "http://" . $_SERVER["HTTP_HOST"];
$css_ref = CheminPage::CSS_REFERENCIEL->value;
require_once __DIR__ . '/../../services/session.service.php';

$errors = recuperer_session('errors', []);
$old = recuperer_session('old_inputs', []);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Référentiels</title>
    <link rel="stylesheet" href="<?= $url . $css_ref ?>">
</head>

<body>
    <div class="ref-container">
        <header>
            <h1>Référentiels</h1>
            <p>Gérer les référentiels de la promotion</p>
        </header>

        <div class="search-bar">
            <form method="GET" action="">
                <input type="hidden" name="page" value="referenciel">
                <input

                    type="text"
                    name="search"
                    placeholder="Rechercher un référentiel..."
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </form>
            <div class="actions">
                <button class="btn btn-orange" onclick="location.href='?page=all_referenciel'">
                    📋 Tous les référentiels
                </button>
                <button class="btn btn-green" onclick="location.href='index.php?page=affecter_ref_promo'">+ Ajouter à la promotion</button>
            </div>
        </div>

        <div class="ref-grid">
            <?php foreach ($referentiels as $ref): ?>
                <div class="ref-card">
                    <div class="ref-image">
                        <img src="<?= htmlspecialchars($ref['photo']) ?>" alt="<?= htmlspecialchars($ref['nom']) ?>">
                    </div>
                    <div class="ref-content">
                        <h3><?= htmlspecialchars($ref['nom']) ?></h3>
                        <p class="description">
                            <?= htmlspecialchars($ref['description'] ?? 'Aucune description disponible') ?>
                        </p>
                        <div class="ref-stats">
                            <span><?= $ref['modules'] ?? 0 ?> modules</span>
                            <span><?= $ref['apprenants'] ?? 0 ?> apprenants</span>
                        </div>
                        <div class="ref-capacity">
                            <span>Capacité: <?= $ref['capacite'] ?> places</span>
                        </div>
                        <div class="apprenant-icons">
                            <?php
                            $totalApprenants = min(($ref['apprenants'] ?? 0), 3);
                            for ($i = 0; $i < $totalApprenants; $i++):
                            ?>
                                <div class="apprenant-icon"></div>
                            <?php endfor; ?>
                            <?php if (($ref['apprenants'] ?? 0) > 3): ?>
                                <div class="remaining-count">+<?= ($ref['apprenants'] - 3) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Popup d'ajout de référentiel -->
    


    <?php if (!empty($errors)): ?>
        <script>
            window.onload = function() {
                // Force l'ouverture du popup en changeant l'URL avec le #popup-add
                if (!location.hash.includes('popup-add')) {
                    location.href = location.href.split('#')[0] + '#popup-add';
                }
            };
        </script>
    <?php endif; ?>


</body>

</html>
<style>
    .alert {
        border: 2px solid red !important;
    }

    .error-message {
        color: red;
        font-size: 0.8rem;
        margin-top: 0.5rem;
    }
</style>