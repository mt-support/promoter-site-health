<?php

namespace Tribe\Extensions\Promoter\Health\Tests;

use Tribe\Extensions\Promoter\Health\Critical_Exception;

class TEC implements Runnable {
	public function run() {
		if ( class_exists( 'Tribe__Events__Main' ) ) {
			return;
		}

		throw new Critical_Exception(
			__( 'Make sure The Events Calendar plugin is installed and activated on this WordPress installation', 'promoter-site-health' ),
			sprintf(
				'<p><a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>%3$s</p>',
				esc_url( 'https://wordpress.org/plugins/the-events-calendar/' ),
				esc_html__( 'Download The Events Calendar', 'promoter-site-health' ),
				esc_html_x( ' plugin', 'Describing a plugin name before this translation.', 'promoter-site-health' )
			)
		);
	}
}
