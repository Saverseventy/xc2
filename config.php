<?php
// --------------------------
// IPTV SERVER CONFIGURATION
// --------------------------

// LOGIN
define('XTREAM_USER', 'admin');
define('XTREAM_PASS', '123456');

// SERVER
define('PROTOCOL', !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http');
define('HOST', $_SERVER['HTTP_HOST'] ?? 'localhost');
define('BASE_URL', PROTOCOL . '://' . HOST . '/');

// TIMEZONE
date_default_timezone_set('America/Sao_Paulo');

// MAX CONNECTIONS
define('MAX_CONNECTIONS', 1);

// LOGOS / IMAGES
define('DEFAULT_LOGO', BASE_URL . 'logos/brazil_flag.png');
