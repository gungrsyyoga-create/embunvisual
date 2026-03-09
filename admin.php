<?php
// ═══════════════════════════════════════════════════════════════
// EMBUN VISUAL - ADMIN ENTRY POINT
// File ini terletak di: /embunvisual/admin.php
// Untuk akses admin langsung: http://localhost/embunvisual/admin.php
// ═══════════════════════════════════════════════════════════════

// Load bootstrap
require_once __DIR__ . '/config/bootstrap.php';

// Check if user is logged in
if (!is_logged_in()) {
    redirect('public/login.php');
}

// Check if user has admin access
if (!has_role(ROLE_SUPER_ADMIN) && !has_role(ROLE_STAFF)) {
    redirect('public/login.php');
}

// Include admin dashboard
require_once __DIR__ . '/admin/admin.php';

?>
