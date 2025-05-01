<?php
namespace App\ENUM\ERREUR;

enum ErreurEnum: string
{
    // Authentification
    case LOGIN_REQUIRED = 'login_required';
    case LOGIN_EMAIL = 'login_email';
    case PASSWORD_REQUIRED = 'password_required';
    case PASSWORD_INVALID = 'password_invalid';
    case LOGIN_INCORRECT = 'login.incorrect';

    // Promotions
    case PROMO_ID_REQUIRED = 'promo_id_obligatoir';
    case PROMO_NAME_REQUIRED = 'nom_du_promo_obligatoire';
    case PROMO_NAME_EXISTS = 'nom_dejas_existe';
    case PROMO_DATE_REQUIRED = 'promo_date_obligatoire';
    case PROMO_DATE_INVALID = 'promo_date_invalide';
    case PROMO_ADD_FAILED = 'promo_ajout_echoue';
    case PROMO_ACTIVATION_FAILED = 'promo_activation_failed';
    case PROMO_DATE_INFERIEUR = 'promo_date_inferieur';
    case PROMO_DATE_NORME = 'promo_date_norme';
    case PROMO_NOM_EXISTS = 'promo_nom_exists';

    case PROMO = 'is_promo_name';
    case PROMO_DATE = 'is_promo_date';
    case PROMO_date_valide = 'is_date_valid'; 
    case VALID_GENERAL = 'valid_general'; 
    case PROMO_NOM_UNIQUE = 'is_promo_nom_unique';

    // Référentiels
    case REF_NOM_REQUIRED = 'nom_referentiel_obligatoire';
    case REF_NOM_EXISTS = 'ref_nom_exists';
    case REF_DESCRIPTION_REQUIRED = 'referentiel_description_obligatoire';
    case REF_CAPACITE_REQUIRED = 'rref_capacite_obligatoire';
    case REF_SESSIONS_REQUIRED = 'referentiel_sessions_obligatoire';
    case REF_PHOTO_REQUIRED = 'referentiel_photo_obligatoire';
    case REF_PHOTO_EXISTS = 'referentiel_photo_existe_deja';
    case REF_PHOTO_INVALID = 'referentiel_photo_invalide';





    // Apprenants



    

    case APPRENANT_NOM_REQUIRED = 'nom_apprenant_obligatoire';
    case APPRENANT_EMAIL_REQUIRED = 'email_apprenant_obligatoire';
    case APPRENANT_EMAIL_INVALID = 'email_apprenant_invalide';
    case APPRENANT_TELEPHONE_REQUIRED = 'telephone_apprenant_obligatoire';
    case APPRENANT_TELEPHONE_INVALID = 'telephone_apprenant_invalide';
    case APPRENANT_MATRICULE_REQUIRED = 'matricule_apprenant_obligatoire';
    case APPRENANT_MATRICULE_EXISTS = 'matricule_apprenant_existe_deja';
    case APPRENANT_REFERENTIEL_REQUIRED = 'referentiel_apprenant_obligatoire';
    case APPRENANT_PHOTO_REQUIRED = 'photo_apprenant_obligatoire';
    case APPRENANT_PHOTO_INVALID = 'photo_apprenant_invalide';
    case APPRENANT_DATE_NAISSANCE_REQUIRED = 'date_naissance_apprenant_obligatoire';
    case APPRENANT_DATE_NAISSANCE_INVALID = 'date_naissance_apprenant_invalide';
    case APPRENANT_LIEU_NAISSANCE_REQUIRED = 'lieu_naissance_apprenant_obligatoire';
    case APPRENANT_ADRESSE_REQUIRED = 'adresse_apprenant_obligatoire';
    case APPRENANT_ADD_FAILED = 'ajout_apprenant_echoue';
    case APPRENANT_UPDATE_FAILED = 'modification_apprenant_echoue';
    case APPRENANT_DELETE_FAILED = 'suppression_apprenant_echoue';

    case APPRENANT_TUTEUR_NOM_REQUIRED = 'Le nom du tuteur est obligatoire.';
case APPRENANT_LIEN_PARENT_REQUIRED = 'Le lien de parenté est obligatoire.';
case APPRENANT_TUTEUR_ADRESSE_REQUIRED = 'L\'adresse du tuteur est obligatoire.';
case APPRENANT_TUTEUR_TELEPHONE_REQUIRED = 'Le téléphone du tuteur est obligatoire.';
case APPRENANT_TUTEUR_TELEPHONE_INVALID = 'Le téléphone du tuteur doit être un numéro valide de 9 chiffres.';
case APPRENANT_TELEPHONE_EXISTS = 'Le téléphone existe dejas.';


case APPRENANT_EMAIL_UNIQUE = 'Cette adresse email est déjà utilisée.';


    // Importation d'apprenants
    case EXCEL_EMAIL_DUPLIQUE = 'email_unique';
    case EXCEL_MATRICULE_DUPLIQUE = 'matricule_unique';
    case EXCEL_COLONNE_VIDE = 'colonne_non_vide';
    case FICHIER_NON_EXCEL = 'fichier_valid';

    
}

