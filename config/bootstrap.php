<?php
// ═══════════════════════════════════════════════════════════════
// EMBUN VISUAL - BOOTSTRAP & INITIALIZATION
// ═══════════════════════════════════════════════════════════════

/**
 * Load Constants
 */
require_once __DIR__ . '/constants.php';

/**
 * Session Initialization
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Database Connection
 */
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Koneksi Database Gagal: ' . mysqli_connect_error()
    ]));
}

// Set charset
mysqli_set_charset($conn, "utf8mb4");

/**
 * Load Core Functions & Includes
 */
require_once INCLUDES_PATH . '/functions.php';

if (file_exists(INCLUDES_PATH . '/mailer.php')) {
    require_once INCLUDES_PATH . '/mailer.php';
}

/**
 * Error Handling (Development)
 */
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

/**
 * Helper Functions
 */

/**
 * Redirect to URL
 */
function redirect($url) {
    header("Location: " . $url);
    exit();
}

/**
 * Check if user is logged in
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) || isset($_SESSION['admin_id']);
}

/**
 * Get current user role
 */
function get_user_role() {
    return $_SESSION['role'] ?? null;
}

/**
 * Check if user has specific role
 */
function has_role($role) {
    return get_user_role() === $role;
}

/**
 * JSON response helper
 */
function json_response($status, $message = '', $data = []) {
    header('Content-Type: application/json');
    echo json_encode(array_merge([
        'status' => $status,
        'message' => $message
    ], $data));
    exit();
}

/**
 * Get relative path to root
 */
function base_url($path = '') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $baseDir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    return $protocol . '://' . $host . $baseDir . ltrim($path, '/');
}

/**
 * Escape user input
 */
function sanitize($data) {
    global $conn;
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = sanitize($value);
        }
        return $data;
    }
    return mysqli_real_escape_string($conn, trim($data));
}

/**
 * Format currency
 */
function format_currency($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

/**
 * Format date
 */
function format_date($date, $format = DATE_FORMAT) {
    return date($format, strtotime($date));
}

?>
