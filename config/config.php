<?php

define('APPROOT', dirname(dirname(__FILE__)));

// Dynamically generate the URLROOT based on the host
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
// Only append the subfolder if we are not at the root domain
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
// Remove '/public' from the end of the script path if it exists, as we rewrite to it
$scriptPath = preg_replace('/\/public$/', '', $scriptPath);
$scriptPath = $scriptPath === '/' || $scriptPath === '\\' ? '' : $scriptPath;

define('URLROOT', $protocol . $host . $scriptPath);

define('SITENAME', 'Abhish.in Shared Hosting');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'shared_hosting');
