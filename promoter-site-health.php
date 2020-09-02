<?php
/**
 * Plugin Name: Promoter Site Health
 * GitHub Plugin URI: https://github.com/mt-support/promoter-site-health
 * Author:            Modern Tribe, Inc.
 * Author URI:        http://m.tri.be/1971
 * Description: Add site health checks to make sure Promoter is working properly.
 * Requires PHP: 7.0
 * Text Domain: promoter-site-health
 */

require_once __DIR__ . '/vendor/autoload.php';

if ( version_compare( PHP_VERSION, '7.0.0' ) >= 0 ) {
	add_filter( 'site_status_tests', [ \Tribe\Extensions\Promoter\Site_Health::class, 'add_status_test' ] );
	add_action( 'wp_ajax_health-check-promoter-site_health', [
		\Tribe\Extensions\Promoter\Background_Test::class,
		'run_test',
	] );

	return;
}

// TODO: Add notice if PHP version is not compatible.
