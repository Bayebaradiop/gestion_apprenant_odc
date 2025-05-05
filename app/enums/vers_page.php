<?php
namespace App\Enums;

enum vers_page: string
{

    // Modèle
    case MODEL = __DIR__ . '/../models/model.php';
    case AUTH_MODEL = __DIR__ . '/../models/auth.model.php';
    case REF_MODEL = __DIR__ . '/../models/ref.model.php';
    case PROMO_MODEL = __DIR__ . '/../models/promo.model.php';
    case APPRENANT_MODEL = __DIR__ . '/../models/apprenant.model.php';






    // Vues
    case VIEW_LOGIN = __DIR__ . '/../views/login/login.view.php';
    case VIEW_RESETPASSWORD = __DIR__ . '/../views/login/reset_password.view.php';
    case VIEW_PROMO = __DIR__ . '/../views/promo/promo.view.php';
    case VIEW_REFERENCIEL = __DIR__ . '/../views/referenciel/referenciel.view.php';
    case VIEW_ERREUR = __DIR__ . '/../views/erreur/erreur.view.php';
    case VIEW_APPRENANT = __DIR__ . '/../views/apprenant/apprenant.view.php';
    





    // Contrôleurs
    case CONTROLLER = __DIR__ . '/../controllers/controller.php';
    case AUTH_CONTROLLER = __DIR__ . '/../controllers/auth.controller.php';
    case LAYOUT_CONTROLLER = __DIR__ . '/../controllers/layout.controller.php';
    case REFERENCIEL_CONTROLLER = __DIR__ . '/../controllers/referenciel.controller.php';
    case PROMO_CONTROLLER = __DIR__ . '/../controllers/promo.controller.php';
    case ERROR_CONTROLLER = __DIR__ . '/../controllers/error.controller.php';
    case APPRENANT_CONTROLLER = __DIR__ . '/../controllers/apprenant.controller.php';




    


    // Données
    case DATA_JSON = __DIR__ . '/../data/data.json';

    // Routes
    case ROUTE_WEB = __DIR__ . '/../route/route.web.php';

    // Services
    case SESSION_SERVICE = __DIR__ . '/../services/session.service.php';
    case VALIDATOR_SERVICE = __DIR__ . '/../services/validator.service.php';

    // Layouts
    case LAYOUT_BASE = __DIR__ . '/../views/layout/base.layout.php';



    ///////////////////enum////////////
    case ERREUR_ENUM = __DIR__ . '/../enums/erreur.enum.php';
    case MESSAGE_ENUM = __DIR__ . '/../enums/message.enum.php';
    case VALIDATOR_ENUM = __DIR__ . '/../enums/validator.enum.php';
    case MODEL_ENUM = __DIR__ . '/../enums/model.enum.php';

    // Traductions
    case ERROR_FR = __DIR__ . '/../views/translate/fr/error.fr.php';
    case MESSAGE_FR = __DIR__ . '/../views/translate/fr/message.fr.php';



    // Public
    case INDEX_PUBLIC = __DIR__ . '/../../public/index.php';


    //le style

    case CSS_DASHBOARD = __DIR__ . '/../../public/assets/css/dashboard.css';
    case CSS_LOGIN = '/assets/css/login/login.css';
    case CSS_PROMO = '/assets/css/promo/promo.css';
    case CSS_PROMOa = '/assets/css/promo/add_promo.css';
    case CSS_REFERENCIEL = '/assets/css/referenciel/referenciel.css';


    // Images
    case IMG_LOGO_LOGIN = '/assets/images/login/logo_odc.png';
    case IMG_LOGO_SIDEBAR = __DIR__ . '/assets/images/sidebar/logo.png';
}
