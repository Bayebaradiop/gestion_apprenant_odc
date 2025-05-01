<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/enums/chemin_page.php';
use App\Enums\CheminPage;
require_once CheminPage::ROUTE_WEB->value;
