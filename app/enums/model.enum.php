<?php
namespace App\Models;

enum JSONMETHODE: string
{
    case ARRAYTOJSON = 'array_to_json';
    case JSONTOARRAY = 'json_to_array';
}

enum AUTHMETHODE: string
{
    case LOGIN = 'login';
    case LOGOUT = 'logout';
    case REGISTER = 'register';
    case FORGOT_PASSWORD = 'forgot_password';
    case RESET_PASSWORD = "reset_password";
}

enum PROMOMETHODE: string {
    case GET_ALL = 'get_all';
    case ACTIVER_PROMO = 'activer_promo';
    case AJOUTER_PROMO = 'ajouter_promo';
    case GET_BY_ID = 'get-by-id'; 
    case GET_ACTIVE_PROMO = 'get_active_promo';
    case DESACTIVER_PROMO = 'desactiver_promo';

}
    
enum REFMETHODE: string {
    case GET_ALL = 'get_all';
    case GET_ACTIVE = 'get_active';
    case AJOUTER = 'ajouter';
    case AFFECTER = 'affecter';
    case GET_NON_AFFECTES = 'get_referenciels_non_affectes';
    case AFFECTER_REF_PROMO_ACTIVE = 'affecter_ref_promo_active';
    case  GET_APPRENANTS_COUNT= 'get_apprenants_count';
    case DESAFFECTER = 'desaffecter';
}


enum APPMETHODE: string {
    case GET_ALL = 'get_all';
    case GET_ACTIVE = 'get_active';
    case AJOUTER = 'ajouter';
    case IMPORTER = 'importer';

    
}