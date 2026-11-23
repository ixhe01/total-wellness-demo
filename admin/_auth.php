<?php
require_once __DIR__ . '/../includes/db.php';
session_name('twc_admin');
session_start();
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));

function current_user() { return $_SESSION['user'] ?? null; }
function require_login(bool $json = false) {
    if (current_user()) return;
    if ($json) { http_response_code(401); echo json_encode(['error' => 'Session expired']); exit; }
    header('Location: login.php'); exit;
}
function check_csrf() {
    $t = $_SERVER['HTTP_X_CSRF'] ?? ($_POST['csrf'] ?? '');
    if (!hash_equals($_SESSION['csrf'], $t)) { http_response_code(403); echo json_encode(['error' => 'Invalid token']); exit; }
}
