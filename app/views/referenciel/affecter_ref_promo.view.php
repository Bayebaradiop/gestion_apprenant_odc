<?php
require_once __DIR__ . '/../../enums/chemin_page.php';
use App\Enums\CheminPage;

$url = "http://" . $_SERVER["HTTP_HOST"];
$css_ref = '/assets/css/referenciel/affecter_ref_promo.css';
require_once __DIR__ . '/../../services/session.service.php';

$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? null;
$old = $_SESSION['old_inputs'] ?? [];
unset($_SESSION['errors'], $_SESSION['old_inputs'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affecter un référentiel</title>
    <link rel="stylesheet" href="<?= $url . $css_ref ?>">
    <style>
        /* styles existants + alertes toast */
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-weight: bold;
        }
        .alert-error {
            background-color: #ffdddd;
            color: #d8000c;
        }
        .alert-success {
            background-color: #ddffdd;
            color: #270;
        }
    </style>
</head>

<body>
<div class="modal">
    <h2>Affecter un référentiel</h2>

    <!-- Messages de succès ou erreur -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
    <?php endif; ?>

    <?php if ($promoActiveEnCours): ?>
        <form method="POST" action="index.php?page=affecter_ref_promo">
            <input type="hidden" name="action" value="affecter">
            <div class="form-group">
                <label for="referenciel_id">Sélectionnez un référentiel</label>
                <select name="referenciel_id" id="referenciel_id">
                    <option value="">Faites votre choix</option>
                    <?php foreach ($referentiels_non_affectes as $ref): ?>
                        <option value="<?= $ref['id'] ?>" <?= (!empty($old['referenciel_id']) && $old['referenciel_id'] == $ref['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ref['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="submit-btn">Affecter</button>
        </form>
    <?php else: ?>
        <p style="color:red;"><strong>Info :</strong> Vous ne pouvez affecter que si la promotion est active et en cours.</p>
    <?php endif; ?>

    <div class="form-group">
        <label>Référentiels déjà affectés</label>
        <div class="tags-container">
            <?php foreach ($referentiels_actifs as $ref): ?>
                <span>
                    <?= htmlspecialchars($ref['nom']) ?>
                    <?php if ($promoActiveEnCours && $ref['apprenants'] == 0): ?>
                        <form method="POST" action="index.php?page=affecter_ref_promo" style="display:inline;">
                            <input type="hidden" name="action" value="desaffecter">
                            <input type="hidden" name="referenciel_id" value="<?= $ref['id'] ?>">
                            <button class="remove" title="Désaffecter">×</button>
                        </form>
                    <?php elseif ($ref['apprenants'] > 0): ?>
                        <em style="font-size:11px; color: #ccc;">(apprenants inscrits)</em>
                    <?php endif; ?>
                </span>
            <?php endforeach; ?>
        </div>
    </div>affecter_ref_promo
</div>
</body>
</html>
