<?php

// 1. Démarrer la session si ce n’est pas déjà fait
function demarrer_session(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// 2. Détruire complètement la session
function detruire_session(): void {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_unset();
        session_destroy();
    }
}

// 3. Supprimer une clé spécifique de la session
function supprimer_session(string $cle): void {
    if (isset($_SESSION[$cle])) {
        unset($_SESSION[$cle]);
    }
}

// 4. Vérifier si une clé existe dans la session
function session_existe(string $cle): bool {
    return isset($_SESSION[$cle]);
}

// 5. Stocker une valeur dans la session
function stocker_session(string $cle, mixed $valeur): void {
    $_SESSION[$cle] = $valeur;
}

// 6. Récupérer une valeur avec valeur par défaut
function recuperer_session(string $cle, mixed $defaut = null): mixed {
    return $_SESSION[$cle] ?? $defaut;
}

// 7. Ajouter un message dans un tableau de la session (par ex. 'messages', 'erreurs', etc.)
function ajouter_message(string $cle, string $message): void {
    if (!isset($_SESSION[$cle]) || !is_array($_SESSION[$cle])) {
        $_SESSION[$cle] = [];
    }
    $_SESSION[$cle][] = $message;
}

// 8. Récupérer tous les messages et les supprimer (mode flash)
function recuperer_messages(string $cle): array {
    $messages = $_SESSION[$cle] ?? [];
    unset($_SESSION[$cle]); // Flash : suppression après lecture
    return $messages;
}

function recuperer_session_flash(string $cle, mixed $defaut = null): mixed {
    $valeur = $_SESSION[$cle] ?? $defaut;
    unset($_SESSION[$cle]);
    return $valeur;
}
