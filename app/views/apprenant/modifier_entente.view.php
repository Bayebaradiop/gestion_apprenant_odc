<?php
require_once __DIR__ . '/../../enums/vers_page.php';
use App\Enums\vers_page;

$url = "http://" . $_SERVER["HTTP_HOST"];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Apprenant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primary: #009688;
            --primary-dark: #00796b;
            --secondary: #ff9800;
            --error: #e53935;
            --bg: #f0f5f9;
            --text: #333;
            --white: #fff;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .containerm {
            max-width: 850px;
            margin: 40px auto;
            background: var(--white);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--primary);
        }

        form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input, select {
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
            transition: 0.3s;
        }

        input:focus, select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0,150,136,0.1);
        }

        .input-error {
            border-color: var(--error);
            background: #ffe5e5;
        }

        .error-text {
            color: var(--error);
            font-size: 13px;
            margin-top: 4px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .buttons {
            grid-column: 1 / -1;
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-secondary {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-secondary:hover {
            background: rgba(0,150,136,0.1);
        }

        @media screen and (max-width: 600px) {
            .buttons {
                flex-direction: column;
                gap: 10px;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="containerm">
    <h2>Modification Apprenant en Attente</h2>
    <form method="POST" action="index.php?page=traiter_modification_en_attente">
        <input type="hidden" name="matricule" value="<?= htmlspecialchars($apprenant['matricule']) ?>">

        <?php
        function champ(string $name, string $label, array $apprenant, array $errors, string $type = 'text', bool $full = false) {
            $value = htmlspecialchars($apprenant[$name] ?? '');
            $hasError = isset($errors[$name]) ? 'input-error' : '';
            $errorText = isset($errors[$name]) ? "<div class='error-text'>{$errors[$name]}</div>" : '';
            $class = $full ? 'form-group full-width' : 'form-group';

            echo "<div class='$class'>
                    <label for='$name'>$label</label>
                    <input type='$type' name='$name' matricule='$name' value='$value' class='$hasError'>
                    $errorText
                  </div>";
        }

        champ('nom_complet', 'Nom Complet', $apprenant, $errors);
        champ('login', 'Email', $apprenant, $errors, 'email');
        champ('date_naissance', 'Date de Naissance', $apprenant, $errors, 'date');
        champ('lieu_naissance', 'Lieu de Naissance', $apprenant, $errors);
        champ('adresse', 'Adresse', $apprenant, $errors, 'text', true);
        champ('telephone', 'Téléphone', $apprenant, $errors);

        // Référentiel
        echo "<div class='form-group'>
                <label for='referenciel'>Référentiel</label>
                <select name='referenciel' id='referenciel' class='" . (isset($errors['referenciel']) ? 'input-error' : '') . "'>
                    <option value=''>-- Sélectionner --</option>";
        foreach ($referenciels as $ref) {
            $selected = ($apprenant['referenciel'] ?? '') == $ref['id'] ? 'selected' : '';
            echo "<option value='{$ref['id']}' $selected>" . htmlspecialchars($ref['nom']) . "</option>";
        }
        echo "  </select>";
        if (isset($errors['referenciel'])) {
            echo "<div class='error-text'>{$errors['referenciel']}</div>";
        }
        echo "</div>";

        champ('tuteur_nom', 'Nom du Tuteur', $apprenant, $errors);
        champ('lien_parente', 'Lien de Parenté', $apprenant, $errors);
        champ('tuteur_adresse', 'Adresse Tuteur', $apprenant, $errors, 'text', true);
        champ('tuteur_telephone', 'Téléphone Tuteur', $apprenant, $errors);
        ?>

        <div class="buttons">
            <button type="submit" class="btn btn-primary">Valider</button>
        </div>
    </form>
</div>
</body>
</html>
