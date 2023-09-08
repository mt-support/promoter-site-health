<?php

namespace TEC\Extensions\Promoter\Health\Tests;

use TEC\Extensions\Promoter\Health\Critical_Exception;

class ET implements Runnable {
	public function run() {
		if ( class_exists( 'Tribe__Tickets__Main', false ) ) {
			if ( version_compare( \Tribe__Tickets__Main::VERSION, '5.6.5', '<' ) ) {
				 throw new Critical_Exception(
					esc_html__( 'Make sure Event Tickets plugin is updated to the latest version, at least 5.6.5', 'promoter-site-health' ),
					sprintf(
						'<p><a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a> %3$s</p>',
						esc_url( 'https://wordpress.org/plugins/event-tickets/' ),
						esc_html__( 'Download Event Tickets', 'promoter-site-health' ),
						esc_html_x( 'plugin',  'Describing a plugin name before this translation.', 'promoter-site-health' )
					)
				);
			}

			return;
		}


		throw new Critical_Exception(
			esc_html__( 'Make sure Event Tickets plugin is installed and activated on this WordPress installation', 'promoter-site-health' ),
			sprintf(
				'<p><a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a> %3$s</p>',
				esc_url( 'https://wordpress.org/plugins/event-tickets/' ),
				esc_html__( 'Download Event Tickets', 'promoter-site-health' ),
				esc_html_x( 'plugin',  'Describing a plugin name before this translation.', 'promoter-site-health' )
			)
		);
	}
}
