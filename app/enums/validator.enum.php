<?php
namespace App\ENUM\VALIDATOR;

enum VALIDATORMETHODE: string
{
    case EMAIL = 'is_email';
    case PASSWORD = 'is_password';
    case USER = 'is_user';


    case PROMO = 'is_promo_name';
    case PROMO_DATE = 'is_promo_date';
    case PROMO_date_valide = 'is_date_valid'; 
    case VALID_GENERAL = 'valid_general'; 
    case PROMO_NOM_UNIQUE = 'is_promo_nom_unique';

    
   
      // Référentiel
      case REF_NOM = 'is_ref_nom';
      case REF_NOM_UNIQUE = 'is_ref_nom_unique';
      case REF_DESCRIPTION = 'is_ref_description';
      case REF_CAPACITE = 'is_ref_capacite';
      case REF_SESSIONS = 'is_ref_sessions';
      case REF_PHOTO = 'is_ref_photo';
      case REF = 'is_ref';


      // apprenant
      case APPRENANT='APPRENANT';


}
