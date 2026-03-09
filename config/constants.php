<?php
// ═══════════════════════════════════════════════════════════════
// EMBUN VISUAL - CONSTANTS & CONFIGURATION
// ═══════════════════════════════════════════════════════════════

/**
 * PROJECT BASE PATHS
 */
define('BASE_PATH', dirname(dirname(__FILE__)));
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('DATABASE_PATH', BASE_PATH . '/database');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('RESOURCES_PATH', BASE_PATH . '/resources');
define('VIEWS_PATH', RESOURCES_PATH . '/views');
define('INCLUDES_PATH', BASE_PATH . '/includes');

/**
 * UPLOAD & FILE PATHS
 */
define('UPLOADS_PATH', BASE_PATH . '/uploads');
define('UPLOADS_AUDIT_PATH', UPLOADS_PATH . '/audit');
define('UPLOADS_GALERI_PATH', UPLOADS_PATH . '/galeri');
define('TEMPLATES_PATH', BASE_PATH . '/tema');
define('THEMES_PATH', BASE_PATH . '/themes');
define('UNDANGAN_PATH', BASE_PATH . '/undangan');

/**
 * DATABASE CONFIG
 */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'embun_visual');

/**
 * APPLICATION SETTINGS
 */
define('APP_NAME', 'Embun Visual');
define('APP_VERSION', '1.0.0');
define('APP_ENV', getenv('APP_ENV') ?: 'production');
define('APP_DEBUG', getenv('APP_DEBUG') === 'true' ? true : false);

/**
 * TIER TYPES
 */
define('TIER_BASIC', 'basic');
define('TIER_PREMIUM', 'premium');
define('TIER_EXCLUSIVE', 'exclusive');

/**
 * ROLES
 */
define('ROLE_SUPER_ADMIN', 'Super Admin');
define('ROLE_STAFF', 'Staff');
define('ROLE_BASIC', 'Basic');

/**
 * RESPONSE TYPES
 */
define('RESPONSE_HADIR', 'hadir');
define('RESPONSE_TIDAK', 'tidak');
define('RESPONSE_BELUM', 'belum');

/**
 * STATUS
 */
define('STATUS_ACTIVE', 1);
define('STATUS_INACTIVE', 0);

/**
 * DATE FORMAT
 */
define('DATE_FORMAT', 'd/m/Y');
define('DATETIME_FORMAT', 'd/m/Y H:i:s');
define('TIME_FORMAT', 'H:i:s');
?>
