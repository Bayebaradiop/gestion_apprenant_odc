<?php
declare(strict_types=1);
require_once __DIR__ . '/../enums/validator.enum.php';
require_once __DIR__ . '/../enums/erreur.enum.php';
require_once __DIR__ . '/../enums/model.enum.php';
require_once __DIR__ . '/../enums/chemin_page.php';
require_once __DIR__ . '/../models/model.php';

use App\ENUM\VALIDATOR\VALIDATORMETHODE;
use App\ENUM\ERREUR\ErreurEnum;
use App\Enums\CheminPage;
use App\Models\JSONMETHODE;
use App\Models\APPMETHODE;
use App\Models\AUTHMETHODE;
use App\Models\VALIDATORMETHODE as VALIDATORMETHODE_MODEL;
global $validator;

$validator = [

    // Email valide
    VALIDATORMETHODE::EMAIL->value => function (string $email): ?string {
        if (empty($email)) return ErreurEnum::LOGIN_REQUIRED->value;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return ErreurEnum::LOGIN_EMAIL->value;
        return null;
    },

    // Mot de passe
    VALIDATORMETHODE::PASSWORD->value => function (string $password): ?string {
        if (empty($password)) return ErreurEnum::PASSWORD_REQUIRED->value;
        if (strlen($password) < 6) return 'password.invalid';
        return null;
    },

    // Validation combinée user
    VALIDATORMETHODE::USER->value => function (string $email, string $password) use (&$validator): array {
        $erreurs = [];
        $email_error = $validator[VALIDATORMETHODE::EMAIL->value]($email);
        if ($email_error) $erreurs['login'] = $email_error;
        $password_error = $validator[VALIDATORMETHODE::PASSWORD->value]($password);
        if ($password_error) $erreurs['password'] = $password_error;
        return $erreurs;
    },

    // Promo simple
    VALIDATORMETHODE::PROMO->value => fn(string $promo_name): ?string =>
        empty($promo_name) ? ErreurEnum::PROMO_NAME_REQUIRED->value : null,

    VALIDATORMETHODE::PROMO_DATE->value => fn(string $date): ?string =>
        empty($date) ? ErreurEnum::PROMO_DATE_REQUIRED->value : null,

    VALIDATORMETHODE::PROMO_date_valide->value => function (string $dateDebut, string $dateFin): ?string {
        $startDate = DateTime::createFromFormat('d-m-y', $dateDebut);
        $endDate = DateTime::createFromFormat('d-m-y', $dateFin);

        if (!$startDate || !$endDate) {
            return ErreurEnum::PROMO_DATE_NORME->value;
        }

        if ($startDate > $endDate) {
            return ErreurEnum::PROMO_DATE_INFERIEUR->value;
        }

        return null;
    },

    VALIDATORMETHODE::PROMO_NOM_UNIQUE->value => function (string $nom, array $promotions): ?string {
        $nomsExistants = array_map(fn($promo) => strtolower(trim($promo['nom'])), $promotions);
        return in_array(strtolower(trim($nom)), $nomsExistants) ? ErreurEnum::PROMO_NOM_EXISTS->value : null;
    },

    VALIDATORMETHODE::VALID_GENERAL->value => function(array $data): array {
        $errors = [];

        // Nom obligatoire
        if (empty($data['nom_promo'])) {
            $errors['nom_promo'] = 'Le nom de la promotion est obligatoire.';
        } else {
            // Unicité du nom
            $chemin = \App\Enums\CheminPage::DATA_JSON->value;
            if (file_exists($chemin)) {
                $contenu = json_decode(file_get_contents($chemin), true);
                $promos = $contenu['promotions'] ?? [];

                $nom_saisi = strtolower(trim($data['nom_promo']));
                $doublon = array_filter($promos, fn($p) => strtolower($p['nom']) === $nom_saisi);

                if (!empty($doublon)) {
                    $errors['nom_promo'] = 'Ce nom de promotion existe déjà.';
                }
            }
        }

        // Dates obligatoires + format
        if (empty($data['date_debut'])) {
            $errors['date_debut'] = 'La date de début est obligatoire.';
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['date_debut'])) {
            $errors['date_debut'] = 'doit être au format YYYY-MM-DD.';
        }

        if (empty($data['date_fin'])) {
            $errors['date_fin'] = 'La date de fin est obligatoire.';
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['date_fin'])) {
            $errors['date_fin'] = 'doit être au format YYYY-MM-DD.';
        }

        // Comparaison des dates
        if (!empty($data['date_debut']) && !empty($data['date_fin'])) {
            $dateDebut = strtotime($data['date_debut']);
            $dateFin = strtotime($data['date_fin']);
            if ($dateDebut > $dateFin) {
                $errors['date_fin'] = 'La date de fin doit être postérieure à la date de début.';
            }
        }

        // Photo
        if (!empty($data['photo']) && $data['photo']['error'] !== UPLOAD_ERR_OK) {
            $errors['photo'] = 'Erreur lors du téléchargement de la photo.';
        }

        // Référentiels
        if (empty($data['referenciels'])) {
            $errors['referenciels'] = 'Veuillez sélectionner au moins un référentiel.';
        }

        // Apprenants
        if (!empty($data['nbr_apprenants']) && (!is_numeric($data['nbr_apprenants']) || $data['nbr_apprenants'] <= 0)) {
            $errors['nbr_apprenants'] = 'Le nombre d\'apprenants doit être un nombre positif.';
        }

        return $errors;
    },

    // Validation des référentiels
    VALIDATORMETHODE::REF->value => function(array $data, array $referentiels): array {
        $errors = [];

        if (empty(trim($data['nom'] ?? ''))) {
            $errors['nom'] = 'Le nom du référentiel est requis.';
        }

        $nomsExistants = array_map(fn($ref) => strtolower(trim($ref['nom'])), $referentiels);
        if (in_array(strtolower(trim($data['nom'] ?? '')), $nomsExistants)) {
            $errors['nom_unique'] = 'Le nom du référentiel existe déjà.';
        }

        if (empty(trim($data['description'] ?? ''))) {
            $errors['description'] = 'La description est requise.';
        }

        if (empty($data['capacite']) || !is_numeric($data['capacite'])) {
            $errors['capacite'] = 'La capacité doit être un nombre valide.';
        }

        if (empty($data['sessions'])) {
            $errors['sessions'] = 'Le nombre de sessions est requis.';
        }

        if (empty($data['photo']) || $data['photo']['error'] !== UPLOAD_ERR_OK) {
            $errors['photo'] = 'La photo est obligatoire.';
        } else {
            $validExtensions = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($data['photo']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $validExtensions)) {
                $errors['photo'] = 'La photo doit être au format JPG ou PNG.';
            }
        }

        return $errors;
    },

    // Validation des apprenants
    VALIDATORMETHODE::APPRENANT->value => function (array $data): array {
        $errors = [];

        // Nom complet obligatoire
        if (empty(trim($data['nom_complet'] ?? ''))) {
            $errors['nom_complet'] = ErreurEnum::APPRENANT_NOM_REQUIRED->value;
        }

        // // Login obligatoire et valide
        // if (empty(trim($data['login'] ?? ''))) {
        //     $errors['login'] = ErreurEnum::APPRENANT_EMAIL_REQUIRED->value;
        // } else {
        //     $login = trim($data['login']);
        //     if (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
        //         $errors['login'] = ErreurEnum::APPRENANT_EMAIL_INVALID->value;
        //     }
        // }




        $login = trim($data['login'] ?? '');

        if ($login === '') {
            $errors['login'] = ErreurEnum::APPRENANT_EMAIL_REQUIRED->value;
        } elseif (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $errors['login'] = ErreurEnum::APPRENANT_EMAIL_INVALID->value;
        } else {
            // Vérification de l'unicité
            $chemin = CheminPage::DATA_JSON->value;
            global $model_tab;
            $utilisateurs = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin, 'utilisateurs');
        
            $loginExiste = array_filter($utilisateurs, fn($u) => isset($u['login']) && strtolower($u['login']) === strtolower($login));
        
            if (!empty($loginExiste)) {
                $errors['login'] = ErreurEnum::APPRENANT_EMAIL_UNIQUE->value;
            }
        }
        







        // Téléphone obligatoire, valide et unique
        if (empty(trim($data['telephone'] ?? ''))) {
            $errors['telephone'] = ErreurEnum::APPRENANT_TELEPHONE_REQUIRED->value;
        } elseif (!preg_match('/^\d{9}$/', $data['telephone'])) {
            $errors['telephone'] = ErreurEnum::APPRENANT_TELEPHONE_INVALID->value;
        } else {
            $chemin = \App\Enums\CheminPage::DATA_JSON->value;
            if (file_exists($chemin)) {
                $contenu = json_decode(file_get_contents($chemin), true);
                $utilisateurs = $contenu['utilisateurs'] ?? [];

                $telephoneSaisi = trim($data['telephone']);
                $doublon = array_filter($utilisateurs, fn($u) => ($u['telephone'] ?? '') === $telephoneSaisi);

                if (!empty($doublon)) {
                    $errors['telephone'] = ErreurEnum::APPRENANT_TELEPHONE_EXISTS->value;
                }
            }
        }

        // Matricule obligatoire et unique
        // if (empty(trim($data['matricule'] ?? ''))) {
        //     $errors['matricule'] = ErreurEnum::APPRENANT_MATRICULE_REQUIRED->value;
        // } else {
        //     $chemin = \App\Enums\CheminPage::DATA_JSON->value;
        //     if (file_exists($chemin)) {
        //         $contenu = json_decode(file_get_contents($chemin), true);
        //         $utilisateurs = $contenu['utilisateurs'] ?? [];

        //         $matriculeSaisi = strtolower(trim($data['matricule']));
        //         $doublon = array_filter($utilisateurs, fn($u) => strtolower($u['matricule'] ?? '') === $matriculeSaisi);

        //         if (!empty($doublon)) {
        //             $errors['matricule'] = ErreurEnum::APPRENANT_MATRICULE_EXISTS->value;
        //         }
        //     }
        // }


        
        // Date de naissance obligatoire et format YYYY-MM-DD
        if (empty(trim($data['date_naissance'] ?? ''))) {
            $errors['date_naissance'] = ErreurEnum::APPRENANT_DATE_NAISSANCE_REQUIRED->value;
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['date_naissance'])) {
            $errors['date_naissance'] = ErreurEnum::APPRENANT_DATE_NAISSANCE_INVALID->value;
        }

        // Lieu de naissance obligatoire
        if (empty(trim($data['lieu_naissance'] ?? ''))) {
            $errors['lieu_naissance'] = ErreurEnum::APPRENANT_LIEU_NAISSANCE_REQUIRED->value;
        }

        // Adresse obligatoire
        if (empty(trim($data['adresse'] ?? ''))) {
            $errors['adresse'] = ErreurEnum::APPRENANT_ADRESSE_REQUIRED->value;
        }

        // Référentiel obligatoire
        if (empty($data['referenciel'])) {
            $errors['referenciel'] = ErreurEnum::APPRENANT_REFERENTIEL_REQUIRED->value;
        }

        // Tuteur - Nom obligatoire
        if (empty(trim($data['tuteur_nom'] ?? ''))) {
            $errors['tuteur_nom'] = ErreurEnum::APPRENANT_TUTEUR_NOM_REQUIRED->value;
        }

        // Tuteur - Lien de parenté obligatoire
        if (empty(trim($data['lien_parente'] ?? ''))) {
            $errors['lien_parente'] = ErreurEnum::APPRENANT_LIEN_PARENT_REQUIRED->value;
        }

        // Tuteur - Adresse obligatoire
        if (empty(trim($data['tuteur_adresse'] ?? ''))) {
            $errors['tuteur_adresse'] = ErreurEnum::APPRENANT_TUTEUR_ADRESSE_REQUIRED->value;
        }

        // Tuteur - Téléphone obligatoire et format 9 chiffres
        if (empty(trim($data['tuteur_telephone'] ?? ''))) {
            $errors['tuteur_telephone'] = ErreurEnum::APPRENANT_TUTEUR_TELEPHONE_REQUIRED->value;
        } elseif (!preg_match('/^\d{9}$/', $data['tuteur_telephone'])) {
            $errors['tuteur_telephone'] = ErreurEnum::APPRENANT_TUTEUR_TELEPHONE_INVALID->value;
        }

        return $errors;
    },









    
];
