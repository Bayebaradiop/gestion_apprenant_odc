<?php
require_once __DIR__ . '/../../enums/vers_page.php';
use App\Enums\vers_page;

$url = "http://" . $_SERVER["HTTP_HOST"];
$CSS_entente = vers_page::CSS_entente->value;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des apprenants en attente</title>
    <link rel="stylesheet" href="<?= $url . $CSS_entente ?>">
</head>
<body>
    <div class="containee">
        <h2>Liste des apprenants en attente</h2>

        <!-- Barre de recherche et filtres (non fonctionnelle ici, à brancher si besoin) -->
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Rechercher un apprenant...">
            <select class="search-input">
                <option value="">Tous les référentiels</option>
                <option value="dev-web">Développement Web</option>
                <option value="data">Data Science</option>
                <option value="cybersecu">Cybersécurité</option>
            </select>
        </div>

        <?php if (empty($apprenants)): ?>
            <div class="empty-state">
                <p>Aucun apprenant en attente pour le moment.</p>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nom complet</th>
                            <th>Login</th>
                            <th>Référentiel</th>
                            <th>Motif de rejet</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($apprenants as $a): ?>
                            <tr>
                                <td><?= htmlspecialchars($a['nom_complet'] ?? '') ?></td>
                                <td><?= htmlspecialchars($a['login'] ?? '') ?></td>
                                <td><?= htmlspecialchars($a['referenciel'] ?? '') ?></td>
                                <td>
                                    <?php if (!empty($a['motif_rejet'])): ?>
                                        <span class="status-badge rejected"><?= htmlspecialchars($a['motif_rejet']) ?></span>
                                    <?php else: ?>
                                        <span class="status-badge no-reason">Non précisé</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($a['matricule'])): ?>
                                        <a href="index.php?page=modifier_entente&matricule=<?= urlencode($a['matricule']) ?>" class="action-button approve">Corriger</a>
                                    <?php else: ?>
                                        <span class="status-badge rejected">Matricule manquant</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>