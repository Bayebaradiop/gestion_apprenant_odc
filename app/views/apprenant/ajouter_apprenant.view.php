<?php
require_once __DIR__ . '/../../services/session.service.php';
demarrer_session();

$errors = recuperer_session_flash('errors', []);
$old = recuperer_session_flash('old_inputs', []);

require_once __DIR__ . '/../../enums/vers_page.php';
use App\Enums\vers_page;
$url = "http://" . $_SERVER["HTTP_HOST"];
$CSS_ajouter = vers_page::CSS_ajouter->value;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajout Apprenant</title>



  <link rel="stylesheet" href="<?= $url . $CSS_ajouter ?>">

</head>

<body>

<div class="containerajour_apprenant">

  <h1>Ajout d'un Apprenant</h1>

  <form action="index.php?page=ajouter_apprenant" method="POST" enctype="multipart/form-data" class="form-ajout">

    <!-- Section Apprenant -->
    <section class="section">
      <div class="section-header">
        <h2>Informations de l'Apprenant</h2>
        <span class="edit-icon">✎</span>
      </div>

      <div class="grid">

        <!-- Matricule -->
        <div class="form-group">
          <label>Matricule</label>
          <input type="text" name="matricule" value="<?= htmlspecialchars($matricule ?? '') ?>" readonly class="<?= !empty($errors['matricule']) ? 'alert' : '' ?>">
          <?php if (!empty($errors['matricule'])): ?><p class="error-message"><?= htmlspecialchars($errors['matricule']) ?></p><?php endif; ?>
        </div>

        <!-- Nom complet -->
        <div class="form-group">
          <label>Nom Complet</label>
          <input type="text" name="nom_complet" placeholder="Ex: Seydina Diop" value="<?= htmlspecialchars($old['nom_complet'] ?? '') ?>" class="<?= !empty($errors['nom_complet']) ? 'alert' : '' ?>">
          <?php if (!empty($errors['nom_complet'])): ?><p class="error-message"><?= htmlspecialchars($errors['nom_complet']) ?></p><?php endif; ?>
        </div>

        <!-- Date de naissance -->
        <div class="form-group">
          <label>Date de naissance</label>
          <input type="text" name="date_naissance" placeholder="Ex: 2000-01-01" value="<?= htmlspecialchars($old['date_naissance'] ?? '') ?>" class="<?= !empty($errors['date_naissance']) ? 'alert' : '' ?>">
          <?php if (!empty($errors['date_naissance'])): ?><p class="error-message"><?= htmlspecialchars($errors['date_naissance']) ?></p><?php endif; ?>
        </div>

        <!-- Lieu de naissance -->
        <div class="form-group">
          <label>Lieu de naissance</label>
          <input type="text" name="lieu_naissance" placeholder="Ex: Dakar" value="<?= htmlspecialchars($old['lieu_naissance'] ?? '') ?>" class="<?= !empty($errors['lieu_naissance']) ? 'alert' : '' ?>">
          <?php if (!empty($errors['lieu_naissance'])): ?><p class="error-message"><?= htmlspecialchars($errors['lieu_naissance']) ?></p><?php endif; ?>
        </div>

        <!-- Adresse -->
        <div class="form-group">
          <label>Adresse</label>
          <input type="text" name="adresse" placeholder="Ex: Liberté 6" value="<?= htmlspecialchars($old['adresse'] ?? '') ?>" class="<?= !empty($errors['adresse']) ? 'alert' : '' ?>">
          <?php if (!empty($errors['adresse'])): ?><p class="error-message"><?= htmlspecialchars($errors['adresse']) ?></p><?php endif; ?>
        </div>

        <!-- Email (Login) -->
        <div class="form-group">
          <label>Email</label>
          <input type="text" name="login" placeholder="Ex: email@exemple.com" value="<?= htmlspecialchars($old['login'] ?? '') ?>" class="<?= !empty($errors['login']) ? 'alert' : '' ?>">
          <?php if (!empty($errors['login'])): ?><p class="error-message"><?= htmlspecialchars($errors['login']) ?></p><?php endif; ?>
        </div>

        <!-- Téléphone -->
        <div class="form-group">
          <label>Téléphone</label>
          <input type="text" name="telephone" placeholder="Ex: 770000000" value="<?= htmlspecialchars($old['telephone'] ?? '') ?>" class="<?= !empty($errors['telephone']) ? 'alert' : '' ?>">
          <?php if (!empty($errors['telephone'])): ?><p class="error-message"><?= htmlspecialchars($errors['telephone']) ?></p><?php endif; ?>
        </div>

        <!-- Référentiel -->
        <div class="form-group">
          <label>Référentiel</label>
          <select name="referenciel" class="<?= !empty($errors['referenciel']) ? 'alert' : '' ?>">
            <option value="">-- Sélectionner un référentiel --</option>
            <?php foreach ($referenciels as $ref): ?>
              <option value="<?= $ref['id'] ?>" <?= (!empty($old['referenciel']) && $old['referenciel'] == $ref['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($ref['nom']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php if (!empty($errors['referenciel'])): ?><p class="error-message"><?= htmlspecialchars($errors['referenciel']) ?></p><?php endif; ?>
        </div>

        <!-- Upload Photo -->
        <div class="form-group file-upload">
          <label>Photo</label>
          <div class="upload-box">
            <input type="file" name="document">
            <p>📄 Ajouter une image</p>
          </div>
          <?php if (!empty($errors['photo'])): ?><p class="error-message"><?= htmlspecialchars($errors['photo']) ?></p><?php endif; ?>
        </div>

      </div>
    </section>

    <!-- Section Tuteur -->
    <section class="section">
      <div class="section-header">
        <h2>Informations du Tuteur</h2>
        <span class="edit-icon">✎</span>
      </div>

      <div class="grid">

        <!-- Nom du Tuteur -->
        <div class="form-group">
          <label>Nom du Tuteur</label>
          <input type="text" name="tuteur_nom" placeholder="Ex: Nathan Allen" value="<?= htmlspecialchars($old['tuteur_nom'] ?? '') ?>" class="<?= !empty($errors['tuteur_nom']) ? 'alert' : '' ?>">
          <?php if (!empty($errors['tuteur_nom'])): ?><p class="error-message"><?= htmlspecialchars($errors['tuteur_nom']) ?></p><?php endif; ?>
        </div>

        <!-- Lien de Parenté -->
        <div class="form-group">
          <label>Lien de Parenté</label>
          <input type="text" name="lien_parente" placeholder="Ex: Mère" value="<?= htmlspecialchars($old['lien_parente'] ?? '') ?>" class="<?= !empty($errors['lien_parente']) ? 'alert' : '' ?>">
          <?php if (!empty($errors['lien_parente'])): ?><p class="error-message"><?= htmlspecialchars($errors['lien_parente']) ?></p><?php endif; ?>
        </div>

        <!-- Adresse du Tuteur -->
        <div class="form-group">
          <label>Adresse du Tuteur</label>
          <input type="text" name="tuteur_adresse" placeholder="Ex: Dakar" value="<?= htmlspecialchars($old['tuteur_adresse'] ?? '') ?>" class="<?= !empty($errors['tuteur_adresse']) ? 'alert' : '' ?>">
          <?php if (!empty($errors['tuteur_adresse'])): ?><p class="error-message"><?= htmlspecialchars($errors['tuteur_adresse']) ?></p><?php endif; ?>
        </div>

        <!-- Téléphone du Tuteur -->
        <div class="form-group">
          <label>Téléphone du Tuteur</label>
          <input type="text" name="tuteur_telephone" placeholder="Ex: 770000001" value="<?= htmlspecialchars($old['tuteur_telephone'] ?? '') ?>" class="<?= !empty($errors['tuteur_telephone']) ? 'alert' : '' ?>">
          <?php if (!empty($errors['tuteur_telephone'])): ?><p class="error-message"><?= htmlspecialchars($errors['tuteur_telephone']) ?></p><?php endif; ?>
        </div>

      </div>
    </section>

    <div class="buttons">
      <a href="index.php?page=liste_apprenant" class="cancel">Annuler</a>
      <button type="submit" class="submit">Enregistrer</button>
    </div>

  </form>

</div>

</body>
</html>
