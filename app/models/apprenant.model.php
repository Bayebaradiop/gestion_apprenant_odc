<?php
global $model_tab;
require_once __DIR__ . '/../enums/model.enum.php';
require_once __DIR__ . '/../enums/chemin_page.php';
require_once __DIR__ . '/utili.import.php';

use App\Enums\CheminPage;
use App\Models\JSONMETHODE;
use App\Models\APPMETHODE;

$json = CheminPage::DATA_JSON->value;
$jsontoarray = $model_tab[JSONMETHODE::JSONTOARRAY->value];

global $apprenants;

$apprenants = [
    // Liste tous les apprenants (utilisateurs avec le profil "Apprenant")
    APPMETHODE::GET_ALL->value => function (?string $nomRecherche = null, ?int $referencielId = null) use ($jsontoarray, $json) {
        $utilisateurs = $jsontoarray($json, "utilisateurs");

        return array_filter($utilisateurs, function ($utilisateur) use ($nomRecherche, $referencielId) {
            $isApprenant =( $utilisateur['profil'] === 'Apprenant'&& ($utilisateur['statut'] === 'Retenu' || $utilisateur['statut'] === 'Remplacer'));
            $matchNom = !$nomRecherche || str_contains(strtolower($utilisateur['nom_complet']), strtolower($nomRecherche));
            $matchReferenciel = !$referencielId || (isset($utilisateur['referenciel']) && $utilisateur['referenciel'] === $referencielId);
            return $isApprenant && $matchNom && $matchReferenciel;
        });
    },

    // Ajoute un apprenant (utilisateur avec le profil "Apprenant")
    APPMETHODE::AJOUTER->value => function (array $nouvelApprenant, string $chemin): bool {
        global $model_tab;
        $data = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);

        if (!isset($data['utilisateurs'])) {
            $data['utilisateurs'] = [];
        }

        // Ajouter le profil "Apprenant" au nouvel utilisateur
        $nouvelApprenant['profil'] = 'Apprenant';
        $data['utilisateurs'][] = $nouvelApprenant;

        return $model_tab[JSONMETHODE::ARRAYTOJSON->value]($data, $chemin);
    },

    // Importe plusieurs apprenants à partir d'un tableau
    // APPMETHODE::IMPORTER->value => function (array $nouveauxApprenants, string $chemin): bool {
    //     global $model_tab;
    //     $data = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);
    
    //     if (!isset($data['utilisateurs'])) {
    //         $data['utilisateurs'] = [];
    //     }
    
    //     $promotions = $data['promotions'] ?? [];
    //     $aujourdHui = date('Y-m-d');
    //     $referentielsValides = [];
    
    //     foreach ($promotions as $promo) {
    //         if (
    //             $promo['statut'] === 'Active' &&
    //             isset($promo['dateDebut'], $promo['dateFin']) &&
    //             $promo['dateDebut'] <= $aujourdHui &&
    //             $promo['dateFin'] >= $aujourdHui &&
    //             isset($promo['referenciels']) && is_array($promo['referenciels'])
    //         ) {
    //             foreach ($promo['referenciels'] as $ref) {
    //                 $ref = (int)$ref;
    //                 if ($ref > 0) {
    //                     $referentielsValides[] = $ref;
    //                 }
    //             }
    //         }
    //     }
    
    //     $referentielsValides = array_unique($referentielsValides);
    
    //     $nouveauxFiltrés = array_filter($nouveauxApprenants, function ($apprenant) use ($referentielsValides) {
    //         return in_array((int)($apprenant['referenciel'] ?? 0), $referentielsValides, true);
    //     });
    
    //     foreach ($nouveauxFiltrés as &$apprenant) {
    //         $apprenant['profil'] = 'Apprenant';
    //     }
    
    //     $data['utilisateurs'] = array_merge($data['utilisateurs'], $nouveauxFiltrés);
    
    //     return $model_tab[JSONMETHODE::ARRAYTOJSON->value]($data, $chemin);
    // }




    APPMETHODE::IMPORTER->value => function (array $nouveauxApprenants, string $chemin): bool {
        global $model_tab;
    
        $data = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);
        if (!isset($data['utilisateurs'])) {
            $data['utilisateurs'] = [];
        }
    
        $promotions = $data['promotions'] ?? [];
        $refValides = recuperer_referentiels_valides($promotions);
        $apprenantsFiltres = filtrer_apprenants_sur_referenciels($nouveauxApprenants, $refValides);
        ajouter_profil_apprenant($apprenantsFiltres);
    
        $data['utilisateurs'] = array_merge($data['utilisateurs'], $apprenantsFiltres);
    
        return $model_tab[JSONMETHODE::ARRAYTOJSON->value]($data, $chemin);
    }
    
    
];






?>