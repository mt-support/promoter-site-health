<?php
/**
 * Plugin Name:         Promoter Site Health
 * GitHub Plugin URI:   https://github.com/mt-support/promoter-site-health
 * Author:              The Events Calendar
 * Author URI:          http://m.tri.be/1971
 * Description:         Add site health checks to make sure Promoter is working properly.
 * License:             GPL version 3 or any later version
 * License URI:         https://www.gnu.org/licenses/gpl-3.0.txt
 * Version:             1.1.0
 * Requires PHP:        7.4
 * Domain Path:         /languages
 * Text Domain:         promoter-site-health
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
if ( version_compare( PHP_VERSION, '7.4.0', '<' ) ) {
	add_action(
		'admin_notices',
		static function() {
			?>
			<div class="notice notice-error">
				<p>
					<?php
					printf(
						/* translators: %s: PHP version */
						esc_html__( 'Promoter Site Health requires PHP version %s or higher.', 'promoter-site-health' ),
						'7.4'
					);
					?>
				</p>
			</div>
			<?php
		}
	);

	return;
}

/**
 * Load the plugin.
 */
require_once 'load.php';
