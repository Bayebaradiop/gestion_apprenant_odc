<!DOCTYPE html>
<html lang="fr">
<?php
require_once __DIR__ . '/../../enums/chemin_page.php';

use App\Enums\CheminPage;

$url = "http://" . $_SERVER["HTTP_HOST"];
$css_ref = '/assets/css/referenciel/add_referentiel.css';
require_once __DIR__ . '/../../services/session.service.php';

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old_inputs'] ?? [];
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= $url . $css_ref ?>">
</head>
<body>
    <div id="popup-add" class="modal">
        <div class="modal-content">
            <h2>Créer un nouveau référentiel</h2>

            <form method="POST" action="index.php?page=add_referentiel" enctype="multipart/form-data">
                <input type="hidden" name="action" value="ajouter">
                <!-- Zone d'upload photo -->
                <div class="upload-wrapper">
                    <label for="photo" class="upload-label <?= isset($errors['photo']) ? 'alert' : '' ?>">
                        <span class="upload-text">Cliquez pour ajouter une photo</span>
                        <input
                            type="file"
                            id="photo"
                            name="photo"
                            accept="image/*"
                            class="file-input">
                    </label>
                    <?php if (isset($errors['photo'])): ?>
                        <p class="error-message" style="color: red;"><?= $errors['photo'] ?></p>
                    <?php endif; ?>
                </div>


                <!-- Nom -->
                <div class="form-group">
                    <label for="nom">Nom*</label>
                    <input
                        type="text"
                        id="nom"
                        name="nom"
                        value="<?= htmlspecialchars($old['nom'] ?? '') ?>"
                        class="<?= isset($errors['nom']) || isset($errors['nom_unique']) ? 'alert' : '' ?>"
                        placeholder="Ex: Développement Web">
                    <?php if (isset($errors['nom'])): ?>
                        <p class="error-message" style="color: red;"><?= $errors['nom'] ?></p>
                    <?php elseif (isset($errors['nom_unique'])): ?>
                        <p class="error-message" style="color: red;"><?= $errors['nom_unique'] ?></p>
                    <?php endif; ?>
                </div>


                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="<?= isset($errors['description']) ? 'alert' : '' ?>"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                    <?php if (isset($errors['description'])): ?>
                        <p class="error-message" style="color: red;"><?= $errors['description'] ?></p>
                    <?php endif; ?>
                </div>


                <!-- Capacité et sessions -->
                <div class="form-group" style="display: flex; gap: 1rem;">
                    <div style="flex:1;">
                        <label for="capacite">Capacité*</label>
                        <input
                            type="text"
                            id="capacite"
                            name="capacite"
                            value="<?= htmlspecialchars($old['capacite'] ?? '') ?>"
                            class="<?= isset($errors['capacite']) ? 'alert' : '' ?>"
                            placeholder="Ex: 30">
                        <?php if (isset($errors['capacite'])): ?>
                            <p class="error-message"><?= $errors['capacite'] ?></p>
                        <?php endif; ?>
                    </div>

                    <div style="flex:1;">
                        <label for="sessions">Nombre de sessions*</label>
                        <select id="sessions" name="sessions" class="<?= isset($errors['sessions']) ? 'alert' : '' ?>">
                            <option value="">Choisir</option>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?= $i ?>" <?= ($old['sessions'] ?? '') == $i ? 'selected' : '' ?>>
                                    <?= $i ?> session<?= $i > 1 ? 's' : '' ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                        <?php if (isset($errors['sessions'])): ?>
                            <p class="error-message" style="color: red;"><?= $errors['sessions'] ?></p>
                        <?php endif; ?>
                    </div>

                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <a href="index.php?page=all_referenciel" class="cancel-btn">Annuler</a>
                    <button type="submit" class="submit-btn">Créer</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>