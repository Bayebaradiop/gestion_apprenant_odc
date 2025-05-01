<?php
declare(strict_types=1);
require_once __DIR__ . '/../enums/model.enum.php';
use App\Models\JSONMETHODE;

$model_tab=[
    // Convertit un tableau en JSON et l'enregistre dans un fichier
      JSONMETHODE::ARRAYTOJSON->value => function (array $tableau, string $cheminFichier): bool {
        $json = json_encode($tableau, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return file_put_contents($cheminFichier, $json) !== false;
    },
    // Lit un fichier JSON et retourne le tableau complet OU une partie via une clé
        JSONMETHODE::JSONTOARRAY->value => function (string $cheminFichier, ?string $cle = null): array {
        if (!file_exists($cheminFichier)) {
            return [];
        }
        $contenu = file_get_contents($cheminFichier);
        $tableau = json_decode($contenu, true);

        if (!is_array($tableau)) {
            return [];
        }
        // Retourner seulement la partie correspondant à la clé si demandée
        if ($cle !== null && array_key_exists($cle, $tableau)) {
            return $tableau[$cle];
        }
        return $tableau;
    }

];