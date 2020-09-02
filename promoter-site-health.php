<?php
/**
 * Plugin Name: Promoter Site Health
 * GitHub Plugin URI: https://github.com/mt-support/promoter-site-health
 * Author:            Modern Tribe, Inc.
 * Author URI:        http://m.tri.be/1971
 * Description: Add site health checks to make sure Promoter is working properly.
 * License:           GPL version 3 or any later version
 * Requires PHP: 7.0
 * Text Domain: promoter-site-health
 *
 *     This plugin is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     any later version.
 *
 *     This plugin is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *     GNU General Public License for more details.
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
