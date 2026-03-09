<?php
// ═══════════════════════════════════════════════════════════════
// EMBUN VISUAL - LEGACY CONFIG (Backward Compatible)
//
// Note: This file is kept for backward compatibility.
// For new code, use config/bootstrap.php instead.
// ═══════════════════════════════════════════════════════════════

// Load bootstrap for centralized initialization
require_once __DIR__ . '/bootstrap.php';

// Legacy aliases for backward compatibility
$host = DB_HOST;
$user = DB_USER;
$pass = DB_PASS;
$db = DB_NAME;

// $conn is already initialized in bootstrap.php
// But we declare it here in case old code expects it

?>
