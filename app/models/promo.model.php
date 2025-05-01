<?php
global $model_tab;
require_once __DIR__ . '/../enums/model.enum.php';
require_once __DIR__ . '/../enums/chemin_page.php';

use App\Enums\CheminPage;
use App\Models\JSONMETHODE;
use App\Models\PROMOMETHODE;

$json = CheminPage::DATA_JSON->value;
$jsontoarray = $model_tab[JSONMETHODE::JSONTOARRAY->value];

global $promos;

$promos = [
    PROMOMETHODE::GET_ALL->value => function (?string $nomRecherche = null, ?string $statut = null) use ($jsontoarray, $json) {
        $promotions = $jsontoarray($json, "promotions");

        return array_filter($promotions, function ($promo) use ($nomRecherche, $statut) {
            $matchNom = !$nomRecherche || str_contains(strtolower($promo['nom']), strtolower($nomRecherche));
            $matchStatut = !$statut || $statut === "tous" || strtolower($promo["statut"]) === strtolower($statut);
            return $matchNom && $matchStatut;
        });
    },

    PROMOMETHODE::AJOUTER_PROMO->value => function (array $nouvellePromo, string $chemin): bool {
        global $model_tab;
        $data = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);

        if (!isset($data['promotions'])) {
            $data['promotions'] = [];
        }

        $data['promotions'][] = $nouvellePromo;

        return $model_tab[JSONMETHODE::ARRAYTOJSON->value]($data, $chemin);
    },

    PROMOMETHODE::ACTIVER_PROMO->value => function (int $idPromo, string $chemin): bool {
        global $model_tab;
    
        $data = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);
    
        if (!isset($data['promotions'])) {
            return false;
        }
    
        // ➔ array_map pour mettre à jour les statuts
        $data['promotions'] = array_map(function ($promo) use ($idPromo) {
            $promo['statut'] = ($promo['id'] === $idPromo) ? 'Active' : 'Inactive';
            return $promo;
        }, $data['promotions']);
    
        return $model_tab[JSONMETHODE::ARRAYTOJSON->value]($data, $chemin);
    },

    PROMOMETHODE::GET_ACTIVE_PROMO->value => function () use ($jsontoarray, $json) {
        $promotions = $jsontoarray($json, "promotions");
        if (!$promotions) return null;

        foreach ($promotions as $promo) {
            if ($promo['statut'] === 'Active') {
                $dateActuelle = new DateTime();
                $dateDebut = new DateTime($promo['date_debut']);
                $dateFin = new DateTime($promo['date_fin']);

                // Vérifier si la promotion est en cours
                if ($dateActuelle >= $dateDebut && $dateActuelle <= $dateFin) {
                    return $promo;
                }
            }
        }
        return null;
    },

    PROMOMETHODE::GET_BY_ID->value => function (int $idPromo) use ($jsontoarray, $json) {
        $promotions = $jsontoarray($json, "promotions");
        if (!$promotions) return null;

        foreach ($promotions as $promo) {
            if ($promo['id'] === $idPromo) {
                return $promo;
            }
        }
        return null;
    }
];
