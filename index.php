<?php
/**
 * OpenAlias — Main router
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

$db = new Database(DB_PATH);

// Force setup when no users exist
if ($db->getUserCount() === 0) {
    $route = 'setup';
} else {
    $route = trim($_GET['route'] ?? '', '/');
}

switch (true) {
    // ── First-run setup ──
    case $route === 'setup':
        if ($db->getUserCount() > 0) {
            header('Location: ' . APP_URL);
            exit;
        }
        require __DIR__ . '/pages/setup.php';
        break;

    // ── Dashboard (home) ──
    case $route === '' || $route === 'dashboard':
        requireAuth();
        require __DIR__ . '/pages/dashboard.php';
        break;

    // ── Settings (admin) ──
    case $route === 'settings':
        requireAdmin();
        require __DIR__ . '/pages/settings.php';
        break;

    // ── Login ──
    case $route === 'login':
        if (isAuthenticated()) {
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        }
        require __DIR__ . '/pages/login.php';
        break;

    // ── SSO callback ──
    case $route === 'callback':
        require __DIR__ . '/pages/callback.php';
        break;

    // ── Logout ──
    case $route === 'logout':
        require __DIR__ . '/pages/logout.php';
        break;

    // ── API ──
    case str_starts_with($route, 'api/'):
        requireAuth();
        require __DIR__ . '/pages/api.php';
        break;

    // ── Short-link redirect (must be last) ──
    default:
        $link = $db->findLinkBySlug($route);
        if ($link && $link['is_active']) {
            $db->incrementClicks($route);
            header('Location: ' . $link['target_url'], true, 301);
            exit;
        }
        http_response_code(404);
        require __DIR__ . '/pages/404.php';
        break;
}
