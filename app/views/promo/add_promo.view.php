<!DOCTYPE html>
<html lang="fr">
<?php
require_once __DIR__ . '/../../services/session.service.php';
demarrer_session();

$errors = recuperer_session_flash('errors', []);
$old = recuperer_session_flash('old_inputs', []);
?>
<head>
    <meta charset="UTF-8">
    <title>Créer une promotion</title>
    <link rel="stylesheet" href="/assets/css/promo/add_promo.css">
   
</head>
<body>
    <div class="container">
        <!-- Statistiques -->
        <div class="cards">
            <div class="card">
                <div class="icon"></div>
                <div class="info">
                    <div class="number">120</div>
                    <div class="label">Apprenants</div>
                </div>
            </div>
            <div class="card">
                <div class="icon"></div>
                <div class="info">
                    <div class="number">3</div>
                    <div class="label">Référentiels</div>
                </div>
            </div>
            <div class="card">
                <div class="icon"></div>
                <div class="info">
                    <div class="number">5</div>
                    <div class="label">Stagiaire</div>
                </div>
            </div>
            <div class="card">
                <div class="icon"></div>
                <div class="info">
                    <div class="number">13</div>
                    <div class="label">Permenant</div>
                </div>
            </div>
        </div>
      
        </div>

        <!-- Formulaire -->
        <div class="form-container">
            <h2>Créer une nouvelle promotion</h2>
            <p class="subtitle">Remplissez les informations pour enregistrer une nouvelle promo.</p>

            <form class="modal-form" method="POST" action="index.php?page=add_promo" enctype="multipart/form-data">
                <!-- Nom promo -->
                <label>Nom de la promotion
                    <input type="text" name="nom_promo"
                           value="<?= htmlspecialchars($old['nom_promo'] ?? '') ?>"
                           class="<?= !empty($errors['nom_promo']) ? 'alert' : '' ?>">
                    <?php if (!empty($errors['nom_promo'])): ?>
                        <p class="error-message"><?= htmlspecialchars($errors['nom_promo']) ?></p>
                    <?php endif; ?>
                </label>

                <!-- Dates -->
                <div class="date-fields">
                    <label>Date de début
                        <input type="text" name="date_debut" placeholder="YYYY-MM-DD"
                               value="<?= htmlspecialchars($old['date_debut'] ?? '') ?>"
                               class="<?= !empty($errors['date_debut']) ? 'alert' : '' ?>">
                        <?php if (!empty($errors['date_debut'])): ?>
                            <p class="error-message"><?= htmlspecialchars($errors['date_debut']) ?></p>
                        <?php endif; ?>
                    </label>

                    <label>Date de fin
                        <input type="text" name="date_fin" placeholder="YYYY-MM-DD"
                               value="<?= htmlspecialchars($old['date_fin'] ?? '') ?>"
                               class="<?= !empty($errors['date_fin']) ? 'alert' : '' ?>">
                        <?php if (!empty($errors['date_fin'])): ?>
                            <p class="error-message"><?= htmlspecialchars($errors['date_fin']) ?></p>
                        <?php endif; ?>
                    </label>
                </div>

                <!-- Photo -->
                <label>Photo
                    <input type="file" name="photo" class="<?= !empty($errors['photo']) ? 'alert' : '' ?>">
                    <?php if (!empty($errors['photo'])): ?>
                        <p class="error-message"><?= htmlspecialchars($errors['photo']) ?></p>
                    <?php endif; ?>
                </label>

                <!-- Référentiels -->
                <label>Référentiels</label>
                <div class="checkbox-grid">
                    <?php foreach ($referentiels as $ref): ?>
                        <div class="checkbox-item">
                            <input type="checkbox" name="referenciels[]"
                                   id="ref-<?= $ref['id'] ?>"
                                   value="<?= $ref['id'] ?>"
                                   <?= (!empty($old['referenciels']) && in_array($ref['id'], $old['referenciels'])) ? 'checked' : '' ?>>
                            <label for="ref-<?= $ref['id'] ?>"><?= htmlspecialchars($ref['nom']) ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (!empty($errors['referenciels'])): ?>
                    <p class="error-message"><?= htmlspecialchars($errors['referenciels']) ?></p>
                <?php endif; ?>

               

                <!-- Boutons -->
                <div class="form-actions">
                    <a href="index.php?page=liste_promo" class="cancel-btn">Annuler</a>
                    <button type="submit" class="submit-btn">Créer la promotion</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
