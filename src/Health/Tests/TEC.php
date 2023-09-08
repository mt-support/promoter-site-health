<?php

namespace TEC\Extensions\Promoter\Health\Tests;

use TEC\Extensions\Promoter\Health\Critical_Exception;

class TEC implements Runnable {
	public function run() {
		if ( class_exists( 'Tribe__Events__Main', false ) ) {
			if ( version_compare( \Tribe__Events__Main::VERSION, '6.2.2', '<' ) ) {
				throw new Critical_Exception(
					esc_html__( 'Make sure The Events Calendar plugin is updated to the latest version, at least 6.2.2', 'promoter-site-health' ),
					sprintf(
						'<p><a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a> %3$s</p>',
						esc_url( 'https://wordpress.org/plugins/the-events-calendar/' ),
						esc_html__( 'Download The Events Calendar', 'promoter-site-health' ),
						esc_html_x( ' plugin', 'Describing a plugin name before this translation.', 'promoter-site-health' )
					)
				);
			}

			return;
		}

		throw new Critical_Exception(
			esc_html__( 'Make sure The Events Calendar plugin is installed and activated on this WordPress installation', 'promoter-site-health' ),
			sprintf(
				'<p><a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a> %3$s</p>',
				esc_url( 'https://wordpress.org/plugins/the-events-calendar/' ),
				esc_html__( 'Download The Events Calendar', 'promoter-site-health' ),
				esc_html_x( ' plugin', 'Describing a plugin name before this translation.', 'promoter-site-health' )
			)
		);
	}
}
