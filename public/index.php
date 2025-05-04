<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/enums/vers_page.php';
use App\Enums\vers_page;
require_once vers_page::ROUTE_WEB->value;
