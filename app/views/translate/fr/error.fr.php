<?php
require_once __DIR__ . '/../../../enums/erreur.enum.php';

use App\ENUM\ERREUR\ErreurEnum;

$error = [
    // Erreurs liées à l'authentification
    ErreurEnum::LOGIN_REQUIRED->value => "L'email est requis.",
    ErreurEnum::LOGIN_EMAIL->value => "L'email doit être une adresse email valide.",
    ErreurEnum::PASSWORD_REQUIRED->value => 'Le mot de passe est requis.',
    ErreurEnum::PASSWORD_INVALID->value => 'Le mot de passe doit contenir au moins 6 caractères.',
    ErreurEnum::LOGIN_INCORRECT->value => "L'email ou mot de passe incorrect.",

    // Erreurs liées aux promotions
    ErreurEnum::PROMO_ID_REQUIRED->value => "L'identifiant de la promotion est requis.",
    ErreurEnum::PROMO_NAME_REQUIRED->value => "Le nom de la promotion est requis.",
    ErreurEnum::PROMO_DATE_REQUIRED->value => "Les dates de début et de fin sont requises.",
    ErreurEnum::PROMO_ADD_FAILED->value => "Échec de l'ajout de la promotion.",
    ErreurEnum::PROMO_ACTIVATION_FAILED->value => "Échec de l'activation de la promotion.",
    ErreurEnum::PROMO_NAME_EXISTS->value => "Ce nom de promotion existe déjà.",
    ErreurEnum::PROMO_DATE_INFERIEUR->value => "La date de début doit être avant la date de fin.",
    ErreurEnum::PROMO_DATE_NORME->value => "Les dates doivent être au format YYYY-MM-DD.",
    ErreurEnum::PROMO_NOM_EXISTS->value => "Ce nom de promotion existe déjà.",

    // Erreurs liées aux référentiels
    ErreurEnum::REF_NOM_REQUIRED->value => "Le nom du référentiel est requis.",
    ErreurEnum::REF_NOM_EXISTS->value => "Ce nom de référentiel existe déjà.",
    ErreurEnum::REF_DESCRIPTION_REQUIRED->value => "La description du référentiel est requise.",
    ErreurEnum::REF_CAPACITE_REQUIRED->value => "La capacité est requise et doit être un nombre valide.",
    ErreurEnum::REF_SESSIONS_REQUIRED->value => "Le nombre de sessions est requis.",
    ErreurEnum::REF_PHOTO_REQUIRED->value => "La photo du référentiel est requise.",
    ErreurEnum::REF_PHOTO_INVALID->value => "La photo doit être au format JPG ou PNG et ne pas dépasser 2MB.",

    // Erreurs liées aux apprenants
    ErreurEnum::APPRENANT_NOM_REQUIRED->value => "Le nom de l'apprenant est requis.",
    ErreurEnum::APPRENANT_EMAIL_REQUIRED->value => "L'email de l'apprenant est requis.",
    ErreurEnum::APPRENANT_EMAIL_INVALID->value => "L'email de l'apprenant est invalide.",
    ErreurEnum::APPRENANT_TELEPHONE_REQUIRED->value => "Le numéro de téléphone de l'apprenant est requis.",
    ErreurEnum::APPRENANT_TELEPHONE_INVALID->value => "Le numéro de téléphone de l'apprenant est invalide.",
    ErreurEnum::APPRENANT_MATRICULE_REQUIRED->value => "Le matricule de l'apprenant est requis.",
    ErreurEnum::APPRENANT_MATRICULE_EXISTS->value => "Le matricule de l'apprenant existe déjà.",
    ErreurEnum::APPRENANT_REFERENTIEL_REQUIRED->value => "Le référentiel de l'apprenant est requis.",
    ErreurEnum::APPRENANT_PHOTO_REQUIRED->value => "La photo de l'apprenant est requise.",
    ErreurEnum::APPRENANT_PHOTO_INVALID->value => "La photo de l'apprenant est invalide.",
    ErreurEnum::APPRENANT_ADD_FAILED->value => "Échec de l'ajout de l'apprenant.",
    ErreurEnum::APPRENANT_UPDATE_FAILED->value => "Échec de la modification de l'apprenant.",
    ErreurEnum::APPRENANT_DELETE_FAILED->value => "Échec de la suppression de l'apprenant.",



    // Erreurs liées aux apprenants
ErreurEnum::APPRENANT_DELETE_FAILED->value => "Échec de la suppression de l'apprenant.",

// Erreurs liées à l'importation d'apprenants
ErreurEnum::EXCEL_EMAIL_DUPLIQUE->value => 'Un email existe déjà dans la base de données.',
ErreurEnum::EXCEL_MATRICULE_DUPLIQUE->value => 'Un matricule existe déjà dans la base de données.',
ErreurEnum::EXCEL_COLONNE_VIDE->value => 'Une ou plusieurs colonnes obligatoires sont vides.',
ErreurEnum::FICHIER_NON_EXCEL->value => 'Le fichier doit être un document Excel valide (.xls ou .xlsx).',
    

];
