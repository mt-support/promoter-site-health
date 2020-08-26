<?php
/**
 * Plugin Name: Promoter Site Health
 * Description: Add site health checks to make sure Promoter is working properly.
 * Requires PHP: 7.0
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
