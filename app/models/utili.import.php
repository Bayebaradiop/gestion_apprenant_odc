<?php
function ajouter_profil_apprenant(array &$apprenants): void {
    foreach ($apprenants as &$a) {
        $a['profil'] = 'Apprenant';
    }
}

function filtrer_apprenants_sur_referenciels(array $apprenants, array $refValides): array {
    return array_filter($apprenants, function ($a) use ($refValides) {
        return in_array((int)($a['referenciel'] ?? 0), $refValides, true);
    });
}


function recuperer_referentiels_valides(array $promotions): array {
    $aujourdhui = date('Y-m-d');
    $referentielsValides = [];

    foreach ($promotions as $promo) {
        if (
            $promo['statut'] === 'Active' &&
            isset($promo['dateDebut'], $promo['dateFin']) &&
            $promo['dateDebut'] <= $aujourdhui &&
            $promo['dateFin'] >= $aujourdhui &&
            is_array($promo['referenciels'] ?? [])
        ) {
            foreach ($promo['referenciels'] as $ref) {
                $refId = (int)$ref;
                if ($refId > 0) {
                    $referentielsValides[] = $refId;
                }
            }
        }
    }

    return array_unique($referentielsValides);
}


?>
