<?php
declare(strict_types=1);
// Override these with environment variables in production.
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_NAME', getenv('DB_NAME') ?: 'user_management');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
// If deployed under a subdirectory, e.g. /user-management-system, set APP_BASE to it.
define('APP_BASE', rtrim(getenv('APP_BASE') ?: '', '/'));
if (session_status() !== PHP_SESSION_ACTIVE) {
 session_name('ums_session');
 session_set_cookie_params(['httponly'=>true,'secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off','samesite'=>'Lax','path'=>APP_BASE ?: '/']);
 session_start();
}
