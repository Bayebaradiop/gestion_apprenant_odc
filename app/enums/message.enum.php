<?php
namespace App\ENUM\MESSAGE;

enum MSGENUM: string
{
    // Messages généraux
    case REUSSI = 'succes';
    case LOGIN_EMAIL = 'login.email';
    case PASSWORD_REQUIRED = 'password.required';
    case PASSWORD_INVALID = 'password.invalid';
    case LOGIN_INCORRECT = 'login.incorrect';

    // Messages liés aux promotions
    case PROMO_AJOUT_REUSSI = 'La promotion a été ajoutée avec succès';
    case PROMO_ACTIVATION_REUSSIE = 'La promotion a été activée avec succès';

    // Messages liés aux apprenants
    case APPRENANT_AJOUT_REUSSI = 'L\'apprenant a été ajouté avec succès';
    case APPRENANT_MODIFICATION_REUSSIE = 'L\'apprenant a été modifié avec succès';
    case APPRENANT_SUPPRESSION_REUSSIE = 'L\'apprenant a été supprimé avec succès';
    case APPRENANT_IMPORT_REUSSI = 'Les apprenants ont été importés avec succès';
    case APPRENANT_NON_TROUVE = 'Aucun apprenant trouvé';
    case APPRENANT_MATRICULE_EXISTE = 'Le matricule de l\'apprenant existe déjà';
    case APPRENANT_EMAIL_EXISTE = 'L\'email de l\'apprenant existe déjà';
}
