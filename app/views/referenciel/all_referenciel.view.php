<!DOCTYPE html>
<html lang="fr">
<?php
require_once __DIR__ . '/../../enums/chemin_page.php';

use App\Enums\CheminPage;

$url = "http://" . $_SERVER["HTTP_HOST"];
$css_ref = '/assets/css/referenciel/all_referenciel.css';
require_once __DIR__ . '/../../services/session.service.php';

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old_inputs'] ?? [];
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tous les Référentiels</title>
    <link rel="stylesheet" href="<?= $url . $css_ref ?>">
</head>

<body>
    <div class="ref-container">
        <div class="ref-header">
            <a href="?page=referenciel" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Retour aux référentiels actifs
            </a>
            <h1>Tous les Référentiels</h1>
            <p>Liste complète des référentiels de formation</p>
        </div>

        <div class="search-bar">
            <form method="GET" action="">
                <input type="hidden" name="page" value="all_referenciel">
                <input
                    type="text"
                    name="search"
                    placeholder="Rechercher un référentiel..."
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit" class="btn btn-blue">Rechercher</button>
            </form>
            <div class="actions">
                <a href="index.php?page=add_referentiel" class="btn btn-green" onclick="location.href='#popup-add'">+ Ajouter un référentiel</a>
            </div>
        </div>

        <div class="ref-grid">
            <?php foreach ($referentiels as $ref): ?>
                <div class="ref-card">
                    <img src="<?= htmlspecialchars($ref['photo']) ?>" alt="<?= htmlspecialchars($ref['nom']) ?>">
                    <div class="ref-content">
                        <h3><?= htmlspecialchars($ref['nom']) ?></h3>
                        <p><?= htmlspecialchars($ref['description'] ?? '') ?></p>
                        <div class="ref-info">
                            <span>Capacité: <?= $ref['capacite'] ?> places</span>
                            <span>Apprenants: <?= $ref['apprenants'] ?? 0 ?></span> <!-- Ajout du nombre d'apprenants -->
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=all_referenciel&p=<?= $page - 1 ?><?= !empty($searchTerm) ? '&search=' . htmlspecialchars($searchTerm) : '' ?>" class="pagination-link">
                        &laquo; Précédent
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total; $i++): ?>
                    <a href="?page=all_referenciel&p=<?= $i ?><?= !empty($searchTerm) ? '&search=' . htmlspecialchars($searchTerm) : '' ?>" 
                       class="pagination-link <?= $i === $page ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $total): ?>
                    <a href="?page=all_referenciel&p=<?= $page + 1 ?><?= !empty($searchTerm) ? '&search=' . htmlspecialchars($searchTerm) : '' ?>" class="pagination-link">
                        Suivant &raquo;
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <style>
            .pagination {
                display: flex;
                justify-content: center;
                gap: 10px;
                margin: 20px 0;
            }

            .pagination-link {
                padding: 8px 12px;
                border: 1px solid #ddd;
                border-radius: 4px;
                text-decoration: none;
                color: #333;
            }

            .pagination-link:hover {
                background-color: #f5f5f5;
            }

            .pagination-link.active {
                background-color: #007bff;
                color: white;
                border-color: #007bff;
            }

            .btn {
                padding: 8px 12px;
                border-radius: 4px;
                text-decoration: none;
                color: white;
                font-size: 14px;
                cursor: pointer;
            }

            .btn-blue {
                background-color: #007bff;
                border: none;
            }

            .btn-blue:hover {
                background-color: #0056b3;
            }

            .btn-green {
                background-color: #28a745;
                border: none;
            }

            .btn-green:hover {
                background-color: #218838;
            }
        </style>
    </div>
</body>

</html>