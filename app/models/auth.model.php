<?php
declare(strict_types=1);
require_once __DIR__ . '/../enums/chemin_page.php';
require_once __DIR__ . '/../enums/model.enum.php';

use App\Enums\CheminPage;
use App\Models\JSONMETHODE;
use App\Models\AUTHMETHODE;

global $auth_model;

$auth_model = [

    // Récupère l'utilisateur uniquement par login (la vérif du mot de passe est faite dans le contrôleur avec password_verify)
    AUTHMETHODE::LOGIN->value => function (string $login, string $unused, string $chemin): ?array {
        global $model_tab;

        $utilisateurs = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin, 'utilisateurs');

        foreach ($utilisateurs as $user) {
            if (isset($user['login']) && $user['login'] === $login) {
                return $user;
            }
        }

        return null;
    },

    // Réinitialise le mot de passe (déjà haché en amont), et met "changer" à true
    AUTHMETHODE::RESET_PASSWORD->value => function (string $login, string $newPasswordHashed, string $chemin): bool {
        global $model_tab;

        $data = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);
        $utilisateurs = $data['utilisateurs'] ?? [];
        $modifie = false;

        $utilisateurs = array_map(function ($u) use ($login, $newPasswordHashed, &$modifie) {
            if (isset($u['login']) && $u['login'] === $login) {
                $u['password'] = $newPasswordHashed;
                $u['changer'] = true;
                $modifie = true;
            }
            return $u;
        }, $utilisateurs);

        if (!$modifie) return false;

        $data['utilisateurs'] = $utilisateurs;
        return $model_tab[JSONMETHODE::ARRAYTOJSON->value]($data, $chemin);
    }

];
