<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: index.php?msg=Please+login+first');
        exit;
    }
}

function require_volunteer(): void {
    require_login();

    if (($_SESSION['role'] ?? '') !== 'volunteer') {
        header('Location: dashboard.php?msg=Volunteer+access+required');
        exit;
    }
}

function h($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

?>