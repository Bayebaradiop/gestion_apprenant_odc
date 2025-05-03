<?php
require_once __DIR__ . '/../enums/chemin_page.php';
use App\Enums\CheminPage;
require_once CheminPage::CONTROLLER->value;



function uploadPhoto(array $file, string $uploadDir, string $defaultPath = "assets/images/promo/default.jpg"): ?string {
    if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
        $photoName = basename($file['name']);
        $photoPath = rtrim($uploadDir, '/') . '/' . $photoName;

        if (move_uploaded_file($file['tmp_name'], $photoPath)) {
            return "assets/images/promo/" . $photoName;
        }

        return null;
    }

    return $defaultPath;
}



function gerer_upload_photo(array $photo): ?string {
    $repertoire = __DIR__ . '/../../public/assets/images/promo/';
    return uploadPhoto($photo, $repertoire);
}


/**
 * Calcule le prochain ID pour une nouvelle promotion.
 */
function getNextPromoId(array $promotions): int {
    $lastId = 0;
    foreach ($promotions as $promo) {
        if ($promo['id'] > $lastId) {
            $lastId = $promo['id'];
        }
    }
    return $lastId + 1;
}






function render(string $vue, array $donnees = [], ?string $layout = 'base.layout'): void {
    $baseViewPath = dirname(__DIR__) . '/views/';
    $baseLayoutPath = $baseViewPath . 'layout/';

    $cheminVue = str_ends_with($vue, '.php') ? $vue : $baseViewPath . trim($vue, '/') . '.view.php';

    if (!file_exists($cheminVue)) {
        throw new Exception("Vue '$cheminVue' introuvable.");
    }

  

    extract($donnees);
    ob_start();
    require $cheminVue;
    $contenu = ob_get_clean();

    if ($layout !== null) {
        $cheminLayout = $baseLayoutPath . trim($layout, '/') . '.php';

        if (!file_exists($cheminLayout)) {
            throw new Exception("Layout '$layout' introuvable.");
        }

        require $cheminLayout;
    } else {
        echo $contenu;
    }
}


function redirect_to_route(string $route, array $params = []): void {
    // Si on a des paramètres à passer dans l'URL
    if (!empty($params)) {
        $query = http_build_query($params);
        $route .= (strpos($route, '?') === false ? '?' : '&') . $query;
    }

    header("Location: $route");
    exit(); // Toujours arrêter l'exécution après une redirection
}

/**
 * Vérifie si la requête est une requête POST et si un champ spécifique est défini.
 */
function is_post_request_with_field(string $field): bool {
    return $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST[$field]);
}



/** --- FONCTION GÉNÉRIQUE PAGINER --- */
function paginer(array $items, int $page, int $parPage): array {
    $total = count($items);
    $pages = max(1, ceil($total / $parPage));
    $page = max(1, min($page, $pages));
    $debut = ($page - 1) * $parPage;
    $elements = array_slice($items, $debut, $parPage);

    return [
        'items' => $elements,
        'pageCourante' => $page,
        'pages' => $pages,
        'debut' => $debut,
        'total' => $total
    ];
}


function generer_matricule(): string {
    return "APP" . date('Ymd') . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
}





?>